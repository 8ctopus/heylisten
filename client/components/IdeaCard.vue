<template>
  <div v-if="idea"
       :class="{ 'card-pinned': idea.pinned }"
       :style="{ 'border-left-color': idea.pinned && category ? category.color : ''}"
       class="card mb-3">
    <div class="card-body">

      <!-- Toolbar -->
      <div class="toolbar">
        <div class="float-right text-right">
          <!-- Category -->
          <category-badge :category_id="idea.category_id" :idea_id="idea.id" class="pb-2"/>

          <!-- Button remove -->
          <button v-if="isAdmin" class="btn btn-danger btn-sm float-right ml-1" @click="remove">
            <fa icon="trash-alt"/>
          </button>
        </div>
      </div>

      <div class="clearfix">
        <!-- Title -->
        <div class="float-left">
          <nuxt-link v-if="!showCurrent" :to="{ name: 'idea', params: {idea: idea.id + '-' + idea.slug}}">
            <h5 class="card-title">{{ idea.title }}</h5>
          </nuxt-link>
          <h5 v-else class="card-title">{{ idea.title }}</h5>
        </div>
      </div>

      <!-- Description -->
      <div class="card-text clearfix mb-3" v-html="idea.description"/>
      <image-attachment :image="idea.image"/>

      <!-- Button vote -->
      <button-counter
        :title="$tc('likes_counter', idea.votes, {n: idea.votes})"
        :selected="ideaIsVoted(idea.id)"
        icon="heart"
        class="mr-3"
        @click.native="makeVote(idea)"
      >
        {{ idea.votes ? idea.votes : '' }}
      </button-counter>

      <!-- Button comment -->
      <nuxt-link v-if="!showCurrent" :to="{ name: 'idea', params: {idea: idea.id + '-' + idea.slug}}" :title="$tc('comments_counter', idea.comments_count, {n: idea.comments_count})">
        <button-counter
          icon="comment"
        >
          {{ idea.comments_count ? idea.comments_count : '' }}
        </button-counter>
      </nuxt-link>
      <button-counter v-else
                      :title="$tc('comments_counter', idea.comments_count, {n: idea.comments_count})"
                      icon="comment"
      >
        {{ idea.comments_count ? idea.comments_count : '' }}
      </button-counter>

      <!-- Time created -->
      <div class="card-text float-right"><small class="text-muted">{{ $t('created_time') }} {{ idea.created_at |timeHuman }}</small></div>
      <!--</div>-->
    </div>

    <!-- Admin comment -->
    <div v-if="!showCurrent && idea.comments && idea.comments.length" class="card-footer comment-list">
      <!-- Display only one comment -->
      <div
        v-for="(comment, index) in idea.comments"
        v-if="index === 0"
        :key="comment.id"
        class="comment"
      >
        <div v-if="comment.is_admin" class="mb-1">
          <admin-icon /><span class="ml-1" v-html="$t('admin_commented')"/>
        </div>
        <div class="card-text clearfix" style="margin-left: 2.25rem;" v-html="comment.description"/>
      <div class="card-text float-right"><small class="text-muted">{{ $t('posted_time') }} {{ comment.created_at |timeHuman }}</small></div></div>

    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { toast, swal } from '~/plugins/alerts'

export default {
  name: 'IdeaCard',

  props: {
    idea: {
      type: Object,
      default: null
    },
    showCurrent: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ...mapGetters({
      ideaIsVoted: 'ideas/voted',
      categoryById: 'categories/byId',
      authenticated: 'auth/check',
      isAdminOf: 'auth/isAdminOf'
    }),

    category () {
      return this.idea.category_id ? this.categoryById(this.idea.category_id) : null
    },

    isAdmin () {
      return this.isAdminOf(this.$route.params.workspace)
    }
  },

  methods: {
    ...mapActions('ideas', {
      removeIdea: 'destroy',
      voteIdea: 'vote',
      unvoteIdea: 'unvote'
    }),

    makeVote (idea) {
      this.ideaIsVoted(idea.id) ? this.unvoteIdea(idea) : this.voteIdea(idea)
    },

    async remove () {
      // Cancel
      if (!await swal({
        title: this.$t('delete_popup.title'),
        text: this.$t('delete_popup.text'),
        confirmButtonText: this.$t('ok'),
        cancelButtonText: this.$t('cancel')
      })) return

      const title = this.$t('idea_deleted').toString()
      await this.removeIdea({ id: this.idea.id })

      toast({ title })

      // In idea, history back
      if (this.showCurrent) {
        this.$router.go(-1)
      }
    }
  }
}
</script>

<style scoped>
  .comment:not(:last-of-type) {
    border-bottom: 1px solid #e1e1e1;
    margin-bottom: 0.5rem;
    padding-bottom: 0.25rem;
  }

  .toolbar {
    float: right;
    margin: -1rem -1rem 0 1rem;
  }
  .card-title {
    display: inline;
  }
  .card-pinned {
    border-left: 3px solid;
  }
</style>
