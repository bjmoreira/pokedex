<script setup>
// EN: Pokédex page - paginated list (10/page) served from the local DB, with
//     search and an "importing" state for the one-time PokeAPI import.
// PT: Página da Pokédex - lista paginada (10/página) servida do banco local, com
//     busca e um estado de "importando" para a importação única da PokeAPI.
import { onMounted, onUnmounted, ref, watch } from 'vue'
import api from '@/lib/api'
import AppLayout from '@/components/AppLayout.vue'
import PokemonCard from '@/components/PokemonCard.vue'
import PokemonModal from '@/components/PokemonModal.vue'

const pokemon = ref([])
const loading = ref(true)
const importing = ref(false)
const error = ref(false)
const search = ref('')
const page = ref(1)
const lastPage = ref(1)
const total = ref(0)
const selected = ref(null)
const toast = ref('')

let retryTimer = null
let searchTimer = null

onMounted(load)
onUnmounted(() => {
  clearTimeout(retryTimer)
  clearTimeout(searchTimer)
})

// EN: Reset to page 1 and reload when the search term changes (debounced).
// PT: Volta para a página 1 e recarrega quando a busca muda (com debounce).
watch(search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    page.value = 1
    load()
  }, 350)
})

async function load() {
  clearTimeout(retryTimer)
  loading.value = true
  error.value = false
  try {
    const { data } = await api.get('/pokemon', {
      params: { page: page.value, search: search.value || undefined },
    })

    // EN: Backend still importing -> show loader and poll again.
    // PT: Backend ainda importando -> mostra loader e tenta de novo.
    if (data.importing) {
      importing.value = true
      retryTimer = setTimeout(load, 3000)
      return
    }

    importing.value = false
    pokemon.value = data.data
    lastPage.value = data.meta.last_page
    total.value = data.meta.total
  } catch (e) {
    // EN: Connection error or backend not ready (no response / 5xx / 502) ->
    //     treat as "still importing" and retry. Other errors surface normally.
    // PT: Erro de conexão ou backend não pronto (sem resposta / 5xx / 502) ->
    //     trata como "ainda importando" e tenta de novo. Outros erros aparecem.
    const status = e.response?.status
    if (!e.response || status >= 500) {
      importing.value = true
      retryTimer = setTimeout(load, 3000)
      return
    }
    error.value = true
  } finally {
    loading.value = false
  }
}

function goTo(p) {
  if (p < 1 || p > lastPage.value || p === page.value) return
  page.value = p
  load()
}

// EN: Capture the selected Pokémon for the current user.
// PT: Captura o Pokémon selecionado para o usuário atual.
async function capture(p) {
  try {
    await api.post('/captured', { pokemon_id: p.id })
    toast.value = 'pokedex.captured'
  } catch {
    toast.value = 'common.error'
  } finally {
    selected.value = null
    setTimeout(() => (toast.value = ''), 2500)
  }
}
</script>

<template>
  <AppLayout>
    <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
      <div>
        <h1 class="h3 mb-1">{{ $t('pokedex.title') }}</h1>
        <p class="text-muted mb-0">{{ $t('pokedex.subtitle') }}</p>
      </div>
      <input v-model="search" class="form-control" style="max-width: 18rem" :placeholder="$t('pokedex.search')" />
    </div>

    <div v-if="toast" class="alert alert-success py-2">{{ $t(toast) }}</div>

    <!-- EN: One-time import loader / PT: Loader da importação inicial -->
    <div v-if="importing" class="text-center text-muted py-5">
      <div class="spinner-border text-danger mb-3" style="width: 3rem; height: 3rem" role="status"></div>
      <p class="mb-0 mx-auto" style="max-width: 28rem">{{ $t('pokedex.importing') }}</p>
    </div>

    <!-- EN: Normal page loader / PT: Loader normal de página -->
    <div v-else-if="loading" class="text-center text-muted py-5">
      <div class="spinner-border text-danger" role="status"></div>
      <p class="mt-2">{{ $t('common.loading') }}</p>
    </div>

    <div v-else-if="error" class="alert alert-danger">{{ $t('common.error') }}</div>

    <div v-else-if="!pokemon.length" class="text-muted py-5 text-center">{{ $t('pokedex.empty') }}</div>

    <template v-else>
      <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
        <div v-for="p in pokemon" :key="p.id" class="col">
          <PokemonCard :pokemon="p" @select="selected = $event" />
        </div>
      </div>

      <!-- EN: Pagination controls / PT: Controles de paginação -->
      <nav class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
        <span class="text-muted small">{{ $t('pokedex.showing', { count: pokemon.length, total }) }}</span>
        <div class="btn-group">
          <button class="btn btn-outline-secondary btn-sm" :disabled="page <= 1" @click="goTo(page - 1)">
            <i class="bi bi-chevron-left"></i> {{ $t('pokedex.prev') }}
          </button>
          <span class="btn btn-light btn-sm disabled">{{ $t('pokedex.page', { current: page, last: lastPage }) }}</span>
          <button class="btn btn-outline-secondary btn-sm" :disabled="page >= lastPage" @click="goTo(page + 1)">
            {{ $t('pokedex.next') }} <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </nav>
    </template>

    <PokemonModal
      v-if="selected"
      :pokemon="selected"
      mode="view"
      @close="selected = null"
      @capture="capture"
    />
  </AppLayout>
</template>
