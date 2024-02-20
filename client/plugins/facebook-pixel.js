export default async ({ app: { $fb, router }, store }) => {
  // Facebook Pixel module not installed
  if (!$fb) {
    return
  }

  // Pixel Id not set
  if ($fb.options.pixelId === 'not-set') {
    return
  }

  // Enable pixel
  $fb.enable()

  // Subscribe to page changes
  router.afterEach((to, from) => {
    // page not changed (#hash)
    if (to.name === from.name) {

      // See pricing
      if (to.name === 'welcome' && to.hash === '#plans') {
        $fb.track('Lead')
      }
      return
    }

    // Landing on welcome page
    if (to.name === 'welcome' && from.name === null) {
      $fb.track('ViewContent')
    }

    // See demo
    if (to.params.product === 'heylisten' && from.name === 'welcome') {
      $fb.track('StartTrial', { value: '0.00', currency: 'USD', predicted_ltv: '0.00' })
    }

    // Choose paid plan
    if (to.name === 'register' && to.params._position === 'plan-hare' || to.params._position === 'plan-rabbit') {
      $fb.track('Subscribe', { value: '0.00', currency: 'USD', predicted_ltv: '0.00' })
    }
  })

  // Subscribe to store changes
  store.subscribe(async (mutation, state) => {
    // Registration success
    if (mutation.type === 'auth/REGISTRATION_SUCCESS') {
      $fb.track('CompleteRegistration')
    }

    // Projuct created
    if (mutation.type === 'products/createSuccess') {
      $fb.track('CustomizeProduct')
    }
  })
}
