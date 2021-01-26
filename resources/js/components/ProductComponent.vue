<template>
  <div class="container">
    <hr />
    <navbar-component></navbar-component>

    <div class="row justify-content-center">
      <div class="col-md-10">
        <br />
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin euismod
          ullamcorper nisi, id mattis augue consequat eget. Duis mattis sapien
          arcu, ac elementum sem lacinia sed.
        </p>

        <flash-message class="statusMessage"></flash-message>

        <form @submit="formSubmit" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-9">
              <input
                type="file"
                accept=".json"
                class="form-control"
                v-on:change="onChange"
              />
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block">Enviar</button>
            </div>
          </div>
        </form>
        <br />

        <!-- The Modal -->
        <div class="modal" :class="{ mostrar: modal }">
          <div class="modal-dialog">
            <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header text-center">
                <h4 class="modal-title w-100">{{ tituloModal }}</h4>
                <button
                  @click="encerrarModal()"
                  type="button"
                  class="close"
                  data-dismiss="modal"
                ></button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                <flash-message class="statusMessage"></flash-message>

                <div class="my-4">
                  <label for="title" title> Title</label>
                  <input
                    v-model="product.title"
                    type="text"
                    class="form-control"
                    id="title"
                    placeholder="title produto"
                  />
                </div>

                <div class="my-4">
                  <label for="type" type>Type</label>
                  <input
                    v-model="product.type"
                    type="text"
                    class="form-control"
                    id="type"
                    placeholder="type produto"
                  />
                </div>

                <div class="my-4">
                  <label for="price" price>Price</label>
                  <input
                    v-model="product.price"
                    type="number"
                    step="any"
                    min="0"
                    class="form-control"
                    id="price"
                    placeholder="price produto"
                  />
                </div>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                <button
                  @click="encerrarModal()"
                  type="button"
                  class="btn btn-primary"
                  data-dismiss="modal"
                >
                  Cancelar
                </button>
                <button
                  @click="atualizar_remover()"
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                >
                  {{ actionButton }}
                </button>
              </div>
            </div>
          </div>
        </div>
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Title</th>
              <th scope="col">Type</th>
              <th scope="col">Rating</th>
              <th scope="col">Price</th>
              <th scope="col">Created</th>
              <th scope="col" colspan="2" class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td style="width: 25%">{{ product.title }}</td>
              <td>{{ product.type }}</td>
              <td>{{ product.rating }}</td>
              <td>R$ {{ product.price }}</td>
              <td>{{ product.created_at | moment("D/M/YYYY") }}</td>
              <td>
                <button
                  @click="
                    modificar = true;
                    abrirModal(product);
                  "
                  class="btn btn-warning"
                >
                  Edit
                </button>
                <button
                  @click="
                    modificar = false;
                    abrirModal(product);
                  "
                  class="btn btn-danger"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      product: {
        title: "",
        type: "",
        price: "",
      },
      modificar: true,
      modal: 0,
      products: [],
      tituloModal: "",
      actionButton: "",
      typeButton: "",
      fileName: "",
      token: process.env.MIX_APP_API
    };
  },
  methods: {
    async listar(fileName) {
      const config = {
        headers: {
          Authorization: this.token,
        },
      };
      let dat = fileName;
      const res = await axios.get(
        "http://localhost:8000/api/v1/getDataFromFile?path_file_upload=" + dat,
        config
      );
      this.products = res.data;
    },
    async atualizar_remover() {
      const config = {
        headers: {
          Authorization: this.token,
        },
      };
      if (this.modificar) {
        axios
          .put(
            "http://localhost:8000/api/v1/products/" + this.id,
            this.product,
            config
          )
          .then((response) => {
            this.encerrarModal();
            this.info = response.data.success;
            this.flashSuccess(this.info, {
              timeout: 3000,
            });
          })
          .catch((error) => {
            this.info = error.response.data;
            this.flashError("Todos os campos são obrigatórios", {
              timeout: 3000,
            });
          });
      } else {
        axios
          .delete("http://localhost:8000/api/v1/products/" + this.id, config)
          .then((response) => {
            this.encerrarModal();

            this.info = response.data.success;
            this.flashSuccess(this.info, {
              timeout: 3000,
            });
          })
          .catch((error) => {
            this.info = error.response.data;
            this.flashError("Todos os campos são obrigatórios", {
              timeout: 3000,
            });
          });
      }
      this.listar(this.fileName);
    },
    abrirModal(data = {}) {
      this.modal = 1;
      this.product.title = data.title;
      this.product.type = data.type;
      this.product.price = data.price;
      if (this.modificar) {
        (this.id = data.id), (this.tituloModal = "Modificar Produto");
        this.actionButton = "Atualizar";
        this.typeButton = "success";
      } else {
        (this.id = data.id), (this.tituloModal = "Confirmar Exclusão Produto");
        this.actionButton = "Deletar";
        this.typeButton = "danger";
      }
    },
    encerrarModal() {
      this.modal = 0;
    },
    onChange(e) {
      this.file = e.target.files[0];
    },
    formSubmit(e) {
      e.preventDefault();

      const config = {
        headers: {
          "content-type": "multipart/form-data",
          Authorization: this.token,
        },
      };

      let data = new FormData();
      data.append("file", this.file);

      axios
        .post("http://localhost:8000/api/v1/products/", data, config)
        .then((response) => {
          this.fileName = response.data;
          this.listar(this.fileName);
          this.flashSuccess("Arquivo carregado com sucesso", {
            timeout: 3000,
          });
        })
        .catch((error) => {
          this.info = error.response.data;
          this.flashError(this.info.error, {
            timeout: 3000,
          });
        });
    },
  },
};
</script>

<style >
.mostrar {
  display: list-item;
  opacity: 1;
  background: rgba(44, 38, 75, 0.849);
}

.navbar,
p {
  border: 1px solid #000;
}
form {
  padding-left: 5%;
}
</style>