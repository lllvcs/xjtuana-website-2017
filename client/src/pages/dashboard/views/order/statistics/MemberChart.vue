<template>
<div>
  <div ref="chart" style="width:100%; height:500px" @resize="chart.resize()"></div>
  <hr>
  <form class="row">
    <div class="form-group col-md-3 col-lg-3">
      <label for="input-search-old_members">社员状态</label>
      <select class="form-control" id="input-search-old_members" name="old_members" v-model="form.old_members">
        <option :value="0">现役社员</option>
        <option :value="1">退役社员</option>
        <option :value="null">全部</option>
      </select>
    </div>

    <div class="form-group col-md-3 col-lg-3">
      <label for="input-search-department">部门</label>
      <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
        <option value="">- 查看全部 -</option>
        <option v-for="department in departments" :value="department.id" :key="department.id">
          {{ department.name }}
        </option>
      </select>
    </div>

    <OrderDateRangePicker
      class="col-md-6 col-lg-6"
      :start.sync="form.created_at_start"
      :end.sync="form.created_at_end"
      label="结单日期"
    />
  </form>
</div>

</template>

<script>
import chartMixin from './mixins/chartMixin'
import clickZoomMixin from './mixins/clickZoomMixin'
import moment from '@/plugins/moment'

export default {
  mixins: [
    chartMixin,
    clickZoomMixin,
  ],
  data () {
    return {
      api: '/api/order_statistics/member',
      form: {
        old_members: 0,
        department_id: '',
        created_at_start: moment().subtract(6, 'days').format('YYYY-MM-DD'),
        created_at_end: moment().format('YYYY-MM-DD'),
      },
    }
  },
  computed: {
    categories () {
      if (this.results === null) {
        return null
      }
      let categories = []
      for (let result of this.results) {
        categories.push(result.user.profile.name)
      }
      return categories
    },
    data () {
      if (this.results === null) {
        return null
      }
      let data = []
      for (let result of this.results) {
        data.push(result.orders_count)
      }
      return data
    },
    option () {
      return {
        title: {
          text: '工作量统计',
          subtext: '社员处理报修量统计图',
          left: 'center',
        },
        toolbox: {
          feature: {
            magicType: {
              type: ['bar', 'line'],
            },
            dataView: {
              readOnly: true,

              optionToContent: (opt) => {
                let axisData = opt.xAxis[0].data
                let series = opt.series
                let table = `
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>姓名</th>
                        <th>${series[0].name}</th>
                      </tr>
                    </thead>
                    <tbody>`
                for (let i = 0, l = axisData.length; i < l; i++) {
                  table += `
                    <tr>
                      <td>${axisData[i]}</td>
                      <td>${series[0].data[i]}</td>
                    </tr>`
                }
                table += '</tbody></table>'
                return table
              },
            },
          },
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
          },
        },
        legend: {
          bottom: 0,
          data: ['结单量'],
        },
        xAxis: {
          type: 'category',
          data: this.categories,
        },
        yAxis: {
          type: 'value',
        },
        dataZoom: [
          {
            type: 'inside',
          },
        ],
        series: [{
          name: '结单量',
          type: 'bar',
          data: this.data,
          itemStyle: {
            normal: {
            },
          },
          markPoint: {
            data: [
              { type: 'max', name: '最大值' },
            ],
          },
          markLine: {
            data: [
              { type: 'average', name: '平均值' },
            ],
          },
        }],
      }
    },
  },
}
</script>
