<template>
<div>
  <form id="form">
    <WeuiCellsTitle>
      <i class="fa fa-fw fa-id-card-o"></i>

      <span>报修人信息</span>
    </WeuiCellsTitle>

    <WeuiCells type="form">
      <WeuiCell>
        <WeuiCellHeader>
          <span class="weui-label">
            姓名
          </span>
        </WeuiCellHeader>

        <WeuiCellBody>{{ form.name }}</WeuiCellBody>
      </WeuiCell>

      <WeuiCell>
        <WeuiCellHeader>
          <span class="weui-label">
            学号
          </span>
        </WeuiCellHeader>

        <WeuiCellBody>{{ form.stuid }}</WeuiCellBody>
      </WeuiCell>

      <WeuiCell>
        <WeuiCellHeader>
          <span class="weui-label">
            性别
          </span>
        </WeuiCellHeader>

        <WeuiCellBody>{{ form.gender }}</WeuiCellBody>
      </WeuiCell>
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-fw fa-mobile"></i>

      <span>报修联系方式</span>
    </WeuiCellsTitle>

    <WeuiCells type="form">
      <WeuiInput
        v-model="form.mobile"
        type="number"
        pattern="REG_MOBILE"
        required
        placeholder="请输入手机号"
        emptyTips="请输入手机号"
        notMatchTips="请正确的手机号"
      >
        手机
      </WeuiInput>

      <WeuiInput
        v-model="form.qq"
        type="number"
        pattern="REG_QQ"
        required
        placeholder="请输入QQ号（选填）"
        notMatchTips="请正确的QQ号"
      >
        QQ
      </WeuiInput>

      <WeuiInput
        v-model="form.wechat"
        pattern="REG_WECHAT"
        required
        placeholder="请输入微信号（选填）"
        notMatchTips="请正确的微信号"
      >
        微信
      </WeuiInput>
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-fw fa-map-marker"></i>

      <span>报修详情</span>
    </WeuiCellsTitle>

    <WeuiCells type="form">
      <WeuiSelect
        v-model="campus_selection.id"
        place="after"
      >
        <label
          slot="before"
          class="weui-label"
        >
          校区
        </label>

        <option
          v-for="campus in campuses"
          :key="campus.id"
          :value="campus.id"
        >
          {{ campus.name }}
        </option>
      </WeuiSelect>

      <WeuiSelect
        v-model="form.building"
        place="after"
        required
        emptyTips="请选择宿舍楼"
      >
        <label
          slot="before"
          class="weui-label"
        >
          宿舍楼
        </label>

        <option
          v-for="building in campus_selection.buildings"
          :key="building.id"
          :value="building.id"
        >
          {{ building.name }}
        </option>
      </WeuiSelect>

      <WeuiInput
        v-model="form.room"
        pattern="REG_ROOM"
        required
        placeholder="请输入房间号"
        emptyTips="请输入房间号"
        notMatchTips="房间号过长，请输入正确的房间号"
      >
        房间号
      </WeuiInput>

      <WeuiSelect
        v-model="form.service"
        place="after"
        required
        emptyTips="请选择优先处理方式"
      >
        <label
          slot="before"
          class="weui-label"
        >
          处理方式
        </label>

        <option
          v-for="service in services"
          :key="service.id"
          :value="service.id"
        >
          {{ service.name }}
        </option>
      </WeuiSelect>
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-fw fa-file-text-o"></i>

      <span>故障描述</span>
    </WeuiCellsTitle>

    <WeuiCells type="form">
      <WeuiTextarea
        v-model="form.detail"
        placeholder="请简单描述您遇到的网络故障，如：出现情况、错误代码、可能原因等。请注意：联通和电信的故障不在我们的负责范围之内。"
        emptyTips="请输入故障描述"
        rows="6"
        required
      />
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-fw fa-tags"></i>

      <span>出现问题的范围</span>
    </WeuiCellsTitle>

    <WeuiCells type="radio">
      <WeuiInputRadio :value="false" v-model="isMultiple">宿舍只有我个人网络出现问题</WeuiInputRadio>

      <WeuiInputRadio :value="true" v-model="isMultiple">宿舍多人网络出现问题</WeuiInputRadio>
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-fw fa-tags"></i>

      <span>是否有错误代码/错误提示</span>
    </WeuiCellsTitle>

    <WeuiCells type="radio">
      <WeuiInputRadio :value="false" v-model="hasErrorNote">我什么都不知道</WeuiInputRadio>

      <WeuiInputRadio :value="true" v-model="hasErrorNote">知道错误代码/错误提示</WeuiInputRadio>
      <WeuiTextarea
        v-if="hasErrorNote"
        v-model="errorNote"
        placeholder="输入错误代码/错误提示的具体内容"
        emptyTips="请输入错误代码/错误提示的具体内容"
        rows="1"
        required
      />
    </WeuiCells>
  </form>

  <WeuiCellsTitle>
    <i class="fa fa-fw fa-warning"></i>
    注意事项
  </WeuiCellsTitle>

  <WeuiCells-tips>
    <p>1. 报修单一旦提交，系统将通知相关网管进行处理；</p>
    <p>2. 若报修成功后撤销此单，则30分钟内无法再次进行报修；</p>
    <p>3. 网管会成员都是本校学生，志愿为大家解决问题，若处理不及时还请见谅，希望同学们能够理解。 </p>
  </WeuiCells-tips>

  <WeuiButtonArea>
    <WeuiButton type="primary" @click="onSubmit">提交</WeuiButton>
  </WeuiButtonArea>

  <br>
