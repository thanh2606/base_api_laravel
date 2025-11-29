import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import { createHtmlPlugin } from 'vite-plugin-html'
import vueDevTools from 'vite-plugin-vue-devtools'
import path from 'path'

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/js/app.ts'],
			ssr: 'resources/js/ssr.ts',
			refresh: true,
		}),
		vueDevTools(),
    createHtmlPlugin({}),
		tailwindcss(),
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false,
				},
			},
		}),
	],
	resolve: {
		alias: {
			'@': path.resolve(__dirname, 'resources/js')
		}
	}
})
