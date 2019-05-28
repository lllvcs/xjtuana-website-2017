<script>
import Vue from 'vue'
import echarts from 'echarts'
export default Vue.extend({
  render: function (createElement) {
    return createElement(
      'div', {
        style: this.style,
        ref: 'chart',
      }
    )
  },
  props: {
    width: {
      type: String,
      default: '100%',
    },

    height: {
      type: String,
      default: '100%',
    },
  },

  computed: {
    style () {
      return {
        width: this.width,
        height: this.height,
      }
    },
  },

  data () {
    return {
      chart: null,
      loading: false,
      option: {},
    }
  },

  watch: {
    loading (isLoading) {
      isLoading ? this.chart.showLoading() : this.chart.hideLoading()
    },
    option: {
      handler: 'updateChart',
      deep: true,
    },
  },

  created () {
    // 加载初始数据操作
    // this.option = { ... }
  },
  mounted () {
    this.chart = echarts.init(this.$refs.chart)
    this.updateChart()
  },
  methods: {
    setOption (opt = {}) {
      this.option = opt
      this.updateChart()
    },
    updateChart () {
      this.chart.setOption(this.option)
    },
  },
})
</script>
