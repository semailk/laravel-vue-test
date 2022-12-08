<template>
  <div>
    <div class="container">
      <button @click="add" id="add-transport" class="btn btn-success mt-3 mb-3">
        Add Transport
      </button>

      <table class="table">
        <thead>
        <tr>
          <th scope="col" v-for="field in fields" :key="field">{{ field }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items" :key="item.id">
          <td> {{ item.name }}</td>
          <td>{{ item.status }}</td>
          <td>
            <button @click="edit" class="btn btn-primary" :data-id="item.id">Edit</button>
            <button @click="remove" class="btn btn-danger" :data-id="item.id">Delete</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <transition v-show="isModalVisibleAdd" @close="closeModalAdd" name="modal-fade">
    <div class="modal-container">
      <div class="modal"
           role="dialog"
           aria-labelledby="modalTitle"
           aria-describedby="modalDescription"
      >
        <header
            class="modal-header"
            id="modalTitle"
        >
          <slot name="header">

            <button
                type="button"
                class="btn-close"
                @click="closeAdd"
                aria-label="Close modal"
            >
              x
            </button>
          </slot>
        </header>
        <section
            class="modal-body"
            id="modalDescription"
        >
          <slot name="body">
            <form @submit.prevent="store()" class="trans-form">
              <label for="name">Name</label>
              <input class="form-control" type="text" name="name" v-model="name">

              <label for="status">Status</label>
              <select id="status-add" :value="statusSelected" v-model="statusSelected" name="status"
                      class="form-control">
              </select>

              <input type="hidden" name="id" :value="id">

              <button class="btn btn-secondary mt-3">Save</button>

              <div class="errors-store"></div>
            </form>
          </slot>
        </section>
        <footer class="modal-footer">
          <slot name="footer">

            <button
                type="button"
                class="btn-green"
                @click="closeModalAdd"
                aria-label="Close modal"
            >
              Close me!
            </button>
          </slot>
        </footer>
      </div>
    </div>
  </transition>

  <transition v-show="isModalVisibleEdit" @close="closeModalEdit" name="modal-fade">
    <div class="modal-container">
      <div class="modal"
           role="dialog"
           aria-labelledby="modalTitle"
           aria-describedby="modalDescription"
      >
        <header
            class="modal-header"
            id="modalTitle"
        >
          <slot name="header">

            <button
                type="button"
                class="btn-close"
                @click="closeEdit"
                aria-label="Close modal"
            >
              x
            </button>
          </slot>
        </header>
        <section
            class="modal-body"
            id="modalDescription"
        >
          <slot name="body">
            <form @submit.prevent="update()" class="trans-form">
              <label for="name">Name</label>
              <input class="form-control" type="text" name="name" v-model="name" >

              <label for="status">Status</label>
              <select id="status-edit" :value="statusSelected" v-model="statusSelected" name="status"
                      class="form-control">

              </select>

              <input type="hidden" name="id" :value="id">

              <button class="btn btn-secondary mt-3">Update</button>
              <div class="errors-update"></div>
            </form>
          </slot>
        </section>
        <footer class="modal-footer">
          <slot name="footer">

            <button
                type="button"
                class="btn-green"
                @click="closeEdit"
                aria-label="Close modal"
            >
              Close me!
            </button>
          </slot>
        </footer>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from "axios";

const conf = {headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}};

export default {
  name: "IndexView",
  data() {
    return {
      fields: ['NAME', 'STATUS', 'ACTION'],
      items: '',
      isModalVisibleAdd: false,
      isModalVisibleEdit: false,
      name: '',
      status: '',
      id: '',
      statusSelected: ''
    }
  },
  methods: {
    add() {
      this.showModalAdd();
      var options = '';
      for (const status of this.status) {

        options = options + '<option ' + status + '>' + status + '</option>';
      }
      document.querySelector('#status-add').innerHTML = options;
    },
    closeAdd() {
      this.$emit('close');
      this.closeModalAdd();
    },
    closeEdit() {
      this.$emit('close');
      this.closeModalEdit();
    },
    edit(event) {
      axios.get('http://localhost:8000/api/transports/' + event.target.getAttribute('data-id'), conf)
          .then((response) => {
            this.showModalEdit();
            this.name = response.data.name;
            this.id = response.data.id;
            var options = '';
            this.statusSelected = response.data.status;
            for (const status of this.status) {
              options = options + '<option ' + status + ' selected>' + status + '</option>';
            }
            document.querySelector('#status-edit').innerHTML = options;
          });
    },
    remove(event) {
      axios.delete('http://localhost:8000/api/transports/' + event.target.getAttribute('data-id'), conf)
          .then(() => {
            location.reload()
          })
    },
    showModalAdd() {
      this.isModalVisibleAdd = true;
    },
    closeModalAdd() {
      this.isModalVisibleAdd = false;
    },
    showModalEdit() {
      this.isModalVisibleEdit = true;
    },
    closeModalEdit() {
      this.isModalVisibleEdit = false;
    },
    update() {
      axios.patch('http://localhost:8000/api/transports/' + this.id, {
        'name': this.name,
        'status': this.statusSelected,
        'id': this.id
      }, conf).then(() => {
        location.reload();
      }).catch((error) => {
        document.querySelector('.errors-update').append(JSON.stringify(error.response.data))
      })
    },
    store() {
      axios.post('http://localhost:8000/api/transports', {
        'name': this.name,
        'status': this.statusSelected,
      }, conf).then(() => {
        location.reload();
      }).catch((error) => {
        document.querySelector('.errors-store').append(JSON.stringify(error.response.data))
      })
    }
  },
  mounted() {
    let config = {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      }
    }
    axios.get('http://localhost:8000/api/statuses', config).then((response) => {
      this.status = response.data;
    })
    axios.get('http://localhost:8000/api/transports', config).then((response) => {
      this.items = response.data;
    })
        .catch(function (error) {
          if (error.response.status === 401) {
            window.location = '/login';
          }
        })
  }
}
</script>

<style scoped>

.modal {
  opacity: 1 !important;
  margin-top: 150px;
  max-height: 500px;
  max-width: 1000px;
  margin-left: 30%;
  background: rgba(157, 229, 177, 0.91);
  box-shadow: 2px 2px 20px 1px;
  overflow-x: auto;
  display: flex;
  flex-direction: column;
}

.modal-header,
.modal-footer {
  padding: 15px;
  display: flex;
}

.modal-header {
  border-bottom: 1px solid #eeeeee;
  color: #4AAE9B;
  justify-content: space-between;
}

.modal-footer {
  border-top: 1px solid #eeeeee;
  justify-content: flex-end;
}

.modal-body {
  position: relative;
  padding: 20px 10px;
}

.btn-close {
  border: none;
  font-size: 20px;
  padding: 20px;
  cursor: pointer;
  font-weight: bold;
  color: #4AAE9B;
  background: transparent;
}

.btn-green {
  color: white;
  background: #4AAE9B;
  border: 1px solid #4AAE9B;
  border-radius: 2px;
}

.modal-fade-enter,
.modal-fade-leave-active {
  opacity: 1;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity .5s ease
}

.errors-update, .errors-store {
  color: red;
  font-size: 24px;
}
</style>