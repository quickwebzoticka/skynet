<template>
  <div class="tarifs">
    <div class="tarif-wrapper" v-for="(tarif, index) in response" :key="index">
        <router-link class="router-link flex-container"
                     :to="{ name: 'tarif_scope', params: { tarifs: tarif.tarifs, tarifTitle:  tarif.title} }"
                     >
            <div class="tarif-header">Тариф "{{ tarif.title }}"</div>
            <div class="tarif-description tarif-description--rigth-arrow">
                <div class="rigth-arrow"></div>
                <div class="tarif-speed" :class="{ earth: tarif.title.toLowerCase().indexOf('земля') > -1,
                                                            water: tarif.title.toLowerCase().indexOf('вода') > -1,
                                                            fire: tarif.title.toLowerCase().indexOf('огонь') > -1 }">
                                                            {{ tarif.speed }} Мбит/с
                </div>
                <div class="tarif-payment">{{ prices[index].min }} - {{ prices[index].max }} ₽/мес</div>
                <div class="tarif-free-options">
                    <div class="tarif-free-options__item" v-for="(i, index) in tarif.free_options" :key="index">{{ i }}</div>
                </div>
            </div>
        </router-link>
        <div class="tarif-link">
            <a :href="tarif.link" noopener target="_blank" class="tarif-link__a">узнать подробнее на сайте sknt.ru</a>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
    data () {
        return {
            response: []
        }
    },
    computed: {
        prices () {
            let prices = [];
            let tarifs = this.response;
            let i = 0;

            for (i; i < tarifs.length; i++) {
                let j = 0;
                let payments = [];
                let minMax = {min: 0, max: 0};
                for (j; j < tarifs[i].tarifs.length; j++) {
                    payments.push(parseInt(tarifs[i].tarifs[j].price)/parseInt(tarifs[i].tarifs[j].pay_period))
                }
                minMax.min = Math.min(...payments)
                minMax.max = Math.max(...payments)
                prices.push(minMax)
            }
            return prices;
        }
    },
    mounted() {
        axios.get('./data.json')
            .then(response => {
                this.response = response.data.tarifs
            })
    },
}
</script>

<style>

</style>