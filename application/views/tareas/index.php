<html>

<head>
    <meta charset="utf-8" />
    <title> Lista de tareas </title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body class="bg-info">
    <div id="app">
        <div class="container ">
            <h1 class="text-center">{{Nombre}}</h1>
            <div class="row">
                <div class="col s4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Agregar Genero</h5>
                            <div class="card-text text-center">
                                <form>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Titulo</span>
                                        </div>
                                        <input type="number" ref="id" class="form-control" placeholder="Genero" aria-label="Genero" aria-describedby="basic-addon1" hidden>
                                        <input type="text" ref="titulo" class="form-control" placeholder="Genero" aria-label="Genero" aria-describedby="basic-addon1" required>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-success" @click="Post_Genero()">Enviar</button><button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s8">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Genero</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="Genero of Generos">
                                <td>{{Genero.id}}</td>
                                <td>{{Genero.titulo}}</td>
                                <td class="btn-group "><button class="btn btn-danger" @click="Delete_Generos(Genero.id)">Elimnar</button><button class="btn btn-warning" @click="Update_Generos(Genero.id)">Editar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script>
        const url = 'http://localhost/Tokens_con_CodeIgniter/index.php/api/Genero';
        const app = new Vue({
            el: '#app',
            data: {
                Generos: [],
                Nombre: 'Api-Vue JS ',
                titulo: '',
                id: '',
                Editar: false 
            },
            mounted() {
                this.Get_Generos()
            },
            methods: {
                async Get_Generos() {
                    const response = await fetch(url)
                    const myJson = await response.json();
                    this.Generos = myJson;
                },
                async Delete_Generos(id) {
                    const response = await fetch(url + "/" + id, {
                        method: 'DELETE'
                    });
                    this.Get_Generos();
                },
                Limpiar() {
                    this.$refs.titulo.value = '';
                },
                async Post_Genero(e) {
                    event.preventDefault(); //evito la recarga al enviar

                    if (this.Editar === false) {
                        //para mandar por form data a la api
                        const data = new FormData();
                        data.append('titulo', this.$refs.titulo.value); //agrego la clave valor y el valor

                        const response = await fetch(url, {
                            method: 'POST',
                            body: data
                        })
                    } else if (this.Editar === true) {
                        //para mandar por form data a la api

                        var data = JSON.stringify({
                            "id": this.$refs.id.value,
                            "titulo": this.$refs.titulo.value
                        })

                        console.log(data)
                        const response = await fetch(url, {
                            method: 'PUT',
                            body: data,
                            headers: {
                                'Content-Type': 'application/json'
                            },
                        });
                    }
                    //para no recargar
                    this.Editar = false;
                    this.Get_Generos();
                    this.Limpiar();
                },
                async Update_Generos(id) {
                    const response = await fetch(url + "/" + id)
                    const myJson = await response.json();
                    this.$refs.titulo.value = myJson.titulo;
                    this.$refs.id.value = myJson.id;
                    this.Editar = true;
                }
            }
        })
    </script>
</body>

</html>