<template>
  <div class="swiper-container" id="banner">

    <!-- 幻灯片开始 -->
    <div class="swiper-wrapper">
      <!-- 第一页开始 -->
      <div class="swiper-slide content-center" id="banner-1">
        <div class="banner-text-wrapper text-center">
          <p class="banner-subtitle">
              {{$t("swiper.line_1") }}
          </p>
          <h1 class="banner-title" data-swiper-parallax="-500">
              {{$t("swiper.line_2") }}
          </h1>
          <p class="banner-text" data-swiper-parallax="-1200">
              {{$t("swiper.line_3") }}
          </p>
          <p class="banner-text" data-swiper-parallax="-2000">
              {{$t("swiper.line_4") }}
          </p>
          <p class="banner-text" data-swiper-parallax="-2700">
              {{$t("swiper.line_5") }}
          </p>
        </div>
      </div>
      <!-- 第一页结束 -->

      <!-- 第二页开始 -->
      <div class="swiper-slide content-center" id="banner-2">
        <div class="banner-text-wrapper text-center">
          <p class="banner-subtitle">
              {{$t("swiper.line_6") }}
          </p>
          <h1 class="banner-title" data-swiper-parallax="-500">
              {{$t("swiper.line_7") }}
          </h1>
          <p class="banner-text" data-swiper-parallax="-1000">
              {{$t("swiper.line_8") }}
          </p>
          <p class="banner-text" data-swiper-parallax="-1500">
              {{$t("swiper.line_9") }}
          </p>
          <p class="banner-text" data-swiper-parallax="-2000">
              {{$t("swiper.line_10") }}
          </p>
          <p class="banner-text" data-swiper-parallax="-2500">
              {{$t("swiper.line_11") }}
          </p>
        </div>
      </div>
      <!-- 第二页结束 -->

    </div>
    <!-- 幻灯片结束 -->

    <!-- 分页器 -->
    <div class="swiper-pagination"></div>

    <!-- 导航按钮 -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</template>

<script>
import Swiper from 'swiper'
export default {
  data () {
    return {
      herf: window.location.href.split('/'),
    }
  },
  created () {
    if (this.herf['3'] === 'en' || this.herf['3'] === 'zh') {
      this.$i18n.locale = this.herf['3']
      window.localStorage.setItem('locale', this.herf['3'])
    }
  },
  mounted () {
    /* eslint-disable no-new */
    new Swiper('.swiper-container', {

      direction: 'horizontal',
      autoplay: 5000, // Autoplay
      speed: 700, // Speed
      loop: true, // Loop
      parallax: true, // Parallax
      // mousewheelControl: true,    // Mousewhell

      pagination: '.swiper-pagination',

      nextButton: '.swiper-button-next',
      prevButton: '.swiper-button-prev',

      // 需要引入swiper.animate
      // onInit: function(swiper){
      //     // 隐藏动画元素
      //     swiperAnimateCache(swiper);
      //     // 初始化完成开始动画
      //     swiperAnimate(swiper);
      // },
      // /* 每个slide切换时运行当前slide动画。切换开始时调用每个slide切换时运行当前slide动画。
      //     切换开始时调用切换结束时调用onSlideChangeStart，切换结束时调用onSlideChangeEnd */
      // onSlideChangeEnd: function(swiper){
      //     swiperAnimate(swiper);
      // }
    })
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

    #banner-1 {
      background-image: url('~@/assets/img/home_banner_01.jpg');
    }

    #banner-2 {
      background-image: url('~@/assets/img/home_banner_02.jpg');
    }
  }
</style>
