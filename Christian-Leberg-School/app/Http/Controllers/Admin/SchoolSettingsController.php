<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.school_settings');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('logo');
        $dir = public_path('images');
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        // Move then resize to a max of 500x500 preserving aspect ratio
        $destination = $dir . DIRECTORY_SEPARATOR . 'school_logo.png';
        $file->move($dir, 'school_logo.png');

        // Resize using Intervention Image if available, otherwise fall back to GD
        try {
            $max = 500;

            if (class_exists(\Intervention\Image\ImageManagerStatic::class)) {
                \Intervention\Image\ImageManagerStatic::configure(['driver' => 'gd']);
                \Intervention\Image\ImageManagerStatic::make($destination)
                    ->resize($max, $max, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($destination, 85);
            } else {
                [$w, $h, $type] = getimagesize($destination);
                $ratio = min($max / $w, $max / $h, 1);
                $newW = (int) round($w * $ratio);
                $newH = (int) round($h * $ratio);

                switch ($type) {
                    case IMAGETYPE_PNG:
                        $src = imagecreatefrompng($destination);
                        break;
                    case IMAGETYPE_JPEG:
                    default:
                        $src = imagecreatefromjpeg($destination);
                        break;
                }

                $dst = imagecreatetruecolor($newW, $newH);
                // preserve transparency for PNG
                if ($type === IMAGETYPE_PNG) {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

                // Save over destination
                if ($type === IMAGETYPE_PNG) {
                    imagepng($dst, $destination, 6);
                } else {
                    imagejpeg($dst, $destination, 85);
                }

                imagedestroy($src);
                imagedestroy($dst);
            }
        } catch (\Throwable $e) {
            // if resizing fails, ignore but keep uploaded file
        }

        return redirect()->route('admin.settings.school.edit')->with('success', 'School logo uploaded successfully.');
    }

    public function deleteLogo()
    {
        $path = public_path('images/school_logo.png');
        if (file_exists($path)) {
            @unlink($path);
            return redirect()->route('admin.settings.school.edit')->with('success', 'School logo removed.');
        }

        return redirect()->route('admin.settings.school.edit')->with('error', 'No logo found.');
    }
}
