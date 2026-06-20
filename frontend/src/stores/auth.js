// EN: Pinia store that owns authentication state (token + user).
// PT: Store Pinia que mantém o estado de autenticação (token + usuário).
import { defineStore } from 'pinia'
import api from '@/lib/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    user: null,
  }),

  getters: {
    // EN: Whether a user is currently authenticated. / PT: Se há um usuário autenticado.
    isAuthenticated: (state) => Boolean(state.token),
  },

  actions: {
    // EN: Rehydrate state from localStorage on app boot.
    // PT: Recupera o estado do localStorage ao iniciar a app.
    restore() {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      if (token) {
        this.token = token
        this.user = user ? JSON.parse(user) : null
      }
    },

    // EN: Authenticate and persist the issued token. / PT: Autentica e persiste o token emitido.
    async login(email, password) {
      const { data } = await api.post('/login', { email, password })
      this.token = data.token
      this.user = data.user
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
    },

    // EN: Revoke the token server-side and clear local state.
    // PT: Revoga o token no servidor e limpa o estado local.
    async logout() {
      try {
        await api.post('/logout')
      } catch {
        // EN: Ignore network errors on logout. / PT: Ignora erros de rede no logout.
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    },
  },
})
