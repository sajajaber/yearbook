<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        //
    }
}