</div>
</template>

<script>
import { mapState } from 'vuex'
const regexp = {
  MOBILE: /^1[3-9]\d{9}$/,
  QQ: /^$|^[1-9]\d{4,9}$/,
  WECHAT: /^$|^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$/,
  ROOM: /^.{1,10}$/,
}
export default {
  data () {
    return {
      form: null,
      campus_selection: null,

      errorNote: null,
      isMultiple: false,
      hasErrorNote: true,
    }
  },
  computed: {
    ...mapState([
      'user',
      'campuses',
      'services',
    ]),

    normalizedForm () {
      const normalizedForm = { ...this.form }
      const isMultipleDetail = this.isMultiple ? '宿舍多人出现问题' : '宿舍个人出现问题'
      const hasErrorNoteDetail = this.hasErrorNote ? `错误代码/提示${this.errorNote}` : '没有错误代码/提示'
      normalizedForm.detail = `${isMultipleDetail};${hasErrorNoteDetail};${this.form.detail}`
      return normalizedForm
    },
  },
  created () {
    this.campus_selection = this.campuses.find(campus => {
      return campus.buildings.findIndex(building => building.name === this.user.profile.dorm_building) > -1
    })
    this.form = {
      name: this.user.profile.name,
      stuid: this.user.profile.stuid,
      gender: this.user.profile.gender,
      mobile: this.user.profile.mobile,
      qq: this.user.profile.qq,
      wechat: this.user.profile.wechat,
      building: this.user.profile.building.id,
      room: this.user.profile.dorm_room,
      service: 1,
      detail: null,
    }
  },
  mounted () {
    this.$weui.form.checkIfBlur('#form', { regexp })
  },
  methods: {
    onSubmit () {
      this.$weui.form.validate('#form', error => {
        if (error === null) {
          let dialog = this.$weui.dialog({
            title: '是否确认提交？',
            content: '提交报修后，将通知负责网管与您联系。',
            buttons: [{
              label: '取消',
              type: 'default',
              onClick: () => null,
            }, {
              label: '确定',
              type: 'primary',
              onClick: () => { dialog.hide(this.createOrder) },
            }],
          })
        }
      }, { regexp })
    },
    async createOrder () {
      let response = await this.$axios.post('/api/order', this.normalizedForm)
      let callback = () => {
        if (response.data.data.id) {
          this.routeToOrder(response.data.data.id)
        }
      }
      if (response.data.status) {
        this.$weui.alert(response.data.message, callback)
      } else {
        this.$weui.toast(response.data.message, callback)
      }
    },
    routeToOrder (id) {
      this.$router.push({ name: 'order.show', params: { id } })
    },
  },
}
</script>
