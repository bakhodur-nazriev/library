import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    server: {
        https: false
    },
    build: {
        target: 'esnext'
    },
    plugins: [vue()]
})
