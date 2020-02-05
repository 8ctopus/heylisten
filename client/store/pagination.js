import Vue from 'vue'

const idAttribute = 'id'

const pagination = {
  // State
  state() {
    return{
      // laravel-vue-pagination
      current_page: 1,
      from: 1,
      last_page: 1,
      next_page_url: null,
      per_page: 10,
      prev_page_url: null,
      to: 1,
      total: 0,
    }
  },

  // Getters
  getters: {
    currentPage: state => state.current_page,
  },

  // Mutations
  mutations: {
    fetchListSuccess(state, response) {
      const { data } = response
      // Pagination
      const { current_page, from, last_page, per_page, to, total } = data

      data.data.forEach((m) => {
        Vue.set(state.entities, m[idAttribute].toString(), m)
      })

      // Pagination
      state = Object.assign(state, { current_page, from, last_page, per_page, to, total })

      state.list = data.data.map(m => m[idAttribute].toString())
      state.isFetchingList = false
      state.fetchListError = null
      // onFetchListSuccess(state, response)
    },
  }
}

export default pagination
