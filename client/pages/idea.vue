<template>
  <div v-if="idea">
    <!-- Breadcrumb -->
    <nav>
      <div class="breadcrumb">
        <nuxt-link :to="back_route" class="breadcrumb-item">
          <button class="btn btn-outline-dark"><fa icon="chevron-left" /> {{ $t('back_to_ideas') }}</button>
        </nuxt-link>
      </div>
    </nav>

    <!-- Idea card -->
    <idea-card :idea="idea" :show-current="true"/>

    <h2>
      {{ $tc('comments_counter', idea.comments_count, { n: idea.comments_count }) }}
    </h2>

    <!-- Post comment from -->
    <div class="card border-light mb-3">
      <div class="card-body">
        <!-- Add new comment -->
        <form @submit.prevent="create" @keydown="form.onKeydown($event)">
          <div class="form-group comment-editor">
            <editor v-model="form.description" :placeholder="$t('add_comment')" :data-invalid="form.errors.has('description')"/>

            <has-error :form="form" field="description"/>

          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <v-button :loading="form.busy" type="secondary">
              <fa icon="plus" />
              {{ $t('submit') }}
            </v-button>
          </div>

        </form>
      </div>
    </div>

    <!-- List of comments -->
    <div v-for="comment in commentList" :key="comment.id" class="card mb-1">
      <div class="card-body">
        <!-- Button remove -->
        <button v-if="isAdmin" class="btn btn-danger btn-sm float-right ml-1" @click="remove(comment)">
          <fa icon="trash-alt"/>
        </button>
        <div v-if="comment.is_admin" class="mb-1">
          <admin-icon /><span class="ml-1" v-html="$t('admin_commented')"/>
        </div>
        <div :style="{ 'margin-left': comment.is_admin ? '2.25rem' : '' }" class="card-text clearfix" v-html="comment.description"/>
        <div class="card-text float-right"><small class="text-muted">{{ $t('posted_time') }} {{ comment.created_at |timeHuman }}</small></div>
      </div>
    </div>

    <!-- Paginaton -->
    <pagination :data="commentsState" route="idea.page"/>

  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import IdeaCard from '~/components/IdeaCard'
import Form from 'vform'
import Editor from '~/components/TextEditor'
import { toast, swal } from '~/plugins/alerts'
import Pagination from '~/components/Pagination'

export default {
  components: {
    Pagination,
    Editor,
    IdeaCard
  },

  middleware: 'product',

  head () {
    const { workspace, product } = this.$route.params
    const { page, ideaId: idea } = this

    const { href } = this.$router.resolve({ name: 'idea.page', params: { workspace, product, idea, page } })
    const canonical = `${process.env.canonicalUrl}${href}`

    const title = this.idea ? this.idea.title : ''

    return {
      title,
      link: [
        { hid: 'canonical', rel: 'canonical', href: canonical }
      ]
    }
  },

  async fetch ({ params, redirect, store, error }) {
    const id = parseInt(params.idea)
    const page = params.page

    try {
      await store.dispatch('ideas/fetchSingle', { id })
    } catch (e) {
      const { status } = e
      if (status === 404) {
        error({ statusCode: 404, message: `This page could not be found` })
      }
      return
    }
    await store.dispatch('comments/fetchList', { customUrlFnArgs: { ideaId: id, page } })
    await store.dispatch('categories/fetchList')
  },

  data: () => ({
    form: new Form({
      description: ''
    })
  }),

  computed: {
    ideaId () {
      return parseInt(this.$route.params.idea)
    },

    ...mapGetters('ideas', {
      ideaById: 'byId',
      ideaIsVoted: 'voted'
    }),

    ...mapGetters('comments', {
      page: 'currentPage',
      commentList: 'list'
    }),

    ...mapGetters({
      authenticated: 'auth/check',
      isAdminOf: 'auth/isAdminOf'
    }),

    ...mapState('comments', {
      commentsState: state => state
    }),

    isAdmin () {
      return this.isAdminOf(this.$route.params.workspace)
    },

    idea () {
      return this.ideaById(this.ideaId)
    },

    back_route () {
      if (this.$store.state.route && this.$store.state.route.from.path !== '/') {
        const { name, params } = this.$store.state.route.from
        return { name, params }
      }

      return { name: 'ideas' }
    }
  },

  methods: {
    ...mapActions({
      fetchComments: 'comments/fetchList',
      createComment: 'comments/create',
      removeComment: 'comments/destroy',
      fetchIdea: 'ideas/fetchSingle'
    }),

    // Create new comment
    async create () {
      const data = this.form.data()
      const ideaId = this.ideaId

      try {
        this.form.startProcessing()
        await this.createComment({ data, customUrlFnArgs: { ideaId } })

        toast({ title: this.$t('your_comment_has_been_posted') })

        this.form.reset()
        await this.fetchComments({ customUrlFnArgs: { ideaId, page: this.page } })
        await this.fetchIdea({ id: ideaId })
      } catch (e) {
        console.log(e)
        this.form.errors.set(this.form.extractErrors(e))
      } finally {
        this.form.finishProcessing()
      }
    },

    async remove ({ id }) {
      // Cancel
      if (!await swal({
        title: this.$t('delete_popup.title'),
        text: this.$t('delete_popup.text'),
        confirmButtonText: this.$t('ok'),
        cancelButtonText: this.$t('cancel')
      })) return

      await this.removeComment({ id, customUrlFnArgs: { ideaId: this.idea.id } })

      toast({ title: this.$t('comment_deleted') })

      await this.fetchComments({ customUrlFnArgs: { ideaId: this.idea.id, page: this.page } })
      await this.fetchIdea({ id: this.ideaId })
    }
  }
}
</script>

<style scoped>
.comment-editor >>> .ql-image {
  display: none !important;
}
</style>
