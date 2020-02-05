export default ({ store, redirect }) => {
  if (store.getters['auth/check']) {
    const workspace = store.getters['auth/workspace']
    return redirect({ name: 'home', params: { workspace } })
  }
}
