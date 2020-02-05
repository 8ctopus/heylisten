export default async ({ store, redirect, params, error }) => {
  const { product, workspace } = params

  // fetchSingle
  try {
    const { data: { slug } } = await store.dispatch('products/fetchSingle', { id: product, customUrlFnArgs: { workspace } })

    if (slug !== product) {
      redirect({ name: 'ideas', params: { product: slug } })
    }
  } catch (e) {
    const { status } = e
    if (status === 404) {
      error({ statusCode: 404, message: 'This page could not be found' })
    }
  }
}
