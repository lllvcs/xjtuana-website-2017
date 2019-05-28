<template>
<div>

  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-xs-12">

        <div class="box box-primary">

          <div class="box-header with-border">
            <i class="fa fa-building"></i>

            <h3 class="box-title">部门/宿舍楼</h3>

          </div>

          <div class="box-body">
            <form class="row">
              <div class="form-group col-md-6 col-lg-6">
                <label for="input-search-department">运维部</label>
                <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
                  <option disabled style="display:none;" value="">- 选择运维部 -</option>
                  <option v-for="department in departmentsOfService" :value="department.id" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-6">
                <label for="input-search-building">宿舍楼</label>
                <select class="form-control" id="input-search-building" name="building_id" v-model="form.building_id">
                  <option value="">- 查看全部 -</option>
                  <template v-for="department in departmentsOfService">
                    <template v-if="department.id === form.department_id">
                      <option v-for="building in department.buildings" :value="building.id" :key="building.id">
                        {{ building.name }}
                      </option>
                    </template>
                  </template>
                </select>
              </div>
            </form>

          </div>

        </div>

      </div>

      <div class="col-xs-12">

        <div v-if="results" class="box box-success">

          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-list"></i>

            <h3 class="box-title">宿舍楼网管列表</h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-bordered table-hover text-center">
              <thead>
                <tr>
                  <th width="150px">宿舍楼</th>
                  <th width="80px">网管人数</th>
                  <th>负责网管</th>
                  <th width="100px">操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="building in results" :key="building.id">
                  <td>{{ building.name }}</td>
                  <td>{{ building.members.length }}</td>
                  <td>
                    <template v-if="selectMembers.building_id === building.id">
                      <select-members v-model="selectMembers.members"></select-members>
                    </template>
                    <template v-else>
                      <span v-if="building.members.length === 0">暂无</span>
                      <template v-else>
                        <span v-for="member in building.members" :key="member.id">{{ member.user.profile.name }} &nbsp;</span>
                      </template>
                    </template>
                  </td>
                  <td>
                    <template v-if="selectMembers.building_id === building.id">
                      <button @click="submitEdit" class="btn btn-xs btn-success"><i class="fa fa-check-circle-o"></i> 提交</button>
                      <button @click="setEdit(null)" class="btn btn-xs btn-danger"><i class="fa fa-times-circle-o"></i> 取消</button>
                    </template>
                    <button v-else @click="setEdit(building)" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> 修改</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

      </div>

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
import { mapGetters } from 'vuex'
import SelectMembers from './components/SelectMembers'
export default {
  components: {
    SelectMembers,
  },
  data () {
    return {
      form: {
        department_id: '',
        building_id: '',
      },
      results: null,
      loading: false,
      selectMembers: {
        building_id: '',
        members: [],
      },
    }
  },
  computed: {
    ...mapGetters([
      'departmentsOfService',
    ]),

    updateForm () {
      let members = []
      for (let member of this.selectMembers.members) {
        members.push(member.id)
      }
      return {
        members,
      }
    },
  },
  watch: {
    'form.department_id' () {
      if (this.form.building_id !== '') this.form.building_id = ''
      else this.fetchData()
    },
    'form.building_id': 'fetchData',
  },
  methods: {
    fetchData () {
      this.loading = true
      this.setEdit(null)
      this.$axios.get(
        '/api/building_member/',
        { params: this.form }
      ).then((response) => {
        this.results = response.data.data
        this.loading = false
      })
    },
    submitEdit () {
      swal({
        title: '是否确认提交？',
        text: '负责网管会收到该楼的报修提醒短信',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.put(
          `/api/building_member/${this.selectMembers.building_id}`,
          this.updateForm
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    setEdit (building) {
      if (building === null) {
        this.selectMembers.building_id = ''
        this.selectMembers.members = []
      } else {
        this.selectMembers.building_id = building.id
        this.selectMembers.members = building.members.concat()
      }
    },
  },
}
</script>
