<template>
  <div>
    <!-- #region Header -->
    <header class="bg-primary text-white">
      <div class="p-3 px-md-5 text-center">
        <h1 class="font-weight-bold pb-4">{{ $t('landing.header_title') }}</h1>
        <div v-html="$t('landing.header_subtitle')"/>
      </div>

      <div class="image-wrapper">
        <image-player
          :image="$t('landing.gifs[0].src')"
          :stub="$t('landing.gifs[0].stub')"
          :alt="$t('landing.gifs[0].alt')"
        />
      </div>
    </header>
    <svg
      width="100%"
      height="50"
      viewBox="0 0 500 80"
      preserveAspectRatio="none"
      class="header-bottom"
    >
      <path d="M0,0 L0,40 Q250,80 500,40 L500,0 Z"/>
    </svg>
    <!-- #endregion -->

    <!-- #region Sign up -->
    <!--
    <div v-if="!authenticated" class="p-3 p-md-5 text-center">
      <router-link
        :to="{ name: 'register', params: { _position: 'first-screen' } }"
        class="btn btn-dark text-uppercase"
      >{{ $t('landing.sign_up_for_free') }}</router-link>
      <div>{{ $t('landing.and_use_it_for_free') }}</div>
    </div>
    -->
    <!-- #endregion -->

    <!-- #region User scenarios -->
    <div class="p-3 p-md-5">
      <h2 id="scenarios" class="text-center pb-2 p-md-4">{{ $t('landing.user_scenarios') }}</h2>
      <scenarios />
    </div>
    <!-- #endregion -->

    <!-- #region Why us? -->
    <div class="p-3 p-md-5">
      <h2 id="why-us" class="text-center pb-3 p-md-5">{{ $t('landing.why_us') }}</h2>
      <div class="row">
        <div class="col-md-4 order-md-1">
          <h4>{{ $t('landing.quick_setup') }}</h4>
          <div v-html="$t('landing.quick_setup_text')"/>
        </div>
        <div class="col-md-8 order-md-2 pb-5">
          <image-player
            :image="$t('landing.gifs[1].src')"
            :stub="$t('landing.gifs[1].stub')"
            :alt="$t('landing.gifs[1].alt')"
          />
        </div>

        <div class="col-md-4 order-md-4">
          <h4>{{ $t('landing.see_what_your_clients_want') }}</h4>
          <p>{{ $t('landing.see_what_your_clients_want_text') }}</p>
        </div>
        <div class="col-md-8 order-md-3 pb-5">
          <image-player
            :image="$t('landing.gifs[2].src')"
            :stub="$t('landing.gifs[2].stub')"
            :alt="$t('landing.gifs[2].alt')"
          />
        </div>

        <div class="col-md-4 order-md-5">
          <h4>{{ $t('landing.easy_moderation') }}</h4>
          <p>{{ $t('landing.easy_moderation_text') }}</p>
        </div>
        <div class="col-md-8 order-md-6 pb-5">
          <image-player
            :image="$t('landing.gifs[3].src')"
            :stub="$t('landing.gifs[3].stub')"
            :alt="$t('landing.gifs[3].alt')"
          />
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Why is it better? -->
    <div class="p-3 p-md-5 why-is-it">
      <h2 class="text-center pb-3 pb-md-5">{{ $t('landing.why_is_it_better') }}</h2>
      <div class="row text-center test">
        <div class="col-sm pb-4">
          <div class="mb-3">
            <img :alt="$t('landing.alt.its_free')" src="/its-free-icon.svg">
          </div>
          <b>{{ $t('landing.its_free') }}</b>
          <div>{{ $t('landing.its_free_text') }}</div>
        </div>
        <div class="col-sm pb-4">
          <div class="mb-3">
            <img :alt="$t('landing.alt.unlimited_walls')" src="/unlimited-walls-icon.svg">
          </div>
          <b>{{ $t('landing.unlimited_walls') }}</b>
          <div>{{ $t('landing.unlimited_walls_text') }}</div>
        </div>
        <div class="col-sm pb-4">
          <div class="mb-3">
            <img :alt="$t('landing.alt.five_languages')" src="/five-languages-support-icon.svg">
          </div>
          <b>{{ $t('landing.languages_support') }}</b>
          <div>{{ $t('landing.languages_support_text') }}</div>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Sign up -->
    <!--
    <div v-if="!authenticated" class="text-center bg-primary sign-up-block text-white px-3 px-md-5">
      <h2 class="pb-4">{{ $t('landing.sign_up_for_your_free_account') }}</h2>
      <p class="pb-3">{{ $t('landing.sign_up_for_your_free_account_text') }}</p>
      <router-link
        :to="{ name: 'register', params: { _position: 'features' } }"
        class="btn btn-secondary text-uppercase"
      >{{ $t('landing.sign_up_for_free') }}</router-link>
    </div>
    -->
    <!-- #endregion -->

    <!-- #region Features -->
    <div class="p-3 p-md-5">
      <h2 id="features" class="text-center pb-4">{{ $t('landing.features') }}</h2>
      <div class="row features-list">
        <div v-for="i in 12" class="col-md-4">
          <b>
            {{ $t(`landing.features_title${i}`) }}
            <fa icon="question-circle" class="text-primary"/>
          </b>
          <p>{{ $t(`landing.features_text${i}`) }}</p>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Plans -->
    <div class="p-3 px-md-1 p-lg-5 position-relative">
      <h2 id="plans" class="text-center pb-2 pb-md-4">{{ $t('landing.plans') }}</h2>
      <div class="position-absolute bg-primary plans-overlay d-none d-lg-block"/>
      <div class="row mb-3 pt-lg-5 text-center">
        <div class="col-12 col-md-4">
          <div class="card my-4 box-shadow">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">{{ $t('landing.plans_content[0].name') }}</h4>
            </div>
            <div class="card-body">
              <h3
                class="card-title pricing-card-title"
                v-html="$t('landing.plans_content[0].price')"
              />
              <ul class="list-unstyled mt-3 mb-4">
                <li
                  v-for="description in $t('landing.plans_content[0].descriptions')"
                >{{ description }}</li>
              </ul>
              <!--
              <template v-if="!authenticated">
                <router-link
                  :to="{ name: 'register', params: { _position: 'plan-bunny' } }"
                  class="btn btn-lg btn-block btn-outline-primary"
                >{{ $t('landing.sign_up_for_free') }}</router-link>
                <p v-html="$t('landing.plans_content[0].card')"/>
              </template>
              -->
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card box-shadow">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">{{ $t('landing.plans_content[1].name') }}</h4>
            </div>
            <div class="card-body">
              <h3
                class="card-title pricing-card-title"
                v-html="$t('landing.plans_content[1].price')"
              />
              <ul class="list-unstyled mt-3 mb-4">
                <li
                  v-for="description in $t('landing.plans_content[1].descriptions')"
                >{{ description }}</li>
              </ul>
              <!--
              <template v-if="!authenticated">
                <router-link
                  :to="{ name: 'register', params: { _position: 'plan-rabbit' } }"
                  class="btn btn-lg btn-block btn-primary"
                >{{ $t('landing.buy_plan_button') }}</router-link>
                <p v-html="$t('landing.plans_content[1].card')"/>
              </template>
              -->
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card my-4 box-shadow">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">{{ $t('landing.plans_content[2].name') }}</h4>
            </div>
            <div class="card-body">
              <h3
                class="card-title pricing-card-title"
                v-html="$t('landing.plans_content[2].price')"
              />
              <ul class="list-unstyled mt-3 mb-4">
                <li
                  v-for="description in $t('landing.plans_content[2].descriptions')"
                >{{ description }}</li>
              </ul>
              <!--
              <template v-if="!authenticated">
                <router-link
                  :to="{ name: 'register', params: { _position: 'plan-hare' } }"
                  class="btn btn-lg btn-block btn-primary"
                >{{ $t('landing.buy_plan_button') }}</router-link>
                <p v-html="$t('landing.plans_content[2].card')"/>
              </template>
              -->
            </div>
          </div>
        </div>
      </div>
      <p class="text-center mt-4" v-html="$t('landing.plan_need_more')"/>
    </div>
    <!-- #endregion -->

    <!-- #region Who is it for -->
    <div class="p-3 p-md-5 who-is-it">
      <h2 id="who-is-it-for" class="text-center pb-3 pb-md-5">{{ $t('landing.who_is_it_for') }}</h2>
      <div class="row text-center">
        <div class="col-sm pb-4">
          <div class="pb-3">
            <img :alt="$t('landing.alt.small_buisiness')" src="/small-business.svg">
          </div>
          <b>{{ $t('landing.small_businesses') }}</b>
          <div>{{ $t('landing.small_businesses_text') }}</div>
        </div>
        <div class="col-sm pb-4">
          <div class="pb-3">
            <img :alt="$t('landing.alt.software_devs')" src="/software-devs.svg">
          </div>
          <b>{{ $t('landing.software_developers') }}</b>
          <div>{{ $t('landing.software_developers_text') }}</div>
        </div>
        <div class="col-sm pb-4">
          <div class="pb-3">
            <img :alt="$t('landing.alt.marketers')" src="/marketers.svg">
          </div>
          <b>{{ $t('landing.marketers') }}</b>
          <div>{{ $t('landing.marketers_text') }}</div>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Featured in -->
    <div class="p-2 p-md-5">
      <h2 class="text-center pb-3 pb-md-5">{{ $t('landing.featured_in') }}</h2>
      <div class="card-columns featured-list">
        <div
          v-for="item in $t('landing.featured_in_links')"
          class="card mb-3"
        >
          <a :href="item.link" target="_blank">
            <img :src="item.src" :alt="item.alt">
          </a>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Awards -->
    <div class="p-3 p-md-5">
      <h2 class="text-center pb-3 pb-md-5 mb-5 mb-md-0">{{ $t('landing.awards') }}</h2>
      <div class="row awards-list mx-lg-5 mx-md-3">
        <div class="col-6 text-center p-0 p-sm-3 px-lg-5">
          <div>
            <img src="/logos/risingstar2019.png" alt="Rising Star award by FinancesOnline">
          </div>
        </div>
        <div class="col-6 text-center p-0 p-sm-3 px-lg-5">
          <div>
            <img src="/logos/premium2019.png" alt="Premium Usability award by FinancesOnline">
          </div>
        </div>
      </div>
    </div>
    <!-- #endregion -->

    <!-- #region Sign up -->
    <!--
    <div v-if="!authenticated" class="text-center bg-primary sign-up-block-2 text-white px-3 px-md-5">
      <h2 class="pb-4">{{ $t('landing.sign_up_for_your_free_account') }}</h2>
      <p class="pb-3">{{ $t('landing.sign_up_for_your_free_account_text') }}</p>
      <router-link
        :to="{ name: 'register', params: { _position: 'footer' } }"
        class="btn btn-secondary text-uppercase mb-4 mx-2"
      >{{ $t('landing.sign_up_for_free') }}</router-link>
      <router-link
        :to="{ path: $t('landing.try_it_link') }"
        class="btn btn-outline-light text-uppercase mb-4 mx-2"
      >{{ $t('landing.no_but_i_have_an_idea') }}</router-link>
    </div>
    -->
    <!-- #endregion -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ImagePlayer from '~/components/ImagePlayer'
