export default ({ app: { router } }) => {
  if (process.env.NODE_ENV !== 'production') return

  // Yandex ID not set
  if (!process.env.yandex.id) return

  (function(d, w, c) {
    (w[c] = w[c] || []).push(function() {
      try {
        w[`yaCounter${ process.env.yandex.id }`] = new Ya.Metrika2({
          id: process.env.yandex.id,
          clickmap: process.env.yandex.clickmap,
          trackLinks: process.env.yandex.trackLinks,
          accurateTrackBounce: process.env.yandex.accurateTrackBounce,
          webvisor: process.env.yandex.webvisor,
        })
      } catch (e) {}
    })

    let n = d.getElementsByTagName('script')[0],
      s = d.createElement('script'),
      f = function() {
        n.parentNode.insertBefore(s, n);
      };
    s.type = 'text/javascript';
    s.async = true;
    s.src = 'https://mc.yandex.ru/metrika/tag.js'

    if (w.opera == '[object Opera]') {
      d.addEventListener('DOMContentLoaded', f, false)
    } else {
      f()
    }
  })(document, window, 'yandex_metrika_callbacks2')

  /* Every time the route changes (fired on initialization too) */
  router.afterEach((to, from) => {
    window[`yaCounter${ process.env.yandex.id }`] &&
    window[`yaCounter${ process.env.yandex.id }`].hit &&
    window[`yaCounter${ process.env.yandex.id }`].hit(to.fullPath, {
      referer: from.fullPath,
    })
  })
}
