<template>
  <div v-if="childTemplate">
    <h3 class="text-left">
      {{ childTemplate.name }}
    </h3>
    <a-form
      class="p-3"
    >
      <a-form-item
        label="Subject"
      >
        <a-input
          v-model="childTemplate.subject"
          placeholder="Type mail subject here"
          @change="updateTemplate"
        />
      </a-form-item>
      <a-form-item
        label="Message"
      />
      <editor
        v-model="childTemplate.message"
        @change="updateTemplate"
      />
    </a-form>
    <a-form-item
      label="Description"
    >
      <a-textarea
        v-model="childTemplate.description"
        placeholder="Type mail description here"
        :rows="4"
        @change="updateTemplate"
      />
    </a-form-item>
  </div>
</template>

<script>
  import 'tui-editor/dist/tui-editor.css'
  import 'tui-editor/dist/tui-editor-contents.css'
  import 'codemirror/lib/codemirror.css'
  import { Editor } from '@toast-ui/vue-editor'

    export default {
        name: "Message",
        components: {
          'editor' : Editor,
        },
        props: {
					template: {
						type: Object,
						default: () => {},
					},
				},
      data() {
        return {
          childTemplate: {},
        }
      },

      watch: {
        template() {
          this.childTemplate = this.template
        },

      },
      beforeMount() {
          this.childTemplate = this.template
      },
      methods: {
        updateTemplate() {
          this.$emit('updateTemplate', this.childTemplate, ['subject', 'message', 'description'])
        },
      },
    }
</script>

<style scoped>

</style>