import { loadMessages } from '~/plugins/i18n'
import Scenarios from './Scenarios'

export default {
  components: {
    ImagePlayer,
    Scenarios
  },

  layout: 'landing',

  fetch ({ store, route: { path } }) {
    // Detect page alias and change locale
    const locale = path.substr(1)
    if (locale) {
      loadMessages(locale)

      store.dispatch('lang/setLocale', { locale })
    }
  },

  data: () => ({
    title: process.env.appName,
    canonicalUrl: process.env.canonicalUrl
  }),

  computed: mapGetters({
    authenticated: 'auth/check',
    locale: 'lang/locale'
  }),

  watch: {
    async locale () {
      if (this.$route.path !== '/') this.$router.push({ path: '/' })
    }
  },

  created () {
    // work around history back
    const locale = this.$route.path.substr(1)
    if (locale && this.$i18n.locale !== locale) {
      loadMessages(locale)

      this.$store.dispatch('lang/setLocale', { locale })
    }
  },

  head () {
    return {
      title: this.$t('welcome'),
      link: [
        {
          rel: 'alternate',
          hreflang: 'x-default',
          href: `${this.canonicalUrl}/`
        },
        { rel: 'alternate', hreflang: 'en', href: `${this.canonicalUrl}/` },
        { rel: 'alternate', hreflang: 'ru', href: `${this.canonicalUrl}/ru` },
        { rel: 'alternate', hreflang: 'fr', href: `${this.canonicalUrl}/fr` },
        { rel: 'alternate', hreflang: 'ja', href: `${this.canonicalUrl}/ja` },
        { rel: 'alternate', hreflang: 'de', href: `${this.canonicalUrl}/de` }
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.$t('welcome_meta_description')
        }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~/assets/sass/_variables.scss";

header {
  .image-wrapper {
    position: relative;

    margin: 0 auto;
    bottom: -150px;
    margin-top: -120px;
    max-width: 600px;
    box-shadow: 1px 1px 5px 0px rgba(0, 0, 0, 0.5);
  }
}
.header-bottom {
  fill: $primary;
  margin: -1px 0 100px 0;
}

.sign-up-block {
  padding: 6rem 3rem;
  clip-path: polygon(0 20%, 100% 0, 100% 80%, 0 100%);

  @media (max-width: 462px) {
    clip-path: polygon(0 5%, 100% 0, 100% 95%, 0 100%);
  }
}

.sign-up-block-2 {
  padding: 6rem 3rem 2rem;
  clip-path: polygon(0 0, 100% 20%, 100% 100%, 0 100%);
  margin-bottom: -1px;

  @media (max-width: 462px) {
    clip-path: polygon(0 0, 100% 5%, 100% 100%, 0 100%);
  }
}

.who-is-it img,
.why-is-it img {
  width: 120px;
}

.features-list {
  /*text-align: center;*/

  div {
    min-height: 80px;
  }

  b {
    cursor: help;
  }
  p {
    visibility: hidden;
    display: block;
    opacity: 0;
    position: absolute;
    background: #ececec;
    z-index: 10;
    border: 1px solid #d5d5d5;
    padding: 0.6rem 0.9rem;
    left: 0;
    max-width: 300px;
    transition: visibility 0.3s linear, opacity 0.3s linear;
  }

  b:hover + p {
    visibility: visible;
    opacity: 1;
  }
}

.featured-list,
.awards-list {
  img {
    max-width: 100%;
    min-width: 40%;
  }
  a {
    transition: all 0.2s ease;
  }
  a:not(:hover) {
    filter: grayscale(100%);
    opacity: 0.8;
  }
}

.featured-list .card {
  border: none;
  box-shadow: none;
  text-align: center;
  height: 60px; // temp fix
}

.plans-overlay {
  left: 0;
  right: 0;
  height: 400px;
}

.box-shadow {
  box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
}
</style>
