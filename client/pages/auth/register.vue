<template>
  <div class="row">
    <div class="col-lg-8 m-auto text-center">

      <div class="card mb-3">
        <!-- Header -->
        <div class="card-header bg-transparent">
          <h1 class="text-primary">{{ $t('sign_up_title') }}</h1>
          <p class="text-primary">{{ $t('sign_up_subtitle') }}</p>
        </div>

        <div class="card-body">

          <form class="mb-3" @submit.prevent="validate" @keydown="form.onKeydown($event)">
            <!-- Email -->
            <div class="form-group">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" :placeholder="$t('email')" type="email"
                     name="email" class="form-control">
              <has-error :form="form" field="email"/>
            </div>

            <!-- Name -->
            <div class="form-group">
              <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" :placeholder="$t('name')" type="text"
                     name="name" class="form-control">
              <has-error :form="form" field="name"/>
            </div>

            <!-- Password -->
            <div class="form-group">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" :placeholder="$t('password')" type="password"
                     name="password" class="form-control">
              <has-error :form="form" field="password"/>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group">
              <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" :placeholder="$t('confirm_password')" type="password"
                     name="password_confirmation" class="form-control">
              <has-error :form="form" field="password_confirmation"/>
            </div>

            <!-- reCAPTCHA -->
            <div v-if="recaptchaKey" class="form-group">
              <vue-recaptcha
                ref="recaptcha"
                :sitekey="recaptchaKey"
                size="invisible"
                badge="bottomleft"
                @verify="register"
                @expired="onCaptchaExpired"
              />
              <has-error :form="form" field="recaptcha_token" class="d-block"/>
            </div>

            <!-- SignUp Button -->
            <div class="form-group">
              <v-button :loading="form.busy">
                {{ $t('sign_up') }}
              </v-button>
            </div>

          </form>
        </div>

      </div>

      <div class="text-center">
        {{ $t('already_have_an_account') }}
        <nuxt-link :to="{ name: 'login' }">{{ $t('login') }}</nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import VueRecaptcha from 'vue-recaptcha'

export default {
  middleware: 'guest',

  components: { VueRecaptcha },

  head () {
    return { title: this.$t('register') }
  },

  data: () => ({
    recaptchaKey: process.env.recaptchaSiteKey || null,
    form: new Form({
      name: '',
      email: '',
      job: '',
      password: '',
      password_confirmation: '',
      recaptcha_token: ''
    })
  }),

  mounted () {
    const $script = document.createElement('script')
    $script.async = true
    $script.src = 'https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit'
    document.head.appendChild($script)
  },

  methods: {
    async register (recaptcha_token) {
      // Register the user.
      this.form.recaptcha_token = recaptcha_token
      const success = await this.$store.dispatch('auth/register', { form: this.form })

      if (!success) {
        // somethings goes wrong
        if (this.recaptchaKey) this.$refs.recaptcha.reset()
        return
      }

      // Redirect home.
      const workspace = this.$store.getters['auth/workspace']
      this.$router.push({ name: 'home', params: { workspace } })
    },

    async validate () {
    // тут можно добавить проверку на валидацию
    // например, с помощью vee validate
    // если с валидацией наших полей все хорошо, запускаем каптчу
      if (this.recaptchaKey) {
        this.$refs.recaptcha.execute()
      } else {
        await this.register()
      }
    },

    onCaptchaExpired () {
      this.$refs.recaptcha.reset()
    }
  }
}
</script>
