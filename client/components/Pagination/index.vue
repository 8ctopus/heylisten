<template>
  <renderless-laravel-vue-pagination :data="data" :limit="limit" @pagination-change-page="onPaginationChangePage">
    <ul v-if="data.total > data.per_page" slot-scope="{ data, limit, pageRange, prevButtonEvents, nextButtonEvents, pageButtonEvents }" class="pagination">
      <li v-if="data.prev_page_url" class="page-item pagination-prev-nav">
        <a class="page-link" href="#" aria-label="Previous" v-on="prevButtonEvents">
          <slot name="prev-nav">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </slot>
        </a>
      </li>
      <li v-for="(page, key) in pageRange" :key="key" :class="{ 'active': page == data.current_page }" class="page-item pagination-page-nav">
        <nuxt-link :to="to(page)" class="page-link">{{ page }}</nuxt-link>
      </li>
      <li v-if="data.next_page_url" class="page-item pagination-next-nav">
        <a class="page-link" href="#" aria-label="Next" v-on="nextButtonEvents">
          <slot name="next-nav">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </slot>
        </a>
      </li>
    </ul>
  </renderless-laravel-vue-pagination>
</template>

<script>
import RenderlessLaravelVuePagination from './RenderlessLaravelVuePagination.vue'

export default {
  components: {
    RenderlessLaravelVuePagination
  },

  props: {
    data: {
      type: Object,
      default: () => {
        return {
          current_page: 1,
          data: [],
          from: 1,
          last_page: 1,
          next_page_url: null,
          per_page: 10,
          prev_page_url: null,
          to: 1,
          total: 0
        }
      }
    },

    limit: {
      type: Number,
      default: 0
    },

    route: {
      type: String,
      default: ''
    },

    params: {
      type: Object,
      default: () => {}
    }
  },

  methods: {
    onPaginationChangePage (page) {
      this.$emit('pagination-change-page', page)
    },

    to (page) {
      const params = Object.assign({ page }, this.params)
      return { name: this.route, params }
    }
  }
}
</script>
