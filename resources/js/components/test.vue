<template>
    <div>
        <h2>Тестовый компонент</h2>
        <div v-for="person in persons">
            <span>{{ person.name }}</span><br>
            <span>Возраст: {{ person.age }}</span>
            <hr>
        </div>
    </div>
</template>

<script>
export default {
    name: "test",

    data() {
        return {
            persons: [
                {
                    name: 'Vasya',
                    age: 20,
                },
                {
                    name: 'Dmitrir',
                    age: 24,
                },

            ]
        }
    },

    methods: {
        test() {
            alert('Метод test');
        },
        notic() {
            alert('Метод notic');
        }
    },

    computed: {
        // cryack() {
        //     alert(this.name + " - Имя");
        // }
    }
}
</script>

<style scoped>

</style>
