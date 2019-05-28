<template>
  <div class="modal" ref="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            @click="closeModal"
          >
            <span aria-hidden="true">×</span>
          </button>

          <h4 class="modal-title">
            <slot name="title"></slot>
          </h4>
        </div>

        <div class="modal-body">
          <slot></slot>
        </div>

        <div class="modal-footer">
          <slot name="footer">
            <button
              type="button"
              class="btn btn-default"
              @click="closeModal"
            >
              关闭
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },
  watch: {
    show (val) {
      if (val) {
        $(this.$refs.modal).modal('show')
      } else {
        $(this.$refs.modal).modal('hide')
      }
    },
  },
  mounted () {
    $(this.$refs.modal).modal({
      backdrop: 'static',
    })
  },
  methods: {
    closeModal () {
      this.$emit('update:show', false)
    },
  },
}
</script>
