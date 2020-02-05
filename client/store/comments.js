import createCRUDModule from 'vuex-crud'
import pagination from '~/store/pagination'
import Cookies from "js-cookie";

const crudModule = createCRUDModule({
  resource: 'comments',

  parseError (res) {
    const { message, response } = res
    const { status, data } = response

    return { message, status, data }
  },

  // State
  state: pagination.state,

  // Getters
  getters: Object.assign(pagination.getters, {
    total: state => state.total
  }),

  // Mutations
  mutations: pagination.mutations,

  customUrlFn(id, {}, { ideaId, page }) {
    // id will only be available when doing request to single resource, otherwise null
    const rootUrl = `/ideas/${ ideaId }/comments`
    return id ? `${ rootUrl }/${ id }` : `${ rootUrl }?page=${ page }`
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
