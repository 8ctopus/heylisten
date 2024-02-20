import Vue from 'vue'
import amplitude from 'amplitude-js'

function amplitudeAdd(key, increment = 1) {
  const identify = new amplitude.Identify().add(key, increment)
  amplitude.getInstance().identify(identify);
}

export default async ({ app, store }) => {
  // Amplitude API KEY not set
  if (!process.env.AmplitudeKey) {
    return
  }

  amplitude.getInstance().init(process.env.AmplitudeKey)
  Vue.prototype.$amplitude = amplitude

  app.amplitude = amplitude

  // Setting referer
  const referer = store.getters['auth/referer']
  if (referer) {
    amplitude.setUserProperties({ referer })
  }

  // Setting locale
  const locale = store.getters['lang/locale']
  if (locale) {
    amplitude.setUserProperties({ locale })
  }

  // Subscribe to store changes
  store.subscribe(async (mutation, state) => {
    // Locale changed
    if (mutation.type === 'lang/SET_LOCALE') {
      amplitude.setUserProperties({ locale })
    }

    // When auth/reset/registration form completed successfully
    if (['auth/FETCH_USER_SUCCESS', 'auth/UPDATE_USER', 'auth/LOGOUT'].includes(mutation.type)) {
      const { email = null } = store.getters['auth/user'] || {}
      amplitude.setUserId(email)
    }

    // Registration success
    if (mutation.type === 'auth/REGISTRATION_SUCCESS') {
      amplitude.logEvent('Registration successful')
    }

    // Login success
    if (mutation.type === 'auth/LOGIN_SUCCESS') {
      amplitude.logEvent('Login successful')
    }

    // Product created
    if (mutation.type === 'products/createSuccess') {
      const { data: { name, slug } } = mutation.payload
      amplitude.logEvent('Project created', { name, slug })
      // counter
      amplitudeAdd('project-count')
    }

    // Product deleted
    if (mutation.type === 'products/destroySuccess') {
      amplitude.logEvent('Project deleted')
    }

    // Idea created
    if (mutation.type === 'ideas/createSuccess') {
      // data: { title, slug, id, product: { slug, name, workspace: { alias, id } }
      const { data: { title, slug } } = mutation.payload

      amplitude.logEvent('Idea created', { title, slug })
    }

    // Comment created
    if (mutation.type === 'comments/createSuccess') {
      amplitude.logEvent('Comment created', )
    }
  })

  // Check if user logged in, it runs
  // once when site loaded to client
  if (store.getters['auth/check']) {
    const { email } = store.getters['auth/user']
    amplitude.setUserId(email)
  }

  /* Every time the route changes (fired on initialization too) */
  app.router.afterEach((to, from) => {
    // page not changed (#hash)
    if (to.name === from.name) {
      return
    }

    const { name, path, params, hash } = to

    amplitude.logEvent('Page visited', { name, path, params, hash })
  })
}
