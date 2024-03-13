import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    devServer: {
        proxy: {
            '/api': {
                target: 'https://2.56.212.189:8081',
                changeOrigin: true,
                secure: false
            }
        }
    },
    server: {
        https: false
    },
    build: {
        target: 'esnext'
    },
    plugins: [vue()]
})
