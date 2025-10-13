<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\FrontendControl; // Make sure this is imported

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        try {
            // 1. Fetch the saved settings from the database
            $frontendSettings = FrontendControl::first();

            // 2. Get the saved category IDs, defaulting to an empty array if no settings exist
            $headerCategoryIds = $frontendSettings->header_category_ids ?? [];
            $sidebarCategoryIds = $frontendSettings->sidebar_category_ids ?? [];

            // 3. Fetch only the categories selected for the header, preserving the saved order
            $headerCategories = collect($headerCategoryIds)->map(function ($id) {
                return Category::with([
                    'children' => fn($q) => $q->where('status', 1),
                    'children.children' => fn($q) => $q->where('status', 1)
                ])->find($id);
            })->filter(); // filter() removes any null values if a category was deleted

            // 4. Fetch only the categories selected for the sidebar, preserving the saved order
            $sidebarCategories = collect($sidebarCategoryIds)->map(function ($id) {
                return Category::with([
                    'children' => fn($q) => $q->where('status', 1),
                    'children.children' => fn($q) => $q->where('status', 1)
                ])->find($id);
            })->filter();

            // 5. Share both variables globally with all Blade views
            View::share('headerCategories', $headerCategories);
            View::share('sidebarCategories', $sidebarCategories);

        } catch (\Exception $e) {
            // Failsafe in case the database isn't ready (e.g., during migrations)
            View::share('headerCategories', collect());
            View::share('sidebarCategories', collect());
        }
    }
}