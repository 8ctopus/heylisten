<template>
  <div class="card">
    <div v-if="title" class="card-header">
      {{ title }}
    </div>

    <div class="card-body">
      <slot/>
    </div>

    <div v-if="hasFooter" class="card-footer">
      <slot name="footer"/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  },

  computed: {
    hasFooter () {
      return !!this.$slots['footer']
    }
  }
}
</script>
