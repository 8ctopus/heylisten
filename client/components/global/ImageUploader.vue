<template>
  <div>
    <!-- slot for parent component to activate the file changer -->
    <div @click="launchFilePicker()">
      <slot name="activator"/>
    </div>

    <!-- image input: style is set to hidden and assigned a ref so that it can be triggered -->
    <input ref="file"
           :name="uploadFieldName"
           type="file"
           accept="image/x-png,image/gif,image/jpeg"
           style="display: none;"
           @change="onFileChange($event.target.name, $event.target.files)"
    >
    <div v-if="errorDialog" class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="closeError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ errorText }}
    </div>
    <slot/>
  </div>
</template>

<script>
import { getBase64 } from '~/utils'

export default {
  name: 'ImageUploader',

  props: {
    // Use "value" to enable using v-model
    value: {
      type: String,
      default: null
    }
  },

  data: () => ({
    errorDialog: null,
    errorText: '',
    uploadFieldName: 'file',
    maxSize: 102400 // Kbytes
  }),

  watch: {
    // on Remove image clean input to allow
    // select same image after removing
    value () {
      this.$refs.file.value = ''
    }
  },

  methods: {
    launchFilePicker () {
      this.$refs.file.click()
    },

    closeError () {
      this.errorDialog = null
      this.$refs.file.value = ''
    },

    async onFileChange (fieldName, file) {
      this.errorDialog = null
      this.$emit('input', null)

      const { maxSize } = this
      let imageFile = file[0]
      if (file.length > 0) {
        let size = imageFile.size / (maxSize * 1024)
        if (!imageFile.type.match('image.*')) {
          // check whether the upload is an image
          this.errorDialog = true
          this.errorText = this.$t('attached_error_wrong_file')
        } else if (size > 1) {
          // check whether the size is greater than the size limit
          this.errorDialog = true
          this.errorText = this.$t('attached_error_too_big')
        } else {
          let imageData = await getBase64(imageFile)
          // Emit the FormData and image URL to the parent component
          this.$emit('input', imageData)
        }
      }
    }
  }
}
</script>
