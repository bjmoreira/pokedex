<script setup>
// EN: Login screen. Authenticates and redirects to the Pokédex.
// PT: Tela de login. Autentica e redireciona para a Pokédex.
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LanguageSwitcher from '@/components/LanguageSwitcher.vue'

const auth = useAuthStore()
const router = useRouter()

const email = ref('admin@admin.com')
const password = ref('123456')
const loading = ref(false)
const error = ref('')

async function submit() {
  loading.value = true
  error.value = ''
  try {
    await auth.login(email.value, password.value)
    router.push({ name: 'pokedex' })
  } catch {
    error.value = 'login.error'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-wrapper">
    <div class="card shadow-lg border-0" style="width: 22rem">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <img src="/pokeball.svg" alt="" width="36" height="36" />
            <h1 class="h4 mb-0 fw-bold">{{ $t('login.title') }}</h1>
          </div>
          <LanguageSwitcher />
        </div>
        <p class="text-muted small mb-4">{{ $t('login.subtitle') }}</p>

        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label">{{ $t('login.email') }}</label>
            <input
              v-model="email"
              type="email"
              class="form-control"
              required
              autocomplete="username"
            />
          </div>
          <div class="mb-3">
            <label class="form-label">{{ $t('login.password') }}</label>
            <input
              v-model="password"
              type="password"
              class="form-control"
              required
              autocomplete="current-password"
            />
          </div>

          <div v-if="error" class="alert alert-danger py-2 small">{{ $t(error) }}</div>

          <button class="btn btn-danger w-100 fw-bold" type="submit" :disabled="loading">
            {{ loading ? $t('login.loading') : $t('login.submit') }}
          </button>
        </form>

        <p class="text-muted small text-center mt-3 mb-0">{{ $t('login.demoHint') }}</p>
      </div>
    </div>
  </div>
</template>
