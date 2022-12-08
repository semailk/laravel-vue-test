<template>
  <nav>
    <router-link class="link" to="/">Main</router-link>
    <router-link v-if="!auth" class="link" to="/login">Login</router-link>
    <router-link v-if="!auth" class="link" to="/register">Register</router-link>
    <button @click="logout()" v-if="auth" class="btn btn-danger" id="logout">Logout</button>
  </nav>
  <router-view/>
</template>

<script>
export default {
  data() {
    return {
      auth: false
    }
  },
  methods: {
    logout() {
      this.auth = false;
      localStorage.setItem('token', '');
      window.location = 'login';
    }
  },
  mounted() {
    this.auth = localStorage.getItem('token');
  }
}
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

.link {
  margin-left: 10px;
}

#logout {
  margin-left: 10px;
}
</style>
