import { createRouter, createWebHistory } from 'vue-router';
import IndexView from '../views/IndexView';
import RegisterView from '../views/RegisterView';
import LoginView from "@/views/LoginView";

const routes = [
  {
    path: '/',
    name: 'index',
    component: IndexView
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
