
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins:  [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
	
    ],
    host: 'node',
    origin: 'http://localhost:5173',
    build: {
        outDir: 'public/build',  // Permite conexões externas
        manifest: true,
    },
    base: '/',
});
