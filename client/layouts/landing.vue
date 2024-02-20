<template>
  <div class="container p-0">
    <div>
      <!-- #region Navigation bar -->
      <nav class="navbar navbar-expand-lg bg-primary navbar-dark shadow-none px-sm-3 px-md-5">
        <router-link :to="{ name: 'welcome' }" class="navbar-brand"><img :src="$t('hey_listen_logo.src')" :alt="$t('hey_listen_logo.src')"></router-link>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-top" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"/>
        </button>

        <div id="navbar-top" class="collapse navbar-collapse navbar-dark">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <router-link :to="{ path: `${ landingHref }#why-us` }" class="nav-link">{{ $t('landing.why_us') }}</router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ path: `${ landingHref }#features` }" class="nav-link">{{ $t('landing.features') }}</router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ path: `${ landingHref }#plans` }" class="nav-link">{{ $t('landing.plans') }}</router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ path: `${ landingHref }#who-is-it-for` }" class="nav-link">{{ $t('landing.who_is_it_for') }}</router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ path: `${ landingHref }#scenarios` }" class="nav-link">{{ $t('landing.try_it') }}</router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'welcome.faq' }" class="nav-link">{{ $t('landing.faq') }}</router-link>
            </li>

            <li class="nav-item d-none d-lg-block">
              <span class="nav-link">|</span>
            </li>

            <li class="nav-item">
              <router-link v-if="!authenticated" :to="{ name: 'login' }" class="nav-link">{{ $t('login') }}</router-link>
              <router-link v-if="authenticated" :to="{ name: 'home', params: { workspace } }" class="nav-link">{{ $t('home') }}</router-link>
            </li>

            <!--
            <li class="nav-item">
              <router-link v-if="!authenticated" :to="{ name: 'register', params: { _position: 'navbar' } }" class="nav-link d-lg-none">{{ $t('sign_up') }}</router-link>
            </li>
            -->
          </ul>
          <!--
          <router-link v-if="!authenticated" :to="{ name: 'register', params: { _position: 'navbar' } }" class="nav-link btn btn-dark btn-sm ml-2 d-none d-lg-block font-weight-bold">{{ $t('sign_up') }}</router-link>
          -->
        </div>
      </nav>
      <!-- #endregion -->

      <nuxt/>

      <!-- #region Footer -->
      <footer class="bg-primary">
        <nav class="navbar navbar-expand-lg navbar-light shadow-none px-0 px-sm-3 px-md-5 text-center">
          <router-link :to="{ name: 'welcome' }" class="navbar-brand d-none d-lg-block"><img :src="$t('hey_listen_logo.src')" :alt="$t('hey_listen_logo.src')"></router-link>

          <div class="collapse navbar-collapse navbar-dark show">
            <ul class="navbar-nav mr-auto">

              <li class="nav-item">
                <router-link :to="{ path: $t('landing.try_it_link') }" class="nav-link">{{ $t('landing.feedback') }}</router-link>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="mailto:hello@heylisten.app">{{ $t('landing.email_us') }}</a>
              </li>

              <li class="nav-item">
                <router-link :to="{ name: 'welcome.about' }" class="nav-link">{{ $t('landing.about_us') }}</router-link>
              </li>

              <li class="nav-item">
                <router-link :to="{ name: 'welcome.policy' }" class="nav-link">{{ $t('landing.privacy_policy') }}</router-link>
              </li>

              <li class="nav-item">
                <router-link :to="{ name: 'welcome.faq' }" class="nav-link">{{ $t('landing.faq') }}</router-link>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="https://twitter.com/HeyListen_app" target="_blank">
                  <fa :icon="['fab', 'twitter']"/>
                </a>
              </li>

            </ul>

            <ul class="navbar-nav ml-auto d-none d-lg-flex">

              <locale-dropdown up right/>

            </ul>

            <div class="navbar-expand text-center d-block d-lg-none">
              <locale-horizontal />
            </div>

          </div>

        </nav>
      </footer>
      <!-- #endregion -->
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .container {
    background-color: #ffffff;
  }
</style>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from '~/components/LocaleDropdown'
import LocaleHorizontal from '~/components/LocaleHorizontal'

export default {
  components: {
    LocaleDropdown,
    LocaleHorizontal
  },

  head () {
    const canonical = `${process.env.canonicalUrl}${this.$route.path}`
    const landingPage = this.locale === 'en' ? '/' : `/${this.locale}`

    const googleVerification = process.env.googleSiteVerification
      ? { hid: 'google-site-verification', name: 'google-site-verification', content: process.env.googleSiteVerification }
      : {}

    return {
      htmlAttrs: {
        lang: this.locale
      },
      link: [
        { hid: 'canonical', rel: 'canonical', href: canonical },
        { hid: 'description', name: 'description', content: this.$t('welcome_meta_description') }
      ],
      meta: [
        googleVerification,
        { hid: 'description', name: 'description', content: this.$t('welcome_meta_description') },
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

  computed: {
    ...mapGetters({
      authenticated: 'auth/check',
      workspace: 'auth/workspace',
      locale: 'lang/locale'
    }),

    landingHref () {
      return this.$route.name === 'welcome' ? '' : '/'
    }
  }
}
</script>
