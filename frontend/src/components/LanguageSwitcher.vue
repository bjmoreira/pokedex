<script setup>
// EN: Flag-based language switcher (Brazil / USA / Spain).
// PT: Seletor de idioma por bandeira (Brasil / EUA / Espanha).
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()

const languages = [
  { code: 'pt-BR', flag: '🇧🇷', label: 'Português' },
  { code: 'en', flag: '🇺🇸', label: 'English' },
  { code: 'es', flag: '🇪🇸', label: 'Español' },
]

// EN: Switch locale and persist the choice. / PT: Troca o idioma e persiste a escolha.
function setLocale(code) {
  locale.value = code
  localStorage.setItem('locale', code)
}
</script>

<template>
  <div class="d-flex gap-2 align-items-center">
    <button
      v-for="lang in languages"
      :key="lang.code"
      class="flag-btn"
      :class="{ active: locale === lang.code }"
      :title="lang.label"
      type="button"
      @click="setLocale(lang.code)"
    >
      {{ lang.flag }}
    </button>
  </div>
</template>
