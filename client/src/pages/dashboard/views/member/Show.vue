<template>
<div>
  <section class="content" v-show="loading">加载中...</section>
  <section class="content" v-if="member">
    <!-- Small boxes (Stat box) -->
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
                <!--<tr>-->
                <!--  <th>NETID</th>-->
                <!--  <td>{{ member.user.netid }}</td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--  <th>学号</th>-->
                <!--  <td>{{ member.user.profile.stuid }}</td>-->
                <!--</tr>-->
                <tr>
                  <th>姓名</th>
                  <td>{{ member.user.profile.name }}</td>
                </tr>
                <tr>
                  <th>性别</th>
                  <td>{{ member.user.profile.gender }}</td>
                </tr>
                <tr>
                  <th>学院</th>
                  <td>{{ member.user.profile.college }}</td>
                </tr>
                <tr>
                  <th>班级</th>
                  <td>{{ member.user.profile.class }}</td>
                </tr>
                <tr>
                  <th>宿舍</th>
                  <td>{{ member.user.profile.dorm_building }} - {{ member.user.profile.dorm_room }}</td>
                </tr>
                <tr>
                  <th>手机</th>
                  <td>{{ member.user.profile.mobile }}</td>
                </tr>
                <tr>
                  <th>微信</th>
                  <td>{{ member.user.profile.wechat }}</td>
                </tr>
                <tr>
                  <th>QQ</th>
                  <td>{{ member.user.profile.qq }}</td>
                </tr>
                <tr>
                  <th>故乡</th>
                  <td>{{ member.user.profile.hometown }}</td>
                </tr>
                <tr>
                  <th>生日</th>
                  <td>{{ member.user.profile.birthday }}</td>
                </tr>
                <tr>
                  <th>自我介绍</th>
                  <td>{{ member.user.profile.desc }}</td>
                </tr>
              </tbody>
            </table>
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
                  <td>{{ member.department.name }}</td>
                </tr>
                <tr>
                  <th>职位</th>
                  <td>{{ member.designation.name }}</td>
                </tr>
                <tr>
                  <th>入社时间</th>
                  <td>{{ member.created_at.substring(0,10) }}</td>
                </tr>
                <tr v-if="member.deleted_at">
                  <th>退役时间</th>
                  <td>{{ member.deleted_at.substring(0,10) }}</td>
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
            <div v-if="member.buildings.length > 0">
              <table class="table table-striped table-hover text-center">
                <thead>
                  <tr>
                    <th>校区</th>
                    <th>楼名</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="building in member.buildings" :key="building.id">
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
export default {
  data () {
    return {
      loading: true,
      member: null,
    }
  },
  computed: {
    memberId () {
      return this.$route.params.id
    },
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.loading = true
      this.$axios.get(
        `/api/member/${this.memberId}`
      ).then(response => {
        this.member = response.data.data
        this.loading = false
      })
    },
  },
}
</script>
