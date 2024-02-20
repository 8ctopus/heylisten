<template>
  <div>
    <!-- Header -->
    <header class="bg-primary text-white">
      <div class="py-3 py-md-5 px-3 px-md-5 text-center">
        <h1 class="font-weight-bold pb-4">{{ $t('landing.privacy_policy') }}</h1>
      </div>
    </header>

    <div class="p-3 p-md-5 text-justify chapters-content position-relative">
      <template v-for="chapter in $t('landing.privacy_chapters')">
        <h4 class="text-center text-primary">{{ chapter.title }}</h4>
        <p v-for="paragraph in chapter.paragraphs">{{ paragraph }}</p>
      </template>
    </div>

  </div>
</template>

<script>
export default {
  layout: 'landing',

  head () {
    return {
      title: this.$t('landing.privacy_policy')
    }
  }
}
</script>

<style scoped>
  h4 {
    counter-increment: chapter-counter;
  }

  h4::before {
    content: counter(chapter-counter) ". ";
  }

</style>
