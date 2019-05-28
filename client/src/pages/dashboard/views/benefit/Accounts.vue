<template>
  <div class="col-xs-12">
    <div class="box box-solid">
      <div class="box-header with-border">
        <i class="fa fa-fw fa-user-secret"></i>

        <h3 class="box-title">会员帐号</h3>
      </div>

      <div class="box-body">
        <MdAccounts />
      </div>
    </div>
  </div>
</template>

<script>
import MdAccounts from '@/assets/md/Accounts'
export default {
  components: {
    MdAccounts,
  },
}
</script>
