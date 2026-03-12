/**
 * Vite Configuration — The build tool that compiles CSS and JavaScript.
 *
 * Vite is a fast frontend bundler. It:
 * - Compiles Tailwind CSS into regular CSS
 * - Bundles JavaScript (Alpine.js, Axios)
 * - Provides hot-reload during development (npm run dev)
 * - Creates optimized production builds (npm run build)
 *
 * Plugins:
 * - laravel-vite-plugin: Integrates Vite with Laravel's @vite() Blade directive
 * - @tailwindcss/vite: Processes Tailwind CSS v4 styles
 */
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        // Laravel plugin: tells Vite which files to compile
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true, // Auto-refresh browser when Blade files change
        }),
        // Tailwind CSS v4 plugin
        tailwindcss(),
    ],
    server: {
        watch: {
            // Ignore compiled views to prevent infinite refresh loops
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
