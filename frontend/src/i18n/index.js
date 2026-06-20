// EN: vue-i18n setup with Portuguese, English and Spanish locales.
// PT: Configuração do vue-i18n com locais Português, Inglês e Espanhol.
import { createI18n } from 'vue-i18n'
import ptBR from './locales/pt-BR'
import en from './locales/en'
import es from './locales/es'

// EN: Persisted locale, falling back to Brazilian Portuguese.
// PT: Idioma persistido, com fallback para Português do Brasil.
const saved = localStorage.getItem('locale') || 'pt-BR'

const i18n = createI18n({
  legacy: false,
  globalInjection: true,
  locale: saved,
  fallbackLocale: 'en',
  messages: {
    'pt-BR': ptBR,
    en,
    es,
  },
})

export default i18n
