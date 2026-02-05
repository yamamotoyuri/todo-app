import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: true,         // これで外部（Windows）からアクセス可能になります
    port: 5173,         // ポート番号を指定
    watch: {
      usePolling: true, // Windowsでのファイル保存をDockerが検知できるようにします
    },
  },
})