<template>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <i class="fa fa-envelope"></i>

            <h3 class="box-title">发送短信</h3>
          </div>

          <div class="box-body">
            <form @submit.prevent="sendSms">
              <div class="form-group col-xs-12" :class="{ 'has-error': errors.has('targets') }">
                <label for="input-targets">发送目标</label>

                <input
                  id="input-targets"
                  class="form-control"
                  name="targets"
                  type="text"
                  placeholder="请输入目标手机号，多个手机号用半角逗号隔开"
                  v-validate="{ rules: { required: true, regex: /^(1[3-9]\d{9})(,1[3-9]\d{9})*$/ } }"
                  data-vv-validate-on="blur"
                  data-vv-as="发送目标"
                  v-model="form.targets"
                />

                <div class="help-block with-errors">{{ errors.first('targets') }}</div>
              </div>

              <div class="form-group col-xs-12" :class="{ 'has-error': errors.has('content') }">
                <label for="input-content">短信内容</label>

                <textarea
                  id="input-content"
                  class="form-control"
                  name="content"
                  placeholder="请输入短信内容"
                  rows=5
                  data-vv-as="短信内容"
                  v-validate="'required'"
                  v-model="form.content"
                ></textarea>

                <div class="help-block with-errors">{{ errors.first('content') }}</div>
              </div>
            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="sendSms" class="btn btn-success"><i class="fa fa-fw fa-send"></i> 发送</button>
          </div>
        </div>
      </div>
    </div><!-- /.row (main row) -->
  </section>
</template>

<script>
import { swal } from '@/plugins'
export default {
  data () {
    return {
      form: {
        targets: '',
        content: '【西交网管会】',
      },
    }
  },
  methods: {
    async sendSms () {
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否确认发送？',
        text: '请注意，发送短信可能有延迟，也有可能因为学校短信平台阻塞发送失败',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post('/api/tool/sms', this.form),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
      }).catch(swal.noop)
    },
  },
}
</script>
