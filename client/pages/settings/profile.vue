<template>
  <card :title="$t('your_info')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')"/>

      <!-- Name -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
        <div class="col-md-7">
          <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" type="text" name="name"
                 class="form-control">
          <has-error :form="form" field="name"/>
        </div>
      </div>

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email"
                 class="form-control">
          <has-error :form="form" field="email"/>
        </div>
      </div>

      <!-- Workspace alias -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('workspace_alias') }}</label>
        <div class="input-group mb-2 mr-sm-2 col-md-7">
          <div class="input-group-prepend">
            <div class="input-group-text">{{ canonicalUrl }}/</div>
          </div>
          <input v-model="form.workspace" :class="{ 'is-invalid': form.errors.has('workspace') }" type="text" name="workspace"
                 class="form-control">
          <has-error :form="form" field="workspace"/>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="primary">{{ $t('update') }}</v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  head () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      workspace: ''
    }),
    canonicalUrl: process.env.canonicalUrl
  }),

  computed: mapGetters({
    user: 'auth/user',
    workspace: 'auth/workspace'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
    this.form.workspace = this.workspace
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
