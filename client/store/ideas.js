import createCRUDModule from 'vuex-crud'
import Cookies from 'js-cookie'
import pagination from '~/store/pagination'

const idAttribute = 'id'

const crudModule = createCRUDModule({
  resource: 'ideas',
  idAttribute: idAttribute,

  parseError (res) {
    const { message, response = {} } = res
    const { status = null, data = null } = response

    return { message, status, data }
  },

  // State
  state: Object.assign(pagination.state, {
    // Order: new, top, hot, etc
    order: '',
    // Cookie voted ideas
    votes: [],
  }),

  // Getters
  getters: Object.assign(pagination.getters, {
    voted: state => id => state.votes.includes(id),
    order: state => state.order
  }),

  // Mutations
  mutations: Object.assign(pagination.mutations, {
    ADD_VOTE: (state, id) => {
      if (!state.votes.includes(id)) state.votes.push(id)
      Cookies.set('votes', state.votes, { expires: 365 })
    },

    REMOVE_VOTE: (state, id) => {
      state.votes = state.votes.filter(e => e !== id)
      Cookies.set('votes', state.votes, { expires: 365 })
    },

    SET_ORDER: (state, order) => {
      state.order = order
    },

    LOAD_VOTES: (state, votes) => {
      try {
        state.votes = JSON.parse(votes)
      }
      catch(e) {
        state.votes = []
      }
    }
  }),

  // Actions
  actions: {
    vote: async ({ commit, dispatch }, { id } ) => {
      await dispatch('update', { id: `${id}/vote`, data: { vote: 'add' } })
      commit('ADD_VOTE', id)
    },

    unvote: async ({ commit, dispatch }, { id } ) => {
      await dispatch('update', { id: `${ id }/vote`, data: { vote: 'remove' } })
      commit('REMOVE_VOTE', id)
    },

    setCategory: async ({ commit, dispatch }, { idea_id, category_id } ) => {
      await dispatch('update', { id: idea_id, data: { category_id }})
    },
  },

  customUrlFn(id, {}, { workspace = null, product = null, order = null, page = null } = {}) {
    // id will only be available when doing request to single resource, otherwise null
    return id ? `/ideas/${ id }` : `/ideas?workspace=${ workspace }&product=${ product }&order=${ order }&page=${ page }`
  }
})

const state = () => crudModule.state

const { actions, mutations, getters } = crudModule

export {
  state,
  actions,
  mutations,
  getters
}
