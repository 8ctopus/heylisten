<template>
  <div class="player">
    <fa v-if="!play" icon="play" class="play fa-3x" @click="play = true"/>
    <div v-else-if="!loaded" class="loader">
      <fa icon="circle-notch" class="fa-spin fa-3x"/>
    </div>
    <img v-if="play" :src="image" :alt="alt" @load="onLoad">
    <img v-else :src="stub" :alt="alt" class="stub" @click="play = true">
  </div>
</template>

<script>
export default {
  props: ['image', 'stub', 'alt'],

  data: () => ({
    play: false,
    loaded: false
  }),

  methods: {
    onLoad () {
      setTimeout(() => {
        this.loaded = true
      }, 200)
    }
  }
}
</script>

<style lang="scss" scoped>
  .player {
    position: relative;
  }

  .player img {
    width: 100%;
  }

  .stub, .play {
    cursor: pointer;
  }

  .loader,
  .play {
    position: absolute;
    z-index: 10;
    color: rgba(0, 0, 0, 0.8);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
