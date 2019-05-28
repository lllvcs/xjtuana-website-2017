<template>
  <VueSelect
    v-model="value"
    label="id"
    :options="members"
    :get-option-label="getOptionLabel"
    placeholder="输入网管姓名以查找"
    no-match-text="没有匹配结果"
    multiple
  />
  <!--<VueSelect -->
  <!--  v-model="value"-->
  <!--  label="id"-->
  <!--  :options="members"-->
  <!--  :get-option-label="getOptionLabel"-->
  <!--  :filter-function="filterFunction"-->
  <!--  placeholder="输入网管姓名以查找" -->
  <!--  no-match-text="没有匹配结果" -->
  <!--  multiple />-->
</template>

<script>
import { mapState } from 'vuex'
// import VueSelect from 'vue-select'
import VueSelect from '../../../components/widgets/vue-select/index'
/* pinyin 在production模式打包出现问题，暂时移除 */
// import pinyin from 'pinyin'
export default {
  components: {
    VueSelect,
  },
  props: {
    value: {
      type: Array,
      default: () => [],
    },
  },
  computed: {
    ...mapState([
      'departments',
    ]),
    members () {
      let members = []
      for (let department of this.departments) {
        members = members.concat(department.members)
      }
      return members
    },
  },
  methods: {
    getOptionLabel (option) {
      return option.user.profile.name
    },
    /* pinyin 在production模式打包出现问题，暂时移除 */
    // filterFunction(option, label, search) {
    //   let labelPinyin = pinyin(label, {
    //     style: pinyin.STYLE_FIRST_LETTER,
    //     heteronym: false
    //   })
    //   let pinyinString = '';
    //   for(let hanzi of labelPinyin){
    //     pinyinString += hanzi[0]
    //   }
    //   return label.toLowerCase().indexOf(search.toLowerCase()) > -1 || pinyinString.toLowerCase().indexOf(search.toLowerCase()) > -1
    // },
  },
}
</script>
