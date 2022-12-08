<template>
  <div class="container">
    <h1>LOGIN</h1>
    <form @submit.prevent="loginSubmit">
      <label for="email">Email</label>
      <input v-model="email" type="email" id="email" class="form-control" name="email">

      <label for="password">Password</label>
      <input v-model="password" type="password" class="form-control" name="password">

      <button class="btn btn-primary mt-2">Accept</button>
    </form>
    <div id="error-login"></div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "LoginView",
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    loginSubmit() {
      axios.post('http://localhost:8000/api/login', {'email': this.email, 'password': this.password})
          .then(response => {
            localStorage.setItem('token', response.data.data.token);
            window.location = '/';
          }).catch((error) => {
        let errorLogin = document.querySelector('#error-login');
        errorLogin.innerHTML = '';

        errorLogin.append(error.response.data.data.error)
      })
    }
  }
}
</script>

<style scoped>
#error-login {
  font-size: 24px;
  color: red;
  margin-top: 15px;
}
</style>