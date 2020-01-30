<template>
  <a-card
    title="Mail templates"
    :body-style="{padding: 0}"
  >
    <template slot="extra">
      <a-popconfirm
        placement="top"
        ok-text="Yes"
        cancel-text="No"
        @confirm="revert"
      >
        <template slot="title">
          <p>Do you really want to rollback all changes made with this template?</p>
        </template>
        <a-button
          type="danger"
          class="mr-2"
        >
          Revert
          <a-icon type="rollback" />
        </a-button>
      </a-popconfirm>
      <a-button
        type="danger"
        class="mr-2"
        @click="unDo"
      >
        Undo
        <a-icon type="undo" />
      </a-button>
      <a-button
        type="primary"
        @click="saveChanges"
      >
        Save
        <a-icon type="save" />
      </a-button>
    </template>

    <a-row>
      <a-col
        :span="6"
      >
        <div class="w-100">
          <div class="p-3 w-100">
            <a-input-search
              v-model="search"
              placeholder="Search by name"
              allow-clear
            />
          </div>
          <a-menu
            v-if="templates"
            :default-selected-keys="[currentKey]"
            :default-open-keys="['sub1']"
            mode="inline"
            :inline-collapsed="collapsed"
            @click="arrangeTemplate"
          >
            <a-menu-item
              v-for="(template, index) in templateList"
              :key="index"
            >
              <a-icon type="mail" />
              <span>{{ template.name }}</span>
            </a-menu-item>
          </a-menu>
        </div>
      </a-col>

      <a-col
        :span="18"
        class="p-3"
      >
        <a-tabs>
          <a-tab-pane
            key="1"
            tab="Message"
          >
            <message
              :template="currentTemplate"
              @updateTemplate="updateTemplate"
            />
          </a-tab-pane>
          <a-tab-pane
            key="2"
            tab="Settings"
          >
            <mail-form
              :template="currentTemplate"
              :local_mails="local_mails"
              @updateTemplate="updateTemplate"
            />
          </a-tab-pane>
        </a-tabs>
      </a-col>
    </a-row>
  </a-card>
</template>

<script>


  import {mapActions} from "vuex";
  import Form from './settings.vue'
  import Message from './message.vue'

    export default {
        name: "MailTemplates",
      components: {
        'mail-form': Form,
        'message' : Message,
      },
      middleware: 'auth',
      data() {
        return {
          collapsed: false,
          loading: false,
          templates:[],
          local_mails:[],
          search: '',
          currentKey: 0,
          store:[],
        };
      },
      computed: {
        templateList() {
          if (this.search.length === 0) return this.templates
          const search = new RegExp(this.search, "i")
          return this.templates.filter((template) => {
            return template.name.match(search)
          })
        },
        currentTemplate() {
          return this.templateList[this.currentKey]
        },
      },
      mounted() {
        this.fetch()
      },
      methods: {
        ...mapActions({
          getTemplates: 'mail-templates/getTemplates',
          update: 'mail-templates/updateMailTemplate',
          revertState: 'mail-templates/revert',
        }),
        fetch() {
            this.loading = true
            this.getTemplates()
            .then(r => {
              const { templates, local_mails } = r.data
              this.templates = templates
              this.store = _.cloneDeep(templates)
              this.local_mails = local_mails
            })
            .finally(() => {
              this.loading = false
            })
        },
        arrangeTemplate(item) {
          this.currentKey = item.key
        },
        updateTemplate(child, fields) {
         fields.map((field) => {
           this.currentTemplate[field] = child[field]
         })
        },
        createClone() {
          return _.cloneDeep(this.store)
        },
        unDo() {
          this.templates = this.createClone()
        },
        revert() {
          this.revertState(this.currentTemplate)
            .then(r => {
              this.fetch()
            })
        },
        saveChanges() {
            this.update(this.currentTemplate)
          .then(r => {
						this.fetch()
          })
        },
      },
    }
</script>

<style scoped>


</style>
