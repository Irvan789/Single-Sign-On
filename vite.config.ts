import { defineConfig } from "vite"

import tailwindcss from "@tailwindcss/vite"
import laravel from "laravel-vite-plugin"

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css", 
                "resources/ts/app.ts"
            ],
            refresh: true
        }),
        tailwindcss()
    ],
    server: {
        watch: {
            ignored: [
                "**/.git/**",
                "**/bootstrap/cache/**",
                "**/storage/**",
                "**/vendor/**",
            ]
        }
    }
})
