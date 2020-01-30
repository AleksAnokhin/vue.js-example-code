<template>
  <div
    v-if="childTemplate"
  >
    <h3 class="text-left">
      {{ childTemplate.name }}
    </h3>
    <a-form
      class="p-3"
    >
      <a-form-item
        label="From"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
      >
        <a-select
          v-model="childTemplate.from"
          show-search
          @change="updateTemplate"
        >
          <a-select-option
            v-for="mail in localMails"
            :key="mail"
          >
            {{ mail }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <!--    TO section -->
      <a-form-item
        label="To"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
      >
        <template>
          <a-select
            v-model="childTemplate.to"
            mode="tags"
            :token-separators="[',']"
            @change="updateTemplate"
          >
            <a-select-option
              v-for="email in toEmails"
              :key="email"
            >
              {{ email }}
            </a-select-option>
          </a-select>
        </template>
      </a-form-item>
      <!--  Copy email sector  -->

      <a-form-item
        label="Copy"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
      >
        <template>
          <a-select
            v-model="childTemplate.copy"
            mode="tags"
            :token-separators="[',']"
            @change="updateTemplate"
          >
            <a-select-option
              v-for="email in copyEmails"
              :key="email"
            >
              {{ email }}
            </a-select-option>
          </a-select>
        </template>
      </a-form-item>
      <a-form-item
        label="Enabled"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
      >
        <a-switch
          v-model="childTemplate.enabled"
          @change="updateTemplate"
        />
      </a-form-item>
      <a-form-item
        label="Send copy to manager"
        :label-col="{ span: 5 }"
        :wrapper-col="{ span: 19 }"
      >
        <a-switch
          v-model="childTemplate.send_copy_to_manager"
          @change="updateTemplate"
        />
      </a-form-item>
      <a-form-item
        label="Send copy to customers"
        :label-col="{ span: 5 }"
        :wrapper-col="{ span: 19 }"
      >
        <a-switch
          v-model="childTemplate.send_copy_to_customers"
          @change="updateTemplate"
        />
      </a-form-item>
    </a-form>
  </div>
</template>

<script>
    export default {
      name: "MailForm",
      props: {
					template: {
						type: Object,
						default: () => {},
					},
					localMails: {
						type: Array,
						default: () => [],
					},
			},
      data() {
        return {
          childTemplate: {},
        }
      },
      computed: {
        from() {
          return this.localMails.filter((mail) => mail !== this.template.from)
        },
        toEmails() {
          if (this.template.to === null) return this.localMails
         return this.localMails.filter((mail) => {
           return this.template.to.indexOf(mail) < 0
         })
        },
        copyEmails() {
          if (this.template.copy === null) return this.localMails
          return this.localMails.filter((mail) => {
            return this.template.copy.indexOf(mail) < 0
          })
        },
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
          this.$emit('updateTemplate', this.childTemplate, ['from', 'to', 'copy','enabled', 'send_copy_to_manager','send_copy_to_customers'])
        },
      },
    }
</script>

<style scoped>

</style>
