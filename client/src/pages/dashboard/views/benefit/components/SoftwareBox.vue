<template>
  <div class="box box-solid collapsed-box">
    <div class="box-header with-border" data-widget="collapse">
      <i class="fa fa-fw fa-plus" />

      <i
        v-if="loading"
        class="fa fa-fw fa-spinner fa-spin pull-right"
      />

      <h3 class="box-title">{{ title }}</h3>
    </div>

    <div
      v-if="data"
      class="box-body"
    >
      <ul>
        <li v-for="d in data" :key="d.name_display">
          <a
            class="software-element"
            @click="$emit('detail', d)"
          >
            {{ d.name_display }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
    loading: {
      type: Boolean,
      default: true,
    },
    title: {
      type: String,
      required: true,
    },
  },
}
</script>

<style type="scss" scope>
.software-element {
  cursor: pointer;
}
</style>
