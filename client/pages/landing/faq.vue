<template>
  <div>
    <!-- #region Header -->
    <header class="bg-primary text-white">
      <div class="p-3 p-md-5 text-center">
        <h1 class="font-weight-bold pb-4">{{ $t('landing.frequently_asked_questions') }}</h1>
        <span>{{ $t('landing.frequently_asked_questions_description') }}</span>
      </div>
    </header>
    <!-- #endregion -->

    <!-- #region FAQ -->
    <div class="p-2 p-md-5 pl-lg-0 m-0 text-justify row">
      <div class="col-12 col-lg chapters-col">
        <div v-scroll="chaptersScroll" id="v-pills-tab" ref="chapters" class="chapters nav flex-column nav-pills text-left" role="tablist" aria-orientation="vertical">
          <div v-for="chapter in $t('landing.faq_chapters')" :key="chapter.anchor"
          >
            <a
              :href="`#${chapter.anchor}`"
              :id="`${chapter.anchor}-tab`"
              :class="{active: chapter.anchor === anchor}"
              class="nav-link"
            >{{ chapter.title }}</a>
            <span class="description">{{ chapter.description }}</span>
          </div>
        </div>
      </div>
      <div ref="content" class="col-12 col-lg-9 mb-lg-4">
        <div v-scroll="handleScroll" v-for="chapter in $t('landing.faq_chapters')" :id="chapter.anchor" :key="chapter.anchor" class="mt-4">
          <h2 class="text-center">{{ chapter.title }}</h2>
          <div v-html="chapter.content"/>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Any questions? -->
    <div class="text-center bg-primary text-white questions px-3 px-md-5">
      <h2 class="mb-5">{{ $t('landing.any_further_questions') }}</h2>
      <p class="text mb-2" v-html="$t('landing.any_further_questions_contact_us')"/>
    </div>
    <!-- #endregion -->

  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'landing',

  head () {
    return {
      title: this.$t('landing.frequently_asked_questions')
    }
  },

  data: () => ({
    anchor: '',
    chaptersTop: null
  }),

  computed: {
    ...mapGetters({ mobile: 'auth/isMobile' })
  },

  watch: {
    '$route.hash' (value) {
      this.setAnchor(value)
    }
  },

  created () {
    this.anchor = this.setAnchor(this.$route.hash)
  },

  mounted () {
    let { top } = this.$refs.chapters.getBoundingClientRect()
    top += window.pageYOffset
    this.chaptersTop = top

    // bind OnResize
    window.addEventListener('resize', this.chaptersScroll)
    this.chaptersScroll()
    this.setAnchor(this.$route.hash)
  },

  beforeDestroy () {
    window.removeEventListener('resize', this.chaptersScroll)
  },

  methods: {
    setAnchor (value) {
      this.anchor = value.slice(1)
    },

    handleScroll (evt, el) {
      if (this.mobile) return

      const { top, bottom } = el.getBoundingClientRect()
      if (top < 50 && bottom > 30 && this.anchor !== el.id) {
        history.replaceState(null, null, `#${el.id}`)
        this.anchor = el.id
      }
    },

    chaptersScroll (evt, el) {
      if (window.innerWidth < 992 || this.mobile) {
        this.$refs.chapters.style.position = ''
        this.$refs.chapters.style.top = ''
        this.$refs.chapters.style.width = ''
        return
      }

      // set width of chapters = parent width - bootstrap paddings (15 + 15)
      this.$refs.chapters.style.width = (this.$refs.chapters.parentNode.offsetWidth - 30) + 'px'

      const { top } = this.$refs.chapters.getBoundingClientRect()
      const contentBottom = this.$refs.content.offsetTop + this.$refs.content.offsetHeight
      const chaptersHeight = this.$refs.chapters.offsetHeight
      const margin = 30

      if (window.pageYOffset + margin < this.chaptersTop) {
        this.$refs.chapters.style.position = ''
        this.$refs.chapters.style.top = ''
      } else if (window.pageYOffset > contentBottom - chaptersHeight) {
        this.$refs.chapters.style.position = 'absolute'
        this.$refs.chapters.style.top = `${contentBottom - chaptersHeight - 260}px`
      } else if (top < margin || this.$refs.chapters.style.position === 'absolute') {
        this.$refs.chapters.style.position = 'fixed'
        this.$refs.chapters.style.top = `${margin}px`
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@media (min-width: 992px) {
  .chapters-col {
    border-right: 3px solid #d44250;
  }
  .chapters {
    position: absolute;
  }
  .description {
    display: none;
  }
}
@media (max-width: 991px) {
  .nav-pills .nav-link.active {
    color: #d44250;
    background-color: transparent;
  }
  .nav-pills .nav-link {
    font-size: 1.8rem;
    padding: 0.5rem 0 0 0;
    text-align: left;
  }
}

.questions {
  padding-top: 9rem;
  padding-bottom: 4rem;
  margin: 8rem 0 0;
  clip-path: polygon(0 20%, 100% 0, 100% 100%, 0 100%);

  @media (max-width: 462px) {
    clip-path: polygon(0 0, 100% 5%, 100% 100%, 0 100%);
  }
}
.questions .text {
  font-size: 1.5rem;
}
</style>
