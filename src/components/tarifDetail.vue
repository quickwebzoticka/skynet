<template>
  <div class="tarif-detail">
      <router-link to="/tarif_scope" class="router-link">
        <div class="tarifs-scope__head left-arrow-container">
            Выбор тарифа
            <div class="left-arrow"></div>
        </div>
      </router-link>
      <div class="tarif-detail-container">
          <div class="tarif-header">Тариф "{{ tarif.title }}"</div>
          <div class="tarif-description">
              <div class="padding">
                  <div class="tarif-description-row text-bold">Период оплаты - {{ tarif.pay_period }} {{ tarif.pay_period | monthText }}</div>
                  <div class="tarif-description-row text-bold">{{ monthlyPayment }} ₽/мес</div>
              </div>
              <div class="padding">
                  <div class="tarif-description-row">разовый платёж - {{ tarif.price }} ₽</div>
                  <div class="tarif-description-row">со счёта снимется - {{ tarif.price }} ₽</div>
              </div>
              <div class="padding">
                  <div class="tarif-description-row text-grey">вступит в силу - сегодня</div>
                  <div class="tarif-description-row text-grey">активно до - {{ dateCurrent }}</div>
              </div>
          </div>
          <div class="tarif-detail__footer">
            <button class="sky-button">Выбрать</button>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  data () {
      return {
        monthlyPayment: false,
        tarif: false
      }
  },
  computed: {
    dateCurrent () {
      let offsetTime = this.tarif.new_payday.slice(-5)
      let theTime = this.tarif.new_payday.slice(0, this.tarif.new_payday.length - 5) * 1000
      let total = 0
      let operation = offsetTime[0]

      offsetTime = parseInt(offsetTime.substr(1,2)) * 60 * 60 * 1000
      
      if (operation === '+') {
        let time = theTime + offsetTime
        total = new Date(time)
      } else {
        let time = theTime - offsetTime
        total = new Date(time)
      }

      console.log(total)
      
      return `${('0' + total.getDate()).slice(-2)}.${('0' + (total.getMonth() + 1)).slice(-2)}.${total.getFullYear()}`
    }
  },
  filters: {
    monthText (value) {
      if (parseInt(value) === 1) return 'Месяц'
      if (parseInt(value) > 1 && parseInt(value) < 5) return 'Месяца'
      if (parseInt(value) >= 5) return 'Месяцев'
    }
  },
  activated () {
    this.tarif = this.$route.params.tarif
    this.monthlyPayment = this.$route.params.monthlyPayment
  }
}
</script>

<style>

</style>