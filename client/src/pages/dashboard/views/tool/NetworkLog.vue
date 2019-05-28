<template>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-search"></i>

            <h3 class="box-title">查询条件</h3>
          </div>

          <div class="box-body">
            <form class="row" @submit.prevent="fetchData">
              <div class="form-group col-xs-12">
                <label for="input-type">查询目标</label>
                <div style="padding:10px">
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-1" value="wenet_pppoe" v-model="form.type"> wenet_pppoe
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-2" value="stu_pppoe" v-model="form.type"> stu_pppoe
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-3" value="stu_wlan" v-model="form.type"> stu_wlan
                  </label>
                </div>
              </div>

              <div class="form-group col-xs-12" :class="{ 'has-error': errors.has('username') }">
                <label for="input-username">用户名</label>
                <input class="form-control" id="input-username" name="username" type="text" placeholder="请输入查询用户名"
                  v-model="form.username" v-validate="'required'" data-vv-as="用户名">
                <div class="help-block with-errors">{{ errors.first('username') }}</div>
              </div>
            </form>

            <button class="btn btn-info btn-sm pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button class="btn btn-primary" @click="fetchData" :disabled="loading">
              <i class="fa fa-fw fa-search" v-show="!loading"></i><i class="fa fa-refresh fa-spin" v-show="loading"></i> 查询<span v-show="loading">中...</span>
            </button>

            <div v-show="!loading && results && results.length === 0">
              <hr>

              <p class="text-danger">没有查询到相关信息</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row" v-if="!loading && results">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-history"></i>

            <h3 class="box-title">日志信息 </h3>
          </div>

          <div class="box-body">
            <div v-html="displayLog"></div>
          </div>
        </div>
      </div>
    </div><!-- /.row (main row) -->
  </section>
</template>

<script>
export default {
  name: 'NetworkLog',

  data () {
    return {
      form: {
        type: 'wenet_pppoe',
        username: '',
      },
      loading: false,
      results: null,
    }
  },

  computed: {
    displayLog () {
      if (Array.isArray(this.results)) {
        let displayLog = ''
        for (const result of this.results) {
          displayLog += `<div>
            <p>时间：${result.authtime}</p>
            <p>用户名：${result.username}</p>
            <p>MAC地址：${result.usermacaddr}</p>
            <p>状态：${result.authstat}</p>
            <p>日志：${result.logdesc}</p>
            <hr/>
          </div>`
        }
        return displayLog
      } else {
        return this.results
      }
    },
  },

  methods: {
    reset () {
      this.form.username = ''
      this.$nextTick(() => {
        this.errors.clear()
      })
    },
    async fetchData () {
      try {
        let isValid = await this.$validator.validateAll()
        if (!isValid) return false
        this.loading = true
        const response = await this.$axios.get('/api/tool/networklog', {
          params: this.form,
        })
        this.results = response.data.data
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
