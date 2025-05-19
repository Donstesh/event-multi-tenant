// nuxt.config.ts

export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
    devtools: { enabled: true },
    css: [
    'bootstrap/dist/css/bootstrap.min.css' // Add Bootstrap CSS here
    ],
    runtimeConfig: {
    public: {
    apiBaseUrl: process.env.NUXT_API_BASE_URL
    }
  }
});