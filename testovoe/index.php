<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

    <div id="app">
        <div class="tarif-wrapper" v-for="(tarif, index) in response.data.tarifs">
            <div class="tarif-header">Тариф "{{ tarif.title }}"</div>
            <div class="tarif-description tarif-description--rigth-arrow">
                <div class="rigth-arrow"></div>
                <div class="tarif-speed" v-bind:class="{ earth: tarif.title.toLowerCase().indexOf('земля') > -1,
                                                         water: tarif.title.toLowerCase().indexOf('вода') > -1,
                                                         fire: tarif.title.toLowerCase().indexOf('огонь') > -1 }">
                                                         {{ tarif.speed }} Мбит/с
                </div>
                <div class="tarif-payment">{{ prices[index].min }} - {{ prices[index].max }} ₽/мес</div>
                <div class="tarif-free-options">
                    <div class="tarif-free-options__item" v-for="i in tarif.free_options">{{ i }}</div>
                </div>
            </div>
            <div class="tarif-link">
                <a v-bind:href="tarif.link" noopener target="_blank" class="tarif-link__a">узнать подробнее на сайте sknt.ru</a>
            </div>
            <tarifs-scope v-bind:tarifs="tarif.tarifs"
                          v-bind:tarif-title="tarif.title"
            ></tarifs-scope>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
    <script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>
    <script>
        Vue.component('tarifs-scope', {
            props: {
                'tarifs': Array, 
                'tarifTitle': String
            },
            data () {
                return {
                    highestPrice: 0
                }
            },
            methods: {
                monthText (count) {
                    count = parseInt(count);
                    if (count === 1) return 'Месяц'
                    if (count > 1 && count < 5) return 'Месяца'
                    if (count >= 5) return 'Месяцев'
                },
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
            mounted () {
                let high = 0;
                let mass = [];
                let i = 0;

                for (i; i < this.tarifs.length; i++) {
                    mass.push(parseInt(this.tarifs[i].price)/parseInt(this.tarifs[i].pay_period))
                }

                this.highestPrice = Math.max(...mass)
            },
            template: `<div class="tarifs-scope">
                            <div class="tarifs-scope__head left-arrow-container">
                                Тариф "{{ tarifTitle }}"
                                <div class="left-arrow"></div>
                            </div>
                            <div class="tarif" v-for="i in tarifSorted(tarifs)"> 
                                <div class="tarif-header tarif-head">{{ i.pay_period }} {{ monthText(i.pay_period) }}</div>
                                <div class="tarif-description tarif-description--no-bb">
                                    <div class="tarif-monthly-payment" >{{ i.price / i.pay_period }} ₽/мес</div>
                                    <div class="tarif-total-price">разовый платеж - {{ i.price }} ₽</div>
                                    <div class="tarif-discount" v-if="parseInt(i.pay_period) !== 1">скидка - {{ highestPrice * i.pay_period - i.price }} ₽</div>
                                </div>
                                <tarif-detail
                                    v-bind:tarif="i"
                                    v-bind:monthlyPayment="i.price / i.pay_period"
                                ></tarif-detail>
                            </div>
                        </div>`
        })
        Vue.component('tarif-detail', {
            props: ['tarif', 'monthlyPayment'],
            data () {
                return {

                }
            },
            computed: {
                dateCurrent () {
                    let offsetTime = this.tarif.new_payday.slice(-5)
                    let theTime = this.tarif.new_payday.slice(0, this.tarif.new_payday.length - 5)
                    let total = 0;
                    
                    // if (offsetTime[0] === '+') {
                    //     total = new Date(theTime) + 
                    // }
                    
                    return offsetTime
                }
            },
            filters: {
                monthText (value) {
                    count = parseInt(value);
                    if (count === 1) return 'Месяц'
                    if (count > 1 && count < 5) return 'Месяца'
                    if (count >= 5) return 'Месяцев'
                }
            },
            template: `<div class="tarif-detail">
                            <div class="tarifs-scope__head left-arrow-container">
                                Выбор тарифа
                                <div class="left-arrow"></div>
                            </div>
                            <div class="tarif-detail-container">
                                <div class="tarif-header">Тариф "{{ tarif.title }}"</div>
                                <div class="tarif-description  tarif-description--no-bb">
                                    <div class="padding">
                                        <div class="tarif-description-row text-bold">Период оплаты - {{ tarif.pay_period }} {{ tarif.pay_period | monthText }}</div>
                                        <div class="tarif-description-row text-bold">{{ monthlyPayment }} ₽/мес</div>
                                    </div>
                                    <div class="padding">
                                        <div class="tarif-description-row text-grey">разовый платёж - {{ tarif.price }}</div>
                                        <div class="tarif-description-row text-grey">со счёта снимется - {{ tarif.price }}</div>
                                    </div>
                                    <div class="padding">
                                        <div class="tarif-description-row text-grey">вступит в силу - сегодня</div>
                                        <div class="tarif-description-row text-grey">активно до - {{ dateCurrent }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>`
        })

        let a = new Vue({
            el: '#app',
            data: {
                response: {},
            },
            computed: {
                prices () {
                    let prices = [];
                    let tarifs = this.response.data.tarifs;
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
            created() {
                axios.get('./data.json')
                     .then(response => {
                         this.response = response
                         console.log(response)
                        })
            },
        })

        
    </script>
</body>
</html>

<?php
    $ch = curl_init("https://www.sknt.ru/job/frontend/data.json");
    $fp = fopen("data.json", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
?>