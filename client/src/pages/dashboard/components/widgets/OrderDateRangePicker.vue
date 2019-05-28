<template>
  <div class="form-group">
    <label for="input-daterange">{{ label }}</label>
    <input ref="daterange" type="text" class="form-control" id="input-daterange">
  </div>
</template>

<script>
import moment from '@/plugins/moment'
export default {
  props: {
    start: {
      type: String,
      default: moment('2015-10-25').format('YYYY-MM-DD'),
    },
    end: {
      type: String,
      default: moment().format('YYYY-MM-DD'),
    },
    label: {
      type: String,
      default: '报修日期',
    },
  },
  mounted () {
    $(this.$refs.daterange).daterangepicker({
      locale: {
        format: 'YYYY-MM-DD',
        applyLabel: '确认',
        cancelLabel: '取消',
        customRangeLabel: '其他范围',
      },
      showDropdowns: true,
      startDate: this.start,
      endDate: this.end,
      opens: 'left',
      ranges: {
        '近7天': [moment().subtract(6, 'days'), moment()],
        '近30天': [moment().subtract(29, 'days'), moment()],
        '近3个月': [moment().subtract(3, 'month'), moment()],
        '今年': [moment().startOf('year'), moment()],
        '全部': [moment('2015-10-25'), moment()],
      },
    }, (start, end, label) => {
      this.$emit('update:start', start.format('YYYY-MM-DD'))
      this.$emit('update:end', end.format('YYYY-MM-DD'))
    })
  },
}
</script>
