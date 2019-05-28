<template>
<div>

  <section class="content" v-if="user">
    <div class="row">

      <!--左侧col开始-->
      <div class="col-md-8 col-lg-9">

        <!--个人资料开始-->
        <div class="box box-success">
          <div class="box-header with-border">
            <i class="fa fa-fw fa-vcard-o"></i>
            <h3 class="box-title">个人资料</h3>
          </div>
          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>NETID</th>
                  <td>{{ user.netid }}</td>
                </tr>
                <tr>
                  <th>学号</th>
                  <td>{{ user.profile.stuid }}</td>
                </tr>
                <tr>
                  <th>姓名</th>
                  <td>{{ user.profile.name }}</td>
                </tr>
                <tr>
                  <th>性别</th>
                  <td>{{ user.profile.gender }}</td>
                </tr>
                <tr>
                  <th>学院</th>
                  <td>{{ user.profile.college }}</td>
                </tr>
                <tr>
                  <th>班级</th>
                  <td>{{ user.profile.class }}</td>
                </tr>
                <tr>
                  <th>宿舍</th>
                  <td>{{ user.profile.dorm_building }} - {{ user.profile.dorm_room }}</td>
                </tr>
                <tr>
                  <th>手机</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('mobile') }">
                      <input type="text" class="form-control" name="mobile"
                        v-model="updateForm.mobile" v-validate="{ rules: { required: true, regex: /^1[3-9]\d{9}$/ } }" data-vv-as="手机">
                      <div class="help-block with-errors">{{ errors.first('mobile') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.mobile }}</td>
                </tr>
                <tr>
                  <th>QQ</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('qq') }">
                      <input type="text" class="form-control" name="qq"
                        v-model="updateForm.qq" v-validate="{ rules: { regex: /^[1-9]\d{4,9}$/ } }" data-vv-as="QQ号">
                      <div class="help-block with-errors">{{ errors.first('qq') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.qq }}</td>
                </tr>
                <tr>
                  <th>微信</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('wechat') }">
                      <input type="text" class="form-control" name="wechat"
                        v-model="updateForm.wechat" v-validate="{ rules: { regex: /^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$/ } }" data-vv-as="微信号">
                      <div class="help-block with-errors">{{ errors.first('wechat') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.wechat }}</td>
                </tr>
                <tr>
                  <th>邮箱</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('email') }">
                      <input type="text" class="form-control" name="email"
                        v-model="updateForm.email" v-validate="{ rules: { regex: /^[a-zA-Z0-9].*@.+(\..+)+$/ } }" data-vv-as="邮箱">
                      <div class="help-block with-errors">{{ errors.first('email') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.email }}</td>
                </tr>
                <tr>
                  <th>故乡</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('hometown') }">
                      <input type="text" class="form-control" name="hometown"
                        v-model="updateForm.hometown" v-validate="''" data-vv-as="故乡">
                      <div class="help-block with-errors">{{ errors.first('hometown') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.hometown }}</td>
                </tr>
                <tr>
                  <th>生日</th>
                  <td v-show="showEdit">
                    <DatePicker v-if="updateForm.birthday" class="form-control" name="birthday" v-model="updateForm.birthday" />
                  </td>
                  <td v-show="!showEdit">{{ user.profile.birthday }}</td>
                </tr>
                <tr>
                  <th>自我介绍</th>
                  <td v-show="showEdit">
                    <div class="form-group" :class="{ 'has-error': errors.has('desc') }">
                      <textarea class="form-control" name="desc"
                        v-model="updateForm.desc" v-validate="''" data-vv-as="自我介绍"></textarea>
                      <div class="help-block with-errors">{{ errors.first('desc') }}</div>
                    </div>
                  </td>
                  <td v-show="!showEdit">{{ user.profile.desc }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="box-footer text-center">
            <button v-show="!showEdit" class="btn btn-warning" @click="enterEdit"><i class="fa fa-edit"></i> 编辑</button>
            <button v-show="showEdit" class="btn btn-success" @click="updateProfile"><i class="fa fa-save"></i> 保存</button>
            <button v-show="showEdit" class="btn btn-default" @click="showEdit = false"><i class="fa fa-undo"></i> 取消</button>
          </div>
        </div>
        <!--个人资料结束-->

      </div>
      <!--左侧col结束-->

      <!--右侧col开始-->
      <div class="col-md-4 col-lg-3">
        <!--社团信息开始-->
        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-fw fa-user-secret"></i>

            <h3 class="box-title">社团信息</h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>所属部门</th>
                  <td>{{ user.member.department.name }}</td>
                </tr>
                <tr>
                  <th>职位</th>
                  <td>{{ user.member.designation.name }}</td>
                </tr>
                <tr>
                  <th>入社时间</th>
                  <td>{{ user.member.created_at.substring(0,10) }}</td>
                </tr>
                <tr v-if="user.member.deleted_at">
                  <th>退役时间</th>
                  <td>{{ user.member.deleted_at.substring(0,10) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!--社团信息结束-->

        <!--负责宿舍楼开始-->
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-fw fa-building"></i>

            <h3 class="box-title">负责宿舍楼</h3>

          </div>
          <div class="box-body">
            <div v-if="user.member.buildings.length > 0">
              <table class="table table-striped table-hover text-center">
                <thead>
                  <tr>
                    <th>校区</th>
                    <th>楼名</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="building in user.member.buildings" :key="building.id">
                    <td>{{ building.campus.name }}</td>
                    <td>{{ building.name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else>暂无负责宿舍楼</p>
          </div>
        </div>
        <!--负责宿舍楼结束-->
      </div>
      <!--右侧col结束-->

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
import { mapState } from 'vuex'
import DatePicker from '@/pages/dashboard/components/widgets/DatePicker'
export default {
  components: {
    DatePicker,
  },
  data () {
    return {
      loading: false,
      photo: null,
      showEdit: false,
      updateForm: {
        mobile: '',
        qq: '',
        wechat: '',
        email: '',
        birthday: '',
        hometown: '',
        desc: '',
      },
    }
  },
  computed: {
    ...mapState([
      'user',
    ]),
  },
  methods: {
    async updateProfile () {
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否确认保存？',
        text: '',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          '/api/user/profile',
          this.updateForm
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.$store.dispatch('refresh')
        this.showEdit = false
      })
        .catch(swal.noop)
    },
    enterEdit () {
      this.showEdit = true
      this.updateForm = {
        mobile: this.user.profile.mobile,
        qq: this.user.profile.qq,
        wechat: this.user.profile.wechat,
        email: this.user.profile.email,
        birthday: this.user.profile.birthday,
        hometown: this.user.profile.hometown,
        desc: this.user.profile.desc,
      }
    },
  },
}
</script>
