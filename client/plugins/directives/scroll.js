import Vue from 'vue'

Vue.directive('scroll', {
  inserted (el, binding) {
    el.handler = function (evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', el.handler)
      }
    }
    window.addEventListener('scroll', el.handler)
  },
  unbind (el) {
    window.removeEventListener('scroll', el.handler)
  }
})
