export default {
    methods: {
        monthText (count) {
            count = parseInt(count);
            if (count === 1) return 'Месяц'
            if (count > 1 && count < 5) return 'Месяца'
            if (count >= 5) return 'Месяцев'
        }
    }
}