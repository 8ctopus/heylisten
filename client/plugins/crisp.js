window.$crisp = [];
window.CRISP_WEBSITE_ID = 'f292fed9-7b79-4368-9464-7ec9210cfc7a';

let loaded = false

const loadCrisp = () => {
  let s = document.createElement('script');
  s.src = 'https://client.crisp.chat/l.js';
  s.async = 1;
  document.getElementsByTagName('head')[0].appendChild(s);
  loaded = true
}

export default ({ app: { router }, store }) => {
  // Only for EN
  if (store.getters['lang/locale'] !== 'en') return

  /* Every time the route changes (fired on initialization too) */
  router.afterEach((to, from) => {
    // Only for landing page if not loaded
    if (to.name === 'welcome' && !loaded) loadCrisp()
  })
}
