<template>
<div>
  <div ref="chart" style="width:100%; height:500px" @resize="chart.resize()"></div>
  <hr>
  <form class="row">

    <div class="form-group col-md-6 col-lg-3">
      <label for="input-search-department">运维部</label>
      <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
        <option value="">- 查看全部 -</option>
        <option v-for="department in departmentsOfService" :value="department.id" :key="department.id">
          {{ department.name }}
        </option>
      </select>
    </div>

    <div class="form-group col-md-6 col-lg-3">
      <label for="input-search-building">宿舍楼</label>
      <select class="form-control" id="input-search-building" name="building_id" v-model="form.building_id">
        <option value="">- 查看全部 -</option>
        <template v-for="department in departments">
          <template v-if="department.id === form.department_id">
            <option v-for="building in department.buildings" :value="building.id" :key="building.id">
              {{ building.name }}
            </option>
          </template>
        </template>
      </select>
    </div>

    <OrderDateRangePicker
      class="col-md-12 col-lg-6"
      :start.sync="form.created_at_start"
      :end.sync="form.created_at_end"
      label="报修日期"
    />
  </form>
</div>

</template>

<script>
import chartMixin from './mixins/chartMixin'
import moment from '@/plugins/moment'

export default {
  mixins: [
    chartMixin,
  ],
  data () {
    return {
      api: '/api/order_statistics/daily',
      form: {
        building_id: '',
        department_id: '',
        created_at_start: moment().subtract(29, 'days').format('YYYY-MM-DD'),
        created_at_end: moment().format('YYYY-MM-DD'),
      },
    }
  },
  computed: {
    data () {
      if (this.results === null) {
        return null
      }
      let data = []
      for (let result of this.results) {
        data.push({
          name: result.date,
          value: [
            result.date,
            result.total,
          ],
        })
      }
      return data
    },

    option () {
      return {
        title: {
          text: '日报修量统计',
          subtext: '社团每日报修总量统计图',
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
                let series = opt.series
                let table = `
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>日期</th>
                        <th>${series[0].name}</th>
                      </tr>
                    </thead>
                    <tbody>`
                for (let i = 0, l = series[0].data.length; i < l; i++) {
                  table += `
                      <tr>
                        <td>${series[0].data[i].name}</td>
                        <td>${series[0].data[i].value[1]}</td>
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
          data: ['报修量'],
        },
        grid: {
          top: 80,
          bottom: 60,
        },
        xAxis: {
          type: 'time',
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
          name: '报修量',
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

  watch: {
    'form.department_id' () {
      this.form.building_id = ''
    },
  },
}
</script>
