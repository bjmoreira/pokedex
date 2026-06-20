// EN: Application routes and the authentication navigation guard.
// PT: Rotas da aplicação e o guard de navegação de autenticação.
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import LoginView from '@/views/LoginView.vue'
import PokedexView from '@/views/PokedexView.vue'
import CapturedView from '@/views/CapturedView.vue'

const routes = [
  { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
  { path: '/', name: 'pokedex', component: PokedexView, meta: { auth: true } },
  { path: '/captured', name: 'captured', component: CapturedView, meta: { auth: true } },
  { path: '/:pathMatch(.*)*', redirect: '/' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// EN: Redirect unauthenticated users to login and vice-versa.
// PT: Redireciona usuários não autenticados ao login e vice-versa.
router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.auth && !auth.isAuthenticated) {
    return { name: 'login' }
  }

  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'pokedex' }
  }

  return true
})

export default router
