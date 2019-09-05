<template>
  <div id="app">
    <div class="content">
      <transition :name="transitionName">
        <keep-alive>
          <router-view></router-view>
        </keep-alive>
      </transition>
    </div>
  </div>
</template>

<script>
import tarifScope from './components/tarifScope.vue'

export default {
  name: 'app',
  data () {
    return {
      transitionName: 'slide'
    }
  },
  components: {
    tarifScope
  },
  watch: {
    '$route' (to, from) {
      const toPath = to.path
      const fromPath = from.path

      if (fromPath === '/tarif_detail') {
        return this.transitionName = 'slide-left'
      } else if (fromPath === '/tarif_scope' && toPath === '/') {
        return this.transitionName = 'slide-left'
      } else {
        return this.transitionName = 'slide'
      }
    }
  }
}
</script>

<style lang="scss" src="./styles/styles.scss"></style>
