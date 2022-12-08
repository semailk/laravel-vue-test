<template>
  <div class="">
    <h1>REGISTER</h1>

    <form @submit.prevent="registerUser">
      <div class="container">
        <label for="name">Name</label>
        <input required v-model="name" type="text" class="form-control" name="name">

        <label for="name">Email</label>
        <input required v-model="email" type="email" class="form-control" name="email">

        <label for="password">Password</label>
        <input required v-model="password" type="password" class="form-control" name="password">

        <button type="submit" class="btn btn-primary mt-2">Save</button>
        <div id="error-register"></div>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "RegisterView",
  data() {
    return {
      name: '',
      email: '',
      password: '',
    }
  },
  methods: {
    registerUser() {
      axios.post('http://localhost:8000/api/register', {'name':this.name, 'email':this.email,'password': this.password}).then((response) => {
        localStorage.setItem('token', response.data.data.token);
        window.location = '/'
      }).catch((error) => {
        let errorRegister = document.querySelector('#error-register');
        errorRegister.innerHTML = '';

        errorRegister.append(JSON.stringify(error.response.data.data));
      })
    }
  },

}
</script>

<style scoped>
#error-register {
  font-size: 24px;
  color: red;
  margin-top: 15px;
}
</style>