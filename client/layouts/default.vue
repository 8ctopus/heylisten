<template>
  <div class="layout">
    <navbar v-if="!headless"/>

    <div class="container mt-4">
      <nuxt/>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import { mapGetters } from 'vuex'

export default {
  components: {
    Navbar
  },

  beforeCreate () {
    const headless = this.$route.fullPath.match(/^.*\/.*[?&]{1}headless/)
    this.headless = headless
  },

  head () {
    const canonical = `${process.env.canonicalUrl}${this.$route.path}`
    const landingPage = this.locale === 'en' ? '/' : `/${this.locale}`

    return {
      htmlAttrs: {
        lang: this.locale
      },
      link: [
        { hid: 'canonical', rel: 'canonical', href: canonical }
      ],
      meta: [
        { hid: 'description', name: 'description', content: this.$t('welcome_meta_description') },
        { hid: 'robots', name: 'robots', content: 'noindex' },
        // og
        { hid: 'og:type', property: 'og:type', content: 'website' },
        { hid: 'og:title', property: 'og:title', content: this.$t('og_title') },
        { hid: 'og:description', property: 'og:description', content: this.$t('og_description') },
        { hid: 'og:url', property: 'og:url', content: canonical },
        { hid: 'og:image', property: 'og:image', content: `${process.env.canonicalUrl}/hey-opengraph.png` },
        // twitter
        { hid: 'twitter:card', property: 'twitter:card', content: 'summary_large_image' },
        { hid: 'twitter:title', property: 'twitter:title', content: this.$t('og_title') },
        { hid: 'twitter:description', property: 'twitter:description', content: this.$t('og_description') },
        { hid: 'twitter:url', property: 'twitter:url', content: canonical },
        { hid: 'twitter:image', property: 'twitter:image', content: `${process.env.canonicalUrl}/hey-opengraph.png` }
      ]
    }
  },

  computed: mapGetters({
    authenticated: 'auth/check',
    locale: 'lang/locale'
  })
}
</script>
