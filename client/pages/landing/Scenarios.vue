<template>
  <div :class="{ mobile: mobile }" class="scenarios">
    <ul class="nav nav-tabs nav-fill primary">
      <li v-for="(scenario, index) in $t('landing.scenarios')" :key="index" class="nav-item">
        <a v-if="mobile"
           :href="scenario.url"
           class="nav-link text-left"
           target="_blank"
        ><fa :icon="scenario.icon" class="fa-fw mr-1"/> {{ scenario.label }}</a>
        <a v-else
           :class="{active: scenario === selected}"
           :href="scenario.url"
           class="nav-link"
           target="_blank"
           @click.prevent="select(index)"
        ><fa :icon="scenario.icon" class="mr-2"/> <span class="label">{{ scenario.label }}</span></a>
      </li>
    </ul>
    <div v-if="selected" class="py-4 px-3 px-md-5 description"><fa icon="info-circle" class="text-primary mr-1"/> {{ selected.description }}</div>
    <div class="wrapper">
      <iframe ref="iframe" :src="frameSrc" frameborder="0" class="scenario-frame"/>
      <div :class="{show: !show || loading}" class="loader">
        <button v-if="!show" class="btn btn-secondary" @click="show = true">{{ $t('landing.show_live_preview') }}</button>
        <div v-else-if="loading" class="spinner-border"/>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
    selectedId: 0,
    loading: false,
    desktop: null,
    show: false
  }),

  computed: {
    ...mapGetters({ mobileDevice: 'auth/isMobile' }),

    selected () {
      return this.$t('landing.scenarios')[this.selectedId]
    },

    mobile () {
      if (this.mobileDevice) return true
      if (this.desktop === null) return null
      else return !this.desktop
    },

    frameSrc () {
      if (this.show && this.selected) return this.selected.url
      return null
    }
  },

  watch: {
    frameSrc () {
      // url changed, show loading
      this.loading = true
    }
  },

  mounted () {
    this.$refs.iframe.onload = () => { this.loading = false }
    window.addEventListener('resize', this.handleResize)
    this.setDevice()
  },

  beforeDestroy () {
    window.removeEventListener('resize', this.handleResize)
  },

  methods: {
    select (index) {
      this.selectedId = index
      this.show = false
    },

    handleResize () {
      this.setDevice()
    },

    setDevice () {
      if (typeof window === 'undefined') return
      if (window.innerWidth > 600) this.desktop = true
      else this.desktop = false
    }
  }
}
</script>

<style lang="scss" scoped>
.wrapper {
  width: 100%;
  min-height: 600px;
  height: 60vh;
  position: relative;

  .close {
    display: none;
  }
}
.scenario-frame {
  width: 100%;
  height: 100%;
  /* opacity: .9; */
  border: 1px solid #e0dfdf;
  border-top: none;
}
.loader {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  border: 1px solid #e0dfdf;
  border-top: none;
  background: #f7f9fb;
  display: none;
  text-align: center;
  padding-top: 25%;

}
.loader::before {
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  position: absolute;
  content: '';
  background: url(/scenario-placeholder.png);
  background-size: 100%;
  opacity: .4;
  filter: blur(10px);
}
.loader button {
  position: relative;
}
.loader.show {
  display: block;
}
.spinner-border {
  left: 50%;
  right: 50%;
}
.description {
  border-left: 1px solid #dee2e6;
  background: #f7f9fb;
  border-right: 1px solid #dee2e6;
  border-bottom: 1px solid #dee2e6;
}

.mobile {
  .nav-fill .nav-item {
    flex: 100%;
  }
  .nav-link,
  .nav-link.active {
    background: #d44250;
    margin: 1rem;
    color: #fff;
    border-radius: 5px;
    &:hover {
      background: #c32c3b;
    }
  }
  .description {
    display: none;
  }
  .wrapper {
    display: none;
  }
}
</style>
