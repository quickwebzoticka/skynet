<template>
  <div class="tarifs-scope">
        <router-link class="router-link" to="/">
            <div class="tarifs-scope__head left-arrow-container">
                Тариф "{{ tarifTitle }}"
                <div class="left-arrow"></div>
            </div>
        </router-link>
        <div class="tarifs">
            <div class="tarif" v-for="(i, index) in tarifSorted(tarifs)" :key="index"> 
                <div class="tarif-header tarif-head">{{ i.pay_period }} {{ monthText(i.pay_period) }}</div>
                <router-link class="router-link flex-container"
                            :to="{ name: 'tarif_detail', params: { tarif: i, monthlyPayment: i.price / i.pay_period } }">
                    <div class="tarif-description tarif-description--no-bb tarif-description--rigth-arrow">
                        <div class="rigth-arrow"></div>
                        <div class="tarif-monthly-payment" >{{ i.price / i.pay_period }} ₽/мес</div>
                        <div class="tarif-total-price">разовый платеж - {{ i.price }} ₽</div>
                        <div class="tarif-discount" v-if="parseInt(i.pay_period) !== 1">скидка - {{ highestPrice * i.pay_period - i.price }} ₽</div>
                    </div>
                </router-link>
            </div>
        </div>
  </div>
</template>

<script>
export default {
    data () {
        return {
            highestPrice: 0,
            tarifs: false,
            tarifTitle: false
        }
    },
    methods: {
        tarifSorted (arr) {
            let i = 0;
            let endI = arr.length - 1;

            for (i, endI; i < endI; i++) {
                let wasSwap = false;
                let j = 0;
                let endJ = endI - i;

                for (j, endJ; j < endJ; j++) {
                    if (+arr[j].pay_period > arr[j + 1].pay_period) {
                        [arr[j], arr[j + 1]] = [arr[j + 1], arr[j]];
                        wasSwap = true;
                    }
                }
                if (!wasSwap) break;
            }
            return arr;
        }
    },
    activated () {
        this.tarifs = this.$route.params.tarifs
        this.tarifTitle = this.$route.params.tarifTitle

        let high = 0;
        let mass = [];
        let i = 0;

        for (i; i < this.tarifs.length; i++) {
            mass.push(parseInt(this.tarifs[i].price)/parseInt(this.tarifs[i].pay_period))
        }

        this.highestPrice = Math.max(...mass)
    }
}
</script>
