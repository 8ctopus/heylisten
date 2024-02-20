<template>
  <div>
    <!-- Header -->
    <header class="bg-primary text-white">
      <div class="p-3 p-md-5 text-center">
        <h1 class="font-weight-bold pb-4" v-html="$t('landing.about_header')"/>
      </div>
    </header>

    <!-- About us -->
    <div class="px-3 px-md-5 about-us">

      <div class="text-center">
        <div class="row pt-3 pt-md-5">
          <div class="d-none d-md-block col-md-4 rabbit-background"/>
          <div class="col-12 col-md-8 pb-3">
            <h2 class="pb-3 pb-md-5">{{ $t('landing.about_us') }}</h2>
            <div v-html="$t('landing.abous_us_content')"/>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact us -->
    <div class="text-center bg-primary sign-up-block-2 text-white p-3 p-md-5">
      <h2 class="pb-4">{{ $t('landing.contact_us') }}</h2>
      <div v-html="$t('landing.contact_us_content')"/>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'landing',

  head () {
    return {
      title: this.$t('landing.about_us')
    }
  }
}
</script>

<style scoped>
  .rabbit-background {
    background: url(/about-us-rabbit.svg) no-repeat center center;
    background-size: contain;
  }
</style>
