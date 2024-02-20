require('dotenv').config()

const polyfills = [
  'Promise',
  'Object.assign',
  'Object.values',
  'Array.prototype.find',
  'Array.prototype.findIndex',
  'Array.prototype.includes',
  'String.prototype.includes',
  'String.prototype.startsWith',
  'String.prototype.endsWith'
]

module.exports = {
  // mode: 'spa',

  srcDir: __dirname,

  env: {
    apiUrl: process.env.APP_URL || 'http://api.feedback.test',
    canonicalUrl: process.env.CLIENT_URL || 'http://feedback.test',
    appName: process.env.APP_NAME || 'Feedback',
    appLocale: process.env.APP_LOCALE || 'en',
    githubAuth: !!process.env.GITHUB_CLIENT_ID,
    facebookAuth: !!process.env.FACEBOOK_CLIENT_ID,

    // Yandex metrics
    yandex: {
      id: process.env.YA_METRIKA_ID || null,
      webvisor: process.env.YA_METRIKA_WEBVISOR || false,
      clickmap: process.env.YA_METRIKA_CLICKMAP || false,
      useCDN: process.env.YA_METRIKA_USECDN || false,
      trackLinks: process.env.YA_METRIKA_TRACKLINKS || false,
      accurateTrackBounce: process.env.YA_METRIKA_ACCURATETRACKBOUNCE || false
    },

    AmplitudeKey: process.env.AMPLITUDE_API_KEY || null,
    googleSiteVerification: process.env.GOOGLE_SITE_VERIFICATION || null,
    recaptchaSiteKey: process.env.RECAPTCHA_SITE_KEY || null
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
    ],
    script: [
      { src: `https://cdn.polyfill.io/v2/polyfill.min.js?features=${polyfills.join(',')}` }
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
    { src: '~plugins/ym', ssr: false },
    { src: '~plugins/crisp', ssr: false },
    // Filters
    '~plugins/filters/date',
    '~plugins/directives/scroll',
    { src: '~plugins/amplitude', ssr: false },
    { src: '~plugins/facebook-pixel', ssr: false }
  ],

  modules: [
    '@nuxtjs/router',
    'nuxt-vuex-router-sync',
    '~/modules/spa',
    ['@nuxtjs/moment', ['fr', 'ja', 'de', 'ru']],
    'nuxt-facebook-pixel-module',
    ['@nuxtjs/google-analytics', {
      id: process.env.GOOGLE_ANALYTICS_ID || null,
      dev: false
    }]
  ],

  facebook: {
    /* module options */
    track: 'PageView',
    pixelId: process.env.FACEBOOK_PIXEL_ID || 'not-set',
    disabled: true
  },

  build: {
    extractCSS: true
  }
}
