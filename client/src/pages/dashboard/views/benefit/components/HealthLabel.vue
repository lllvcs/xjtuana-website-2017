<template>
  <span
    class="label"
    :class="labelClass"
    :title="labelTitle"
    data-toggle="tooltip"
    data-placement="top"
    data-html="true"
  >
    <i class="fa fa-fw" :class="iconClass"></i>
    {{ labelText }}
  </span>
</template>

<script>
import { moment } from '@/plugins'
export default {
  props: {
    loading: {
      type: Boolean,
      required: true,
    },
    healthy: {
      type: Boolean,
      required: false,
      default: null,
    },
    name: {
      type: String,
      required: false,
      default: '',
    },
    ts: {
      type: Number,
      required: false,
      default: null,
    },
  },
  computed: {
    labelText () {
      return this.loading
        ? 'checking'
        : this.healthy === null
          ? 'unknown'
          : this.healthy === true
            ? 'healthy'
            : 'error'
    },
    labelClass () {
      return this.loading
        ? 'label-primary'
        : this.healthy === null
          ? 'label-warning'
          : this.healthy === true
            ? 'label-success'
            : 'label-danger'
    },
    labelTitle () {
      return `${this.name} <br> ${this.timeDisplay}`
    },
    iconClass () {
      return this.loading
        ? 'fa-spin fa-spinner'
        : this.healthy === null
          ? 'fa-question-circle-o'
          : this.healthy === true
            ? 'fa-check'
            : 'fa-warning'
    },
    timeDisplay () {
      return this.ts ? moment.unix(this.ts).format('YYYY-MM-DD HH:mm:ss') : ''
    },
  },
}
</script>
