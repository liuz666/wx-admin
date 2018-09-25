// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import VueResource from 'vue-resource';
// import $ from 'jquery';
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'
import 'font-awesome/css/font-awesome.css'
Vue.use(VueResource);

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
//流程： index.html->main.js->App.vue
//  1、 index.html是入口文件
//  2、 main.js 是核心文件，
//  3、 App.vue是根组件文件
