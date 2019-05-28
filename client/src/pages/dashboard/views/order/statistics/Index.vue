<template>
<div>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <router-link :to="{ name: 'order_statistics.daily' }" tag="li" exact>
              <a>
                <i class="fa fa-bell"></i>
                <span> 每日报修量</span>
              </a>
            </router-link>

            <router-link :to="{ name: 'order_statistics.member' }" tag="li" exact>
              <a>
                <i class="fa fa-wrench"></i>
                <span> 运维工作量</span>
              </a>
            </router-link>

            <router-link :to="{ name: 'order_statistics.building' }" tag="li" exact>
              <a>
                <i class="fa fa-building"></i>
                <span> 宿舍楼报修量</span>
              </a>
            </router-link>
          </ul>

          <div class="tab-content">
            <TransitionFade appear>
              <router-view></router-view>
            </TransitionFade>
          </div>
        </div>

      </div>

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
import TransitionFade from '@/pages/dashboard/components/transitions/TransitionFade'
export default {
  components: {
    TransitionFade,
  },
}
</script>
