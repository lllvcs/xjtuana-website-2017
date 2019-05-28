<template>
  <input ref="datepicker" type="text" :value="value">
</template>

<script>
import moment from '@/plugins/moment'

export default {
  props: {
    value: {
      type: String,
    },
  },
  mounted () {
    $(this.$refs.datepicker).daterangepicker({
      locale: {
        format: 'YYYY-MM-DD',
      },
      singleDatePicker: true,
      showDropdowns: true,
      minDate: moment('1896-01-01'),
      maxDate: moment('2017-09-01'),
    }, (start, end, label) => {
      this.$emit('input', start.format('YYYY-MM-DD'))
    })
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
