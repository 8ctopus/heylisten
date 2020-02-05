import createCRUDModule from 'vuex-crud'

const crudModule = createCRUDModule({
  resource: 'products',
  urlRoot: '/products',
  idAttribute: 'slug',

  parseError (res) {
    const { message, response } = res
    const { status, data } = response

    return { message, status, data }
  },

  customUrlFn(id, {}, { workspace = null } = {}) {
    // id will only be available when doing request to single resource, otherwise null
    return id ? `/products/${ id }?workspace=${ workspace }` : `/products?workspace=${ workspace }`
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
