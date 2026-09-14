<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use App\Services\GeminiAiService;
use App\Contracts\AiProviderInterface;


/**
 * This implementation was not necessary for this project
 * since there is only one AI provider at a time. 
 * The project can be perfectly work without it
 * but we are trying to respect SOLID pronciples  as much as possible
 * in case the project expands later
 * (Dependency Inversion Principle here)
 */

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        /* Tell Laravel "whenever someone asks for AiProviderInterface
        give them GeminiAiService */
        $this->app->bind(AiProviderInterface::class, GeminiAiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function (): void {
            $routeName = request()->route()?->getName() ?? '';
            $siteTitle = 'LIU Digital Yearbook';

            $sectionTitles = [
                'dashboard' => 'Dashboard',
                'settings.index' => 'Settings',
                'ai-generations.index' => 'AI Review Queue',
                'profile.edit' => 'Profile',
            ];

            $resourceTitles = [
                'academic-years' => 'Academic Years',
                'majors' => 'Majors',
                'campuses' => 'Campuses',
                'schools' => 'Schools',
                'event-categories' => 'Event Categories',
                'graduations' => 'Graduations',
                'media' => 'Media Library',
                'events' => 'Events',
                'graduates' => 'Graduates',
                'users' => 'Users',
            ];

            $pageTitle = $sectionTitles[$routeName] ?? null;

            if ($pageTitle === null) {
                foreach ($resourceTitles as $resource => $label) {
                    if (! Str::startsWith($routeName, $resource . '.')) {
                        continue;
                    }

                    $action = Str::afterLast($routeName, '.');
                    $pageTitle = match ($action) {
                        'index' => $label,
                        'create' => 'Create ' . Str::singular($label),
                        'edit' => 'Edit ' . Str::singular($label),
                        'show' => 'View ' . Str::singular($label),
                        default => $label,
                    };

                    $routeParameters = request()->route()?->parameters() ?? [];
                    $record = collect($routeParameters)->first(fn ($value) => is_object($value));

                    if ($record && in_array($action, ['edit', 'show'], true)) {
                        $recordName = data_get($record, 'name')
                            ?? data_get($record, 'title')
                            ?? data_get($record, 'file_name');

                        if (! $recordName && $resource === 'graduations') {
                            $recordName = data_get($record, 'academicYear.title');
                        }

                        if ($recordName) {
                            $pageTitle = $pageTitle . ' — ' . $recordName;
                        }
                    }

                    break;
                }
            }

            $pageTitle ??= 'Administration';

            config([
                'app.name' => $pageTitle === 'Administration'
                    ? 'Administration | ' . $siteTitle
                    : $pageTitle . ' | Administration | ' . $siteTitle,
            ]);
        });
    }
}
