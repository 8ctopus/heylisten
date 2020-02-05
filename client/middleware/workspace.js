export default ({ store, error, params }) => {
  const userWorkspace = store.getters['auth/workspace']
  const { workspace = null } = params
  if (userWorkspace !== workspace) {
    error({ statusCode: 404, message: `This page could not be found` })
  }
}
