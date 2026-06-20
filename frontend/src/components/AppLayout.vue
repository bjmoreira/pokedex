<script setup>
// EN: Shell layout with sidebar + topbar, wrapping authenticated pages.
// PT: Layout base com barra lateral + topo, envolvendo as páginas autenticadas.
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LanguageSwitcher from './LanguageSwitcher.vue'

const auth = useAuthStore()
const router = useRouter()

// EN: Log out and return to the login screen. / PT: Sai e volta para a tela de login.
async function logout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="d-flex">
    <!-- EN: Sidebar / PT: Barra lateral -->
    <aside class="app-sidebar d-flex flex-column p-3">
      <RouterLink :to="{ name: 'pokedex' }" class="brand text-decoration-none d-flex align-items-center mb-4 gap-2">
        <img src="/pokeball.svg" alt="" width="28" height="28" />
        <span>Pokédex</span>
      </RouterLink>

      <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
          <RouterLink :to="{ name: 'pokedex' }" class="nav-link">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>{{ $t('nav.pokedex') }}
          </RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink :to="{ name: 'captured' }" class="nav-link">
            <i class="bi bi-collection-fill me-2"></i>{{ $t('nav.captured') }}
          </RouterLink>
        </li>
      </ul>
    </aside>

    <!-- EN: Main column / PT: Coluna principal -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100">
      <nav class="navbar navbar-light bg-white shadow-sm px-4">
        <span class="navbar-text fw-bold text-secondary">
          {{ auth.user?.name }}
        </span>
        <div class="d-flex align-items-center gap-3">
          <LanguageSwitcher />
          <button class="btn btn-outline-danger btn-sm" type="button" @click="logout">
            <i class="bi bi-box-arrow-right me-1"></i>{{ $t('nav.logout') }}
          </button>
        </div>
      </nav>

      <main class="p-4 flex-grow-1">
        <slot />
      </main>
    </div>
  </div>
</template>
