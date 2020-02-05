<template>
  <button :title="title" class="btn">
    <fa :icon="icon" :class="{ 'text-primary': selected, 'text-muted': !selected, heartbeat }"/>
    <slot />
  </button>
</template>

<script>
export default {
  name: 'ButtonCounter',

  props: {
    icon: String,
    title: String,
    selected: Boolean
  },

  computed: {
    heartbeat () {
      return this.selected && this.icon === 'heart'
    }
  }
}
</script>

<style scoped>
.btn {
  box-shadow: none !important;
  padding: 0;
}
.heartbeat {
  animation: heartbeat 1.2s ease-in-out infinite;
}
@keyframes heartbeat {
  0% {
      -webkit-transform: scale(1);
      transform: scale(1)
  }

  14% {
      -webkit-transform: scale(1.3);
      transform: scale(1.3)
  }

  28% {
      -webkit-transform: scale(1);
      transform: scale(1)
  }

  42% {
      -webkit-transform: scale(1.3);
      transform: scale(1.3)
  }

  70% {
      -webkit-transform: scale(1);
      transform: scale(1)
  }
}
</style>
