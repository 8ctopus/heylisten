<template>
  <div
    :style="{ color }"
    :class="{ admin: isAdmin }"
    class="btn-group"
    role="group"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false"
  >
    <span v-if="category || isAdmin" class="btn btn-sm category-badge float-right ml-1">
      <template v-if="category">
        <fa :icon="category.icon" />
        {{ category.title }}
      </template>
      <template v-else-if="isAdmin">{{ $t('select_category') }}</template>
    </span>
    <template v-if="isAdmin">
      <span class="btn btn-sm category-select">
        <fa icon="chevron-down"/>
      </span>
      <div class="dropdown-menu dropdown-menu-right">
        <a v-for="cat in categoryList"
           v-if="!category || category.id !== cat.id"
           :key="cat.id"
           class="dropdown-item"
           href="#"
           @click.prevent="setCategory({idea_id, category_id: cat.id})"
        >
          <fa :icon="cat.icon" :style="{ color: cat.color }" class="fa-fw" /> {{ cat.title }}
        </a>
        <template v-if="category">
          <div role="separator" class="dropdown-divider"/>
          <a class="dropdown-item" href="#" @click.prevent="setCategory({idea_id, category_id: null})">
            <fa icon="ban" class="text-danger fa-fw" /> {{ $t('remove_category') }}
          </a>
        </template>
      </div>
    </template>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'CategoryBadge',

  props: {
    category_id: {
      type: Number
    },
    idea_id: {
      type: Number,
      default: null
    }
  },

  computed: {
    color () {
      return this.category ? this.category.color : null
    },

    ...mapGetters({
      authenticated: 'auth/check',
      isAdminOf: 'auth/isAdminOf',
      locale: 'lang/locale',
      categoryList: 'categories/list',
      categoryById: 'categories/byId'
    }),

    isAdmin () {
      return this.isAdminOf(this.$route.params.workspace)
    },

    category () {
      return this.category_id ? this.categoryById(this.category_id) : null
    }
  },

  watch: {
    async locale () {
      // Other component already trigger this action
      if (this.$store.getters['categories/isLoading']) {
        return
      }
      // Load translated categories
      await this.$store.dispatch('categories/fetchList')
    }
  },

  methods: {
    ...mapActions('ideas', ['setCategory'])
  }
}
</script>

<style scoped>
  .category-badge {
    border: 1px solid;
    cursor: inherit !important;
  }
  .category-select {
    border: 1px solid;
  }
  .btn {
    color: inherit;
  }

  /* Fix dropdown in iOS */
  .btn-group.admin {
    cursor: pointer;
  }
</style>
