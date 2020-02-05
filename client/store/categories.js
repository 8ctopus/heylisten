import createCRUDModule from 'vuex-crud'

const crudModule = createCRUDModule({
  resource: 'categories',
  urlRoot: '/categories',
  idAttribute: 'id',

  parseError (res) {
    const { message, response } = res
    const { status, data } = response

    return { message, status, data }
  },
})

const state = () => crudModule.state

const { actions, mutations, getters } = crudModule

export {
  state,
  actions,
  mutations,
  getters
}
