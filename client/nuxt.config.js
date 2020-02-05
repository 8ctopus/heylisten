require('dotenv').config()

module.exports = {
  // mode: 'spa',

  srcDir: __dirname,

  env: {
    apiUrl: process.env.APP_URL || 'http://api.feedback.test',
    canonicalUrl: process.env.CLIENT_URL || 'http://feedback.test',
    appName: process.env.APP_NAME || 'Feedback',
    appLocale: process.env.APP_LOCALE || 'en',
  },

  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'theme-color', name: 'theme-color', content: '#d44250' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  loading: { color: '#fe5f01' },

  router: {
    middleware: ['locale', 'check-auth']
  },

  css: [
    { src: '~assets/sass/app.scss', lang: 'scss' },
    // quill
    'quill/dist/quill.snow.css',
    'quill/dist/quill.bubble.css',
    'quill/dist/quill.core.css'
  ],

  plugins: [
    '~components/global',
    '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    '~plugins/fontawesome',
    // '~plugins/nuxt-client-init',
    { src: '~plugins/bootstrap', ssr: false },
    { src: '~plugins/quill', ssr: false },
    // Filters
    '~plugins/filters/date',
    '~plugins/directives/scroll',
  ],

  modules: [
    '@nuxtjs/router',
    'nuxt-vuex-router-sync',
    '~/modules/spa',
    ['@nuxtjs/moment', ['fr', 'ja', 'de', 'ru']],
  ],

  build: {
    extractCSS: true
  }
}
