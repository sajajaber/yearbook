import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/public-layout.css',
                'resources/css/pages/home.css',
                'resources/css/pages/archive.css',
                'resources/css/pages/events.css',
                'resources/css/pages/graduates.css',
                'resources/css/pages/graduations.css',
                'resources/css/pages/timeline.css',
                'resources/css/pages/event-detail.css',
                'resources/css/pages/graduate-detail.css',
                'resources/css/pages/graduation-detail.css',
                'resources/css/pages/book.css',
                'resources/css/pages/search.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
