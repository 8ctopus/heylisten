<template>
  <div class="row">
    <div class="col-lg-8 m-auto text-center">

      <div class="card mb-3">
        <!-- Header -->
        <div class="card-header bg-transparent">
          <h1 class="text-primary">{{ $t('login') }}</h1>
        </div>

        <div class="card-body">
          <form @submit.prevent="login" @keydown="form.onKeydown($event)">
            <!-- Email -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
              <div class="col-md-7">
                <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email"
                       class="form-control">
                <has-error :form="form" field="email"/>
              </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
              <div class="col-md-7">
                <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password" name="password"
                       class="form-control">
                <has-error :form="form" field="password"/>
              </div>
            </div>

            <!-- Remember Me -->
            <div class="form-group row">
              <div class="col-md-3"/>
              <div class="col-md-7 d-flex">
                <checkbox v-model="remember" name="remember">
                  {{ $t('remember_me') }}
                </checkbox>

                <router-link :to="{ name: 'password.request' }" class="small ml-auto my-auto">
                  {{ $t('forgot_password') }}
                </router-link>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <v-button :loading="form.busy">
                  {{ $t('login') }}
                </v-button>

                <!-- GitHub Login Button -->
                <login-with-github/>
              </div>
            </div>
          </form>
        </div>

      </div>

      <div class="text-center">
        {{ $t('dont_have_an_account') }}
        <nuxt-link :to="{ name: 'register' }">{{ $t('register') }}</nuxt-link>
      </div>

    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'

export default {
  middleware: 'guest',

  head () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Login the form.
      const success = await this.$store.dispatch('auth/login', { form: this.form, remember: this.remember })

      if (!success) {
        // somethings goes wrong
        return
      }

      const workspace = this.$store.getters['auth/workspace']

      // Redirect home.
      this.$router.push({ name: 'home', params: { workspace } })
    }
  }
}
</script>
