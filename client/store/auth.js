import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({
  user: null,
  token: null,
  referer: null,
  isMobile: null,
})

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  notifications: state => state.user ? state.user.notifications : null,
  workspace: state => state.user ? state.user.workspace.alias : null,
  isAdminOf: state => alias => state.user ? state.user.workspace.alias === alias : null,
  notificationById: state => id => state.user ? state.user.notifications.find(notification => notification.id === id) : null,
  notificationByType: state => type => state.user ? state.user.notifications.find(notification => notification.type.toLowerCase() === type.toLowerCase()) : null,
  referer: state => state.referer,
  isMobile: state => state.isMobile
}

// mutations
export const mutations = {
  SET_TOKEN (state, token) {
    state.token = token
  },

  FETCH_USER_SUCCESS (state, user) {
    state.user = user
  },

  FETCH_USER_FAILURE (state) {
    state.token = null
  },

  REGISTRATION_SUCCESS(state, { user }) {
    state.user = user
  },

  LOGIN_SUCCESS(state) {

  },

  LOGOUT (state) {
    state.user = null
    state.token = null
  },

  UPDATE_USER (state, { user }) {
    state.user = user
  },

  REMOVE_NOTIFICATION(state, id) {
    state.user.notifications.splice(state.user.notifications.findIndex(notification => notification.id === id), 1)
  },

  SET_REFERER(state, referer) {
    state.referer = referer
  },

  SET_DEVICE(state, mobile) {
    state.isMobile = mobile
  }
}

// actions
export const actions = {
  saveToken ({ commit }, { token, remember }) {
    commit('SET_TOKEN', token)

    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/user')

      commit('FETCH_USER_SUCCESS', data)
      return true
    } catch (e) {
      Cookies.remove('token')

      commit('FETCH_USER_FAILURE')
      return false
    }
  },

  updateUser ({ commit }, payload) {
    commit('UPDATE_USER', payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/logout')
    } catch (e) { }

    Cookies.remove('token')

    commit('LOGOUT')
  },

  async fetchOauthUrl (ctx, { provider }) {
    const { data } = await axios.post(`/oauth/${provider}`)

    return data.url
  },

  async readNotification ({ commit }, { id }) {
    try {
      await axios.patch(`/notifications/${id}`)

      commit('REMOVE_NOTIFICATION', id)
    } catch(e) { }
  },

  async login({ commit, dispatch }, { form, remember }) {
    try {
      const { data: { token } } = await form.post('/login')

      // Save the token.
      dispatch('saveToken', { token, remember })

      // Fetch the user.
      await dispatch('fetchUser')

      commit('LOGIN_SUCCESS')

      return true
    }
    catch(e) {
      return false
    }
  },

  async register ({ commit, dispatch }, { form }) {
    try {
      const { data: user } = await form.post('/register')

      // Log in the user.
      const { data: { token } } = await form.post('/login')

      dispatch('saveToken', { token })
      commit('REGISTRATION_SUCCESS', { user })

      return user
    }
    catch(e) {
      return null
    }
  }
}
