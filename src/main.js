import Vue from 'vue'
import router from './router/index'

import App from './App.vue'
import Mixin from './mixins/mixin'

Vue.config.productionTip = false

Vue.mixin(Mixin)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
