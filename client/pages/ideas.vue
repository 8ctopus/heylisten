<template>
  <div>
    <!-- Headings -->
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
      <div class="col-md-10 mx-auto">
        <h1 class="display-4 font-weight-normal">{{ $t('suggest_a_feature_for', { product: currentProduct.name }) }}</h1>
        <p class="lead font-weight-normal">{{ $t('suggest_description') }}</p>
        <button v-if="!show_post_form" class="btn btn-secondary" @click="show_post_form=!show_post_form">
          {{ $t('suggest_idea_button') }}
        </button>
      </div>
    </div>

    <!-- Post new idea -->
    <transition name="form-popover">
      <create-idea v-if="show_post_form" :current-product="currentProduct" @create="onCreateIdea"/>
    </transition>

    <!-- List of ideas -->
    <template v-if="ideaList.length">
      <h2 class="text-center">{{ $t('list_of_ideas') }}</h2>

      <!-- Order buttons: New, Top, Hot -->
      <div class="btn-group btn-group-toggle mb-3" data-toggle="buttons">
        <nuxt-link :to="{ name: 'ideas.sort', params: {order: 'top'}}" :class="{active: order === 'top'}" class="btn btn-outline-primary" active-class="active">{{ $t('top') }}</nuxt-link>
        <nuxt-link :to="{ name: 'ideas.sort', params: {order: 'new'}}" :class="{active: order === 'new'}" class="btn btn-outline-secondary" active-class="active">{{ $t('new') }}</nuxt-link>
        <!--<nuxt-link :to="{ name: 'ideas.sort', params: {order: 'hot'}}" class="btn btn-outline-warning" active-class="active">{{ $t('hot') }}</nuxt-link>-->
      </div>

      <!-- Idea card -->
      <idea-card v-for="idea in ideaList" :idea="idea" :key="idea.id" @remove="onRemoveIdea"/>

      <!-- Paginaton -->
      <pagination :data="ideasState" :params="{ order }" route="ideas.sort.page"/>
    </template>
    <h2 v-else class="text-center">{{ $t('no_ideas_yet') }}</h2>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import CreateIdea from '~/components/CreateIdea'
import IdeaCard from '~/components/IdeaCard'
import Pagination from '~/components/Pagination'

export default {
  components: {
    IdeaCard,
    Pagination,
    CreateIdea
  },

  middleware: 'product',

  head () {
    const { workspace, product } = this.$route.params
    const { order, page } = this

    const { href } = this.$router.resolve({ name: 'ideas.sort.page', params: { workspace, product, order, page } })
    const canonical = `${process.env.canonicalUrl}${href}`

    return {
      title: `${this.$t(this.order)} ${this.$t('title_ideas')}`,
      link: [
        { hid: 'canonical', rel: 'canonical', href: canonical }
      ]
    }
  },

  async fetch ({ params, error, store }) {
    const { page, workspace, product, order = 'top' } = params

    store.commit('ideas/SET_ORDER', order)
    await store.dispatch('ideas/fetchList', { customUrlFnArgs: { order, page, workspace, product } })
    await store.dispatch('categories/fetchList')
  },

  data: () => ({
    show_post_form: false
  }),

  computed: {
    ...mapGetters('ideas', {
      page: 'currentPage',
      order: 'order',
      ideaList: 'list',
      ideaIsVoted: 'voted'
    }),

    ...mapGetters('products', {
      productById: 'byId'
    }),

    currentProduct () {
      return this.productById(this.$route.params.product)
    },

    ...mapState('ideas', {
      ideasState: state => state
    })
  },

  created () {
    // Subscribe to ideas/destroySuccess and refresh ideas list on delete idea
    this.$store.subscribe((mutation, state) => {
      if (mutation.type === 'ideas/destroySuccess') {
        this.onRemoveIdea()
      }
    })
  },

  methods: {
    ...mapActions('ideas', {
      fetchIdeas: 'fetchList'
    }),

    onCreateIdea ({ id, slug }) {
      this.$router.push({ name: 'idea', params: { idea: id + '-' + slug } })
    },

    async onRemoveIdea () {
      const { order, page } = this
      const product = this.currentProduct.slug
      const workspace = this.currentProduct.workspace.alias

      await this.fetchIdeas({ customUrlFnArgs: { order, page, workspace, product } })
    }
  }
}
</script>

<style scoped>
  .form-popover-enter,
  .form-popover-leave-to {
    opacity: 0;
    transform: rotateY(50deg) rotateX(120deg);
  }
  .form-popover-enter-to,
  .form-popover-leave {
    opacity: 1;
    transform: rotateY(0deg) rotateX(0deg);
  }
  .form-popover-enter-active,
  .form-popover-leave-active {
    transition: opacity, transform 400ms ease-out;
  }
</style>
