import Vue from 'vue'
import VueRouter from 'vue-router'
import tarifscope from '../components/tarifScope'
import tarifs from '../components/tarifs'
import tarifdetail from '../components/tarifDetail'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {
          path: '/',
          name: 'tarifs',
          component: tarifs
        },
        {
          path: '/tarif_scope',
          name: 'tarif_scope',
          component: tarifscope,
          props: true
        },
        {
          path: '/tarif_detail',
          name: 'tarif_detail',
          component: tarifdetail,
          props: true
        }
      ]
})
