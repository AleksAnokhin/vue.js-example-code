import axios from 'axios'

export const actions = {
  async getTemplates(context) {
    return axios.get('/mail-templates/all')
  },
  async updateMailTemplate(context, payload) {
    return axios.put(`/mail-templates/update/${payload.id}`, payload)
  },
  async revert(context, payload) {

    return axios.get(`/mail-templates/revert/${payload.slug}`)
  },
}
