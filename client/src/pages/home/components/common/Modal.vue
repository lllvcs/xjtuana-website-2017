<template>
  <div class="modal fade content-center" :id="id" tabindex="-1" role="dialog" style="display:none">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div v-if="title" class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ title }}</h4>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <div class="text-center">
            <button v-if="hasCancel" type="button" class="btn btn-danger" data-dismiss="modal">{{ cancelText }}</button>
            <button v-if="hasConfirm" type="button" class="btn btn-success">{{ confirmText }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
    title: {
      type: String,
    },
    hasCancel: {
      type: Boolean,
      default: false,
    },
    cancelText: {
      type: String,
      default: '关闭',
    },
    hasConfirm: {
      type: Boolean,
      default: false,
    },
    confirmText: {
      type: String,
      default: '确认',
    },
  },

  data () {
    return {
    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>
  #banner {
    height: 400px;
    .banner-text-wrapper {
      background: rgba(0,0,0,0.5);
      padding: 20px 40px;
      color: #fff;
      .banner-title {
        margin-bottom: 25px;
      }
      .banner-subtitle {
        font-size: 24px;
        margin: {
          top: 20px;
          bottom: 10px;
        }
      }
      .banner-text {
        font-size: 18px;
        margin: {
            top: 15px;
            bottom: 15px;
        }
      }
    }

    // #banner-1 {
    //     background: #fad64a;
    //     background: radial-gradient(#fff569, #ffca22);
    // }

    // #banner-2 {
    //     background: #a2cd5a;
    //     background: radial-gradient(#a2cd5a, #9acd32);
    // }

    // #banner-3 {
    //     * {color: #fff;}
    //     background: #e67e22;
    //     background: radial-gradient(#e67e22, #d35400);
    // }
  }
</style>
