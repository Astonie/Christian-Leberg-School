<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\View\View;

class WebsiteComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Share menus
        $headerMenu = Menu::with('items.children')
            ->byLocation('header')
            ->active()
            ->first();
            
        $footerMenu = Menu::with('items.children')
            ->byLocation('footer')
            ->active()
            ->first();

        $view->with('headerMenu', $headerMenu);
        $view->with('footerMenu', $footerMenu);
    }
}
