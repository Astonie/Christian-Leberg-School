<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$class = App\Models\SchoolClass::find(2);
$activeYear = App\Models\AcademicYear::active()->first();

echo "Active Year ID: " . $activeYear->id . "\n";
echo "Active Year Name: " . $activeYear->name . "\n\n";

echo "Streams before load: " . $class->streams()->count() . "\n";
echo "Streams for active year: " . $class->streams()->where('academic_year_id', $activeYear->id)->count() . "\n\n";

$class->load(['streams' => function($q) use ($activeYear) {
    $q->where('academic_year_id', $activeYear->id)->with('academicYear');
}]);

echo "Streams after load: " . $class->streams->count() . "\n";
echo "Stream names: ";
foreach ($class->streams as $stream) {
    echo $stream->name . " (Year: " . $stream->academic_year_id . "), ";
}
echo "\n";
