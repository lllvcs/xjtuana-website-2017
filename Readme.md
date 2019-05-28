# 社团网站2017 丨 XJTUANA Website 2017

开发人员:

- meteorlxy

---

## 简介 Introduction

- 主要架构
    - PHP框架：Laravel
    - 前端框架：Vue.js

- Web
    - [Vue](https://cn.vuejs.org/v2/guide/) - [Github](https://github.com/vuejs/vue)
    - [Vuex](https://vuex.vuejs.org/zh-cn/) - [Github](https://github.com/vuejs/vuex)
    - [Vue-router](https://router.vuejs.org/zh-cn/) - [Github](https://github.com/vuejs/vue-router)
    - [Vee-validate](http://vee-validate.logaretm.com) - [Github](https://github.com/baianat/vee-validate)
    - [Vue-select](http://sagalbot.github.io/vue-select/) - [Github](https://github.com/sagalbot/vue-select)
    - [jQuery](http://www.jquery123.com/) - [Github](https://github.com/jquery/jquery)
    - [Bootstrap](http://www.bootcss.com/) - [Github](https://github.com/twbs/bootstrap)
    - [Bootstrap-validator](http://1000hz.github.io/bootstrap-validator) - [Github](https://github.com/1000hz/bootstrap-validator)
    - [Bootstrap-daterangepicker](http://www.daterangepicker.com/) - [Github](https://github.com/dangrossman/bootstrap-daterangepicker)
    - [Font Awesome](http://fontawesome.io/icons/) - [Github](https://github.com/FortAwesome/Font-Awesome)
    - [AdminLTE](http://almsaeedstudio.com/) - [Github](https://github.com/almasaeed2010/AdminLTE)
    - [Hover.css](http://ianlunn.co.uk/) - [Github](https://github.com/IanLunn/Hover)
    - [Animate.css](https://github.com/daneden/animate.css) - [Github](https://github.com/daneden/animate.css)
    - Buttons.css - [Github](https://github.com/alexwolfe/Buttons)
    - [Datatables](https://www.datatables.net/)
    - [Swiper](http://www.swiper.com.cn/) - [Github](https://github.com/nolimits4web/swiper/)
    - [Echarts](http://echarts.baidu.com/) - [Github](https://github.com/ecomfe/echarts)
    - [Sweetalert2](https://limonte.github.io/sweetalert2) - [Github](https://github.com/limonte/sweetalert2)
    - Axios - [Github](https://github.com/mzabriskie/axios)
    - [Moment.js](http://momentjs.com/docs/) - [Github](https://github.com/moment/moment)
    - pinyin - [Github](https://github.com/hotoo/pinyin)  (Webpack打包有问题，暂时移除)

- PHP
    - laravel/laravel - [Github](https://github.com/laravel/laravel)
    - predis/predis
    - [xjtuana/laravel-xjtuana](https://github.com/xjtuana-dev/laravel-xjtuana)

---

## 开发指南

#### 1. 克隆本仓库并切换到dev分支

```bash
git clone https://git.xjtuana.com/xjtuana/website-2017.git
cd website-2017
```

#### 2. 使用composer安装第三方依赖

注意PHP需要开启相关扩展：

- `php-curl`
- `php-dom`
- `php-iconv`
- `php-pdo_mysql`
- `php-simplexml`
- `php-soap`
- `php-sockets`
- `php-zip`

```bash
composer install
```

#### 3. 复制.env文件

```bash
cp .env.example .env
```

#### 4. 生成APP_KEY

```bash
php artisan key:generate
```

#### 5. 配置环境变量

在`.env`文件中配置相关的数据库连接等

最下方的`XJTUANA`开头的一些配置项是关于使用学校相关接口的，想要使用向部长申请。这里只公布CAS代理供登录使用：

```
XJTUANA_CAS_PROXY_VERSION=v1
XJTUANA_CAS_PROXY_PROTOCOL=https
XJTUANA_CAS_PROXY_HOSTNAME=ana.xjtu.edu.cn
XJTUANA_CAS_PROXY_PREFIX=/casproxy
```

#### 6. 配置数据库

前往[database-backup](https://git.xjtuana.com/xjtuana/database-backup)仓库中，将其中的sql文件导入开发环境的数据库中即可

> 注意，数据中涉及同学的个人隐私，请!务必!注意!保密!

#### 7. 启动Laravel

通过artisan命令，即可启动本地开发服务器

```sh
php artisan serve
```

#### 8. 前端开发

新开一个命令行窗口，使用`yarn`安装依赖：

```bash
yarn install
```

开发前端代码，监听变化：

```bash
yarn run dev
```

开发完成后，构建线上部署文件时使用`build`命令：

```bash
yarn run build
```

> 目前，构建后的前端资源文件`public/static`不保存在代码仓库中

PS：可以在`package.json`的`scripts`字段下查看所有可用的脚本

#### 8. 代码提交和更新等问题

- `master`分支禁止直接`push`，所以更新的代码要`push`到`dev`分支
- `dev`分支随时可能会有其他人的更新，你可能需要经常使用`git pull`等操作将更新合并到你的本地仓库中。注意如果你本地仓库和远程仓库不一致，直接使用`git pull`可能会因为冲突而失败，可以用`git fetch`, `git merge`等方式处理冲突。具体解决办法自己去学。

#### PS

遇到问题鼓励自己先去尝试解决，百度不行就google，网上相关资料非常多。

也可以先和同学请教讨论，避免盲目找资料不知道方向。
