<template>
  <div v-quill:myQuillEditor="editorOption"
       :data-focus="hasFocus"
       :content="content"
       class="quill-editor"
       @change="onEditorChange($event)"
       @blur="onEditorBlur($event)"
       @focus="onEditorFocus($event)"
  />
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: ''
    },

    placeholder: {
      type: String,
      default: 'Insert text here...'
    }
  },

  data () {
    return {
      content: this.value,
      editorOption: {
        theme: 'snow',
        // some quill options
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            //              ['blockquote', 'code-block'],
            [{ list: 'bullet' }, 'image']
          ]
        },
        placeholder: this.placeholder
      },
      hasFocus: false
    }
  },

  watch: {
    value (value) {
      this.content = value
    },

    placeholder (value) {
      this.myQuillEditor.root.setAttribute('data-placeholder', value)
    }
  },

  mounted () {
    const toolbar = this.myQuillEditor.getModule('toolbar')
    toolbar.addHandler('image', () => this.$emit('imageUpload'))
  },

  methods: {
    onEditorChange ({ editor, html, text }) {
      this.$emit('input', html)
    },
    onEditorBlur (quill) {
      this.hasFocus = quill.hasFocus()
    },
    onEditorFocus (quill) {
      this.hasFocus = quill.hasFocus()
    }
  }
}
</script>
