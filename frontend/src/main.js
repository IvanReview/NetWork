import Vue from 'vue'
import Vuelidate from 'vuelidate'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import axios from 'axios'
import VueAxios from 'vue-axios'
import dateFilter from "./filters/date_filter";
import './assets/style.sass'
import Toasted from 'vue-toasted';


// you can also pass options, check options reference below
Vue.use(Toasted, {
  theme: "bubble",
  position: "top-left",
  duration : 5000,
})


// global register

Vue.use(VueAxios, axios)
Vue.use(Vuelidate)

Vue.config.productionTip = false
Vue.filter('dateFilter', dateFilter)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
