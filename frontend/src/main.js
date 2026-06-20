// EN: Application entrypoint - wires Vue, Pinia, Router and i18n together.
// PT: Ponto de entrada da aplicação - conecta Vue, Pinia, Router e i18n.
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './assets/main.css'

import App from './App.vue'
import router from './router'
import i18n from './i18n'
import { useAuthStore } from './stores/auth'

const app = createApp(App)

app.use(createPinia())
app.use(i18n)

// EN: Restore any persisted session before mounting the router.
// PT: Restaura a sessão persistida antes de montar o router.
useAuthStore().restore()

app.use(router)
app.mount('#app')
