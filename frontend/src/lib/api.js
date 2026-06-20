// EN: Pre-configured Axios instance used across the app.
// PT: Instância do Axios pré-configurada usada em toda a aplicação.
import axios from 'axios'

const api = axios.create({
  // EN: Same-origin in production (nginx proxy); Vite proxy in development.
  // PT: Mesma origem em produção (proxy nginx); proxy do Vite em desenvolvimento.
  baseURL: '/api',
  headers: { Accept: 'application/json' },
})

// EN: Attach the bearer token to every outgoing request when present.
// PT: Anexa o token Bearer a toda requisição quando disponível.
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// EN: On 401 (expired/invalid token) clear the session and go to login. This
//     prevents protected pages from looping on a stale token.
// PT: Em 401 (token expirado/inválido) limpa a sessão e vai para o login. Isso
//     evita que páginas protegidas fiquem em loop com um token velho.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      if (window.location.pathname !== '/login') {
        window.location.assign('/login')
      }
    }
    return Promise.reject(error)
  },
)

export default api
