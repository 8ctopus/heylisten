<template>
  <div v-if="image" class="image-attachment mb-2">
    <div class="image">
      <a :href="image" target="_blank"><img :src="image" :alt="$t('attached_image')"></a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageAttachment',

  props: {
    image: {
      type: String,
      default: null
    }
  },

  data: () => ({
    collapsed: true
  })
}
</script>

<style scoped>
.image {
    border: 1px solid #ced4da;
    border-radius: 3px;
    overflow: hidden;
    display: inline-block;
    position: relative;
    max-width: 200px;
    max-height: 200px;
}
.image-attachment img {
  max-width: 100%;
  max-height: 100%;
}
</style>
