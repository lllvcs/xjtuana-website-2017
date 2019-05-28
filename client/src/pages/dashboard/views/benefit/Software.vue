<template>
  <div class="col-xs-12">
    <div class="text-muted row">
      <template v-if="loading">
        <p>加载中……</p>
      </template>

      <template v-else-if="isFailed">
        <p>加载失败，软件下载仅在校内/校园网可用</p>
      </template>

      <template v-else>
        <p class="col-xs-6">
          <span>总大小：</span>
          <span>{{ displayFilesize(results.total_filesize) }}</span>
          <span>（</span>
          <span class="hidden-xs">本地缓存：</span>
          <span>{{ displayFilesize(results.total_local_filesize) }}</span>
          <span>）</span>
        </p>

        <p class="col-xs-6 text-right">
          <span>更新时间：</span>
          <span>{{ displayTimestamp(results.generate_ts) }}</span>
        </p>
      </template>
    </div>

    <SoftwareBox
      v-for="(val, key) in results.data"
      :key="key"
      :title="key"
      :loading="loading"
      :data="val"
      @detail="showDetail"
    />

    <Modal v-if="detail" :show.sync="showModal">
      <template slot="title">{{ detail.name_display }}</template>
      <p>软件名称：{{ detail.name }}</p>
      <p>版本：{{ detail.version }}</p>
      <p>简介：{{ detail.intro }}</p>
      <p>文件下载：</p>
      <ul>
        <li v-for="file in detail.files" :key="file.hash.sha1" class="file-item">
          <a :href="file.url" target="_blank">{{ file.filename }}</a> ({{ displayFilesize(file.filesize) }})
          <ul class="list-unstyled text-muted">
            <li v-if="file.hash.sha1">SHA1: {{ file.hash.sha1 }}</li>
            <li v-if="file.update_ts">Updated at: {{ displayTimestamp(file.update_ts) }}</li>
          </ul>
        </li>
      </ul>
    </Modal>
  </div>
</template>

<script>
import Modal from '@/pages/dashboard/components/widgets/Modal'
import SoftwareBox from './components/SoftwareBox'
import moment from '@/plugins/moment'

export default {
  components: {
    Modal,
    SoftwareBox,
  },
  data () {
    return {
      loading: true,
      results: {
        data: {},
      },
      detail: null,
      showModal: false,
      isFailed: false,
    }
  },
  created () {
    this.getSoftware()
  },
  methods: {
    getSoftware () {
      this.loading = true
      this.isFailed = false

      this.$axios.get(
        'https://dl.xjtuana.com/index.json'
      ).then((response) => {
        this.results = response.data
      }).catch(() => {
        this.isFailed = true
      }).finally(() => {
        this.loading = false
      })
    },
    showDetail () {
      this.detail = arguments[0]
      this.showModal = true
    },
    displayFilesize (bytes) {
      if (bytes === 0) return '0 B'
      let k = 1024
      let sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
      let i = Math.floor(Math.log(bytes) / Math.log(k))
      return (bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i]
    },
    displayTimestamp (ts) {
      return moment.unix(ts).format('YYYY-MM-DD HH:mm:ss')
    },
  },
}
</script>

<style type="text/css" lang="scss" scoped>
.file-item {
  word-break: break-all;
}
</style>
