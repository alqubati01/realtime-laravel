import { fileURLToPath } from 'node:url'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue({
      template: null,
      includeAbsolute: false,
    }),
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
      ziggy: path.resolve('vendor/tightenco/ziggy'),
      '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
      '@css': fileURLToPath(new URL('./resources/css', import.meta.url)),
    },
  },
})
