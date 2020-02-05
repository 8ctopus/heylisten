<template>
  <div>

    <div v-if="greeting" class="alert alert-warning alert-dismissible fade show text-justify" role="alert">
      <div v-html="$t('project_page.registration_greeting')"/>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="readNotification(greeting)">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <h1 class="mb-4">{{ $t('list_of_products') }}</h1>

    <table class="table">
      <thead>
        <tr>
          <th class="break-words">{{ $t('product_name') }}</th>
          <th class="break-words">{{ $t('product_slug') }}</th>
          <th class="text-sm-right break-words edit-col">{{ $t('actions') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in productList" :key="product.id">
          <template v-if="product.id !== editorId">
            <td><nuxt-link :to="{ name: 'ideas', params: { product: product.slug } }">{{ product.name }}</nuxt-link></td>
            <td>{{ product.slug }}</td>
            <td class="text-center text-sm-right" nowrap>
              <nuxt-link :to="{ name: 'ideas', params: { product: product.slug } }">
                <fa icon="eye" class="text-dark fa-lg fa-fw action-button"/>
              </nuxt-link>
              <fa icon="edit" class="text-dark fa-lg fa-fw action-button" @click="startEdit(product)"/>
              <fa icon="trash-alt" class="text-danger fa-fw fa-lg action-button " @click="remove(product)"/>
            </td>
          </template>
          <template v-else>
            <td>
              <input :placeholder="$t('product_name_placeholder')" v-model="formEdit.name" :class="{ 'is-invalid': formEdit.errors.has('name') }"
                     type="text" class="form-control">
              <has-error :form="formEdit" field="name"/>
            </td>
            <td>
              <input :placeholder="$t('product_slug_placeholder')" v-model="formEdit.slug" :class="{ 'is-invalid': formEdit.errors.has('slug') }"
                     type="text" class="form-control">
              <has-error :form="formEdit" field="slug"/>
            </td>
            <td class="text-center text-sm-right">
              <fa icon="check" class="text-success fa-lg fa-fw action-button" @click="edit(product)"/>
              <fa icon="times" class="text-danger fa-lg fa-fw action-button" @click="editorId = null"/>
            </td>
          </template>
        </tr>
        <tr>
          <td>
            <input :placeholder="$t('product_name_placeholder')" v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }"
                   type="text" class="form-control mb-2 mr-sm-2">
            <has-error :form="form" field="name"/>
          </td>
          <td>
            <input :placeholder="$t('product_slug_placeholder')" v-model="form.slug" :class="{ 'is-invalid': form.errors.has('slug') }"
                   type="text" class="form-control">
            <has-error :form="form" field="slug"/>
          </td>
          <td class="text-right">
            <v-button :loading="form.busy" type="primary" class="mb-2" @click.native="create">
              <fa icon="plus-square" />
              <span class="d-none d-sm-inline">{{ $t('create') }}</span>
            </v-button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Form from 'vform'
import { toast, swal } from '~/plugins/alerts'
import slugify from 'slugify'

export default {
  middleware: ['workspace', 'auth'],

  async fetch ({ params, query, redirect, store, route }) {
    const { workspace } = params
    await store.dispatch('products/fetchList', { customUrlFnArgs: { workspace } })
  },

  head () {
    return { title: this.$t('home') }
  },

  data: () => ({
    form: new Form({
      name: '',
      slug: ''
    }),

    formEdit: new Form({
      name: '',
      slug: ''
    }),

    editorId: null
  }),

  computed: {
    ...mapGetters({
      productList: 'products/list',
      findNotification: 'auth/notificationByType'
    }),

    greeting () {
      return this.findNotification('App\\Notifications\\UserGreeting')
    }
  },

  watch: {
    'form.name' (value) {
      this.form.slug = slugify(value, { lower: true })
    }
  },

  methods: {
    ...mapActions({
      fetchProducts: 'products/fetchList',
      createProduct: 'products/create',
      updateProduct: 'products/update',
      removeProduct: 'products/destroy',
      readNotification: 'auth/readNotification'
    }),

    // Create new project
    async create () {
      const { workspace } = this.$route.params
      this.form.workspace = workspace
      const data = this.form.data()

      try {
        await this.createProduct({ data })

        this.form.reset()
        await this.fetchProducts({ customUrlFnArgs: { workspace } })
      } catch (e) {
        console.log(e)
        this.form.errors.set(this.form.extractErrors(e))
      }
    },

    async remove ({ id }) {
      const { workspace } = this.$route.params

      // Cancel
      if (!await swal({ title: this.$t('delete_popup.title'), text: this.$t('delete_popup.text'), confirmButtonText: this.$t('ok'), cancelButtonText: this.$t('cancel') })) {
        return
      }

      await this.removeProduct({ id })

      toast({ title: this.$t('product_deleted') })

      await this.fetchProducts({ customUrlFnArgs: { workspace } })
    },

    startEdit ({ id, slug, name }) {
      const { workspace } = this.$route.params

      this.editorId = id
      this.formEdit.clear()
      this.formEdit.name = name
      this.formEdit.slug = slug
    },

    async edit ({ id }) {
      const { workspace } = this.$route.params

      this.formEdit.workspace = this.$route.params.workspace
      const data = this.formEdit.data()

      try {
        await this.updateProduct({ id, data })

        this.formEdit.reset()
        this.editorId = null
        await this.fetchProducts({ customUrlFnArgs: { workspace } })
      } catch (e) {
        console.log(e)
        this.formEdit.errors.set(this.formEdit.extractErrors(e))
      }
    }
  }
}
</script>

<style scoped>
  .action-button {
    opacity: 0.8;
    cursor: pointer;
  }

  .action-button:hover {
    opacity: 1;
  }

  .break-words {
    word-break: break-all;
    word-wrap: break-word;
  }
  .no-break-words {
    word-break: keep-all;
    word-wrap: normal;
  }
  .edit-col {
    min-width: 85px;
  }
</style>
