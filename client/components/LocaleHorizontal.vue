<template>
  <ul class="navbar-nav justify-content-center">
    <li v-for="(value, key) in locales" :key="key" class="nav-item">
      <a class="nav-link" href="#" @click.prevent="setLocale(key)">
        {{ value }}
      </a>
    </li>
  </ul>
</template>

<script>
import { mapGetters } from 'vuex'
import { loadMessages } from '~/plugins/i18n'

export default {
  props: {
    up: Boolean,
    right: Boolean
  },

  computed: mapGetters({
    locale: 'lang/locale',
    locales: 'lang/locales'
  }),

  methods: {
    setLocale (locale) {
      if (this.$i18n.locale !== locale) {
        loadMessages(locale)

        this.$store.dispatch('lang/setLocale', { locale })
      }
    }
  }
}
</script>
