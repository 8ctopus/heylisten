import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'
import LanguageLocaleParser from 'accept-language-parser'

export const actions = {
  nuxtServerInit ({ commit, getters }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    // Get available locales
    const locales = Object.keys(getters['lang/locales'])
    // Get locale from cookie
    let locale = cookieFromRequest(req, 'locale')

    // Otherwise get locale from accept-language header
    if (!locale) {
      // loose: true - to detect de-* as de
      locale = LanguageLocaleParser.pick(locales, req.headers['accept-language'], { loose: true })
    }
    
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }

    // Load voted ideas from cookie
    commit('ideas/LOAD_VOTES', unescape(cookieFromRequest(req, 'votes')) )

    // Get referer
    const { referer = null} = req.headers
    commit('auth/SET_REFERER', referer)

    // Check device type
    const mobile = (/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/i.test(req.headers['user-agent']))
    commit('auth/SET_DEVICE', mobile)
  },

  nuxtClientInit ({ commit }) {
    const token = Cookies.get('token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = Cookies.get('locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }

    // Load voted ideas from cookie
    commit('ideas/LOAD_VOTES', Cookies.get('votes') )
  }
}
