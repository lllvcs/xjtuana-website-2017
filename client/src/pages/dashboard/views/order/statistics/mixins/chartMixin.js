import _ from 'lodash'
import echarts from 'echarts'
import { mapState, mapGetters } from 'vuex'
import OrderDateRangePicker from '@/pages/dashboard/components/widgets/OrderDateRangePicker'

export default {
  components: {
    OrderDateRangePicker,
  },
  data () {
    return {
      api: null, // defined_in_component
      from: null, // defined_in_component
      chart: null,
      results: null,
      loading: false,
    }
  },
  computed: {
    ...mapState([
      'departments',
    ]),

    ...mapGetters([
      'departmentsOfService',
    ]),
  },
  watch: {
    loading (isLoading) {
      isLoading ? this.chart.showLoading() : this.chart.hideLoading()
    },
    option: {
      handler: 'updateChart',
      deep: true,
    },
    form: {
      handler: 'fetchData',
      deep: true,
    },
  },
  mounted () {
    this.chart = echarts.init(this.$refs.chart)

    window.addEventListener('resize', _.debounce(() => {
      this.chart.resize()
    }, 100))

    this.fetchData()
  },
  methods: {
    fetchData () {
      this.loading = true
      this.$axios.get(
        this.api, {
          params: this.form,
        }
      ).then((response) => {
        this.loading = false
        this.results = response.data.data
      }).catch(null)
    },
    updateChart () {
      this.chart.setOption(this.option)
    },
  },
}
