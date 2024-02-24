<template>
  <div class="card border-light mb-5">
    <h5 class="card-header text-center">{{ $t('suggest_a_feature') }}</h5>
    <div class="card-body">
      <form @submit.prevent="create" @keydown="form.onKeydown($event)">
        <div class="form-group">
          <input :placeholder="$t('name_your_feature')" v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }"
                 type="text" class="form-control">
          <has-error :form="form" field="title"/>
        </div>
        <div class="form-group">
          <input placeholder="Email" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }"
                 type="email" class="form-control">
          <has-error :form="form" field="email"/>
        </div>
        <div class="form-group">
          <editor
            v-model="form.description"
            :placeholder="`${$t('describe_your_feature')} (${$t('optional')})`"
            :data-invalid="form.errors.has('description')"
            @imageUpload="$refs.imageUploader.click()"/>
          <has-error :form="form" field="description"/>
        </div>

        <image-uploader v-model="image" class="image-uploader mb-3">
          <div slot="activator" ref="imageUploader"/>
          <div v-if="form.image" :data-invalid="form.errors.has('image')" class="image">
            <div class="tools"><span class="remove text-danger" @click="removeImage">×</span></div>
            <img :src="form.image" alt="image">
          </div>
          <has-error :form="form" field="image"/>
        </image-uploader>

        <alert-error v-if="form.errors.errors && form.errors.errors.error" :form="form">{{ $t('error_alert_text') }}</alert-error>

        <!-- Submit Button -->
        <div class="text-center">
          <v-button :loading="form.busy" type="secondary">
            <fa icon="plus" />
            {{ $t('submit') }}
          </v-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Editor from '~/components/TextEditor'
import { mapActions } from 'vuex'
import { resizeImage } from '~/utils/index'

export default {
  components: {
    Editor
  },

  props: {
    currentProduct: {
      type: Object,
      default: () => ({
        slug: '',
        name: '',
        workspace: {
          alias: ''
        }
      })
    }
  },

  data: () => ({
    form: new Form({
      title: '',
      email: '',
      description: '',
      image: null
    }),
    image: null
  }),

  watch: {
    async image () {
      if (this.image) this.form.image = await resizeImage({ src: this.image })
      else this.form.image = null
      this.form.errors.clear('image')
    }
  },

  methods: {
    ...mapActions('ideas', {
      createIdea: 'create'
    }),

    removeImage () {
      this.image = null
    },

    // Create new idea
    async create () {
      this.form.product = this.currentProduct.slug
      this.form.workspace = this.currentProduct.workspace.alias
      const data = this.form.data()

      try {
        this.form.startProcessing()
        const { data: idea } = await this.createIdea({ data })

        // Redirect to createed idea
        this.$emit('create', idea)
        // this.$router.push({ name: 'idea', params: { idea: id + '-' + slug } })
      } catch (e) {
        this.form.errors.set(this.form.extractErrors(e))
      } finally {
        this.form.finishProcessing()
      }
    }
  }
}
</script>

<style scoped>
.image-uploader {
    position: relative;
}
.image {
    border: 1px solid #ced4da;
    border-radius: 3px;
    overflow: hidden;
    display: inline-block;
    position: relative;
    max-width: 200px;
    max-height: 200px;
}
.image img {
    max-width: 100%;
    max-height: 100%;
}
.image[data-invalid="true"] {
    border-color: #dc3545;
}
.image + .invalid-feedback {
    display: block;
}
.tools {
  position: absolute;
  width: 100%;
  text-align: right;
  background-color: rgba(200, 200, 200, 0.7)
}
.remove {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1rem;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .75;
    cursor: pointer;
    top: 0;
    right: 0;
    margin: 0.25rem;
    margin-left: auto;
}
</style>
