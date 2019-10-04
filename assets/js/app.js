const app = new Vue({
    el: '#app',
    data: {
        Generos: [],
        Nombre: 'Api-Vue JS ',
        titulo: ''
    },
    mounted() {

    },
    methods: {
        cargar() {

        },
        async get_generos() {
            await fetch('http://localhost/Tokens_con_CodeIgniter/index.php/api/Genero')
                .then(data => this.Generos = data).catch(e => console.error(e));
        }
    }
})