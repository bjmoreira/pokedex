<script setup>
// EN: Captured page - the user's personal collection with edit/release actions.
// PT: Página de Capturados - a coleção pessoal do usuário com editar/soltar.
import { onMounted, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import api from '@/lib/api'
import AppLayout from '@/components/AppLayout.vue'
import PokemonCard from '@/components/PokemonCard.vue'
import PokemonModal from '@/components/PokemonModal.vue'

const { t } = useI18n()

const captured = ref([])
const loading = ref(true)
const error = ref(false)
const selected = ref(null)

onMounted(load)

async function load() {
  loading.value = true
  error.value = false
  try {
    const { data } = await api.get('/captured')
    captured.value = data.data
  } catch {
    error.value = true
  } finally {
    loading.value = false
  }
}

// EN: Persist edited trainer metadata. / PT: Persiste os metadados editados do treinador.
async function save(payload) {
  await api.put(`/captured/${payload.id}`, {
    nickname: payload.nickname,
    level: payload.level,
    detail_note: payload.detail_note,
  })
  selected.value = null
  await load()
}

// EN: Release (delete) a captured Pokémon after confirmation.
// PT: Solta (exclui) um Pokémon capturado após confirmação.
async function release(item) {
  if (!confirm(t('captured.confirmRelease'))) return
  await api.delete(`/captured/${item.id}`)
  await load()
}
</script>

<template>
  <AppLayout>
    <div class="mb-3">
      <h1 class="h3 mb-1">{{ $t('captured.title') }}</h1>
      <p class="text-muted mb-0">{{ $t('captured.subtitle') }}</p>
    </div>

    <div v-if="loading" class="text-center text-muted py-5">
      <div class="spinner-border text-danger" role="status"></div>
      <p class="mt-2">{{ $t('common.loading') }}</p>
    </div>

    <div v-else-if="error" class="alert alert-danger">{{ $t('common.error') }}</div>

    <div v-else-if="!captured.length" class="text-muted py-5 text-center">{{ $t('captured.empty') }}</div>

    <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
      <div v-for="item in captured" :key="item.id" class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex gap-3">
            <img :src="item.image" :alt="item.name" style="height: 96px; width: 96px; object-fit: contain" />
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between">
                <span class="pokemon-id small fw-bold">#{{ String(item.id).padStart(3, '0') }}</span>
              </div>
              <h6 class="text-capitalize mb-1">
                {{ item.nickname || item.name }}
                <small v-if="item.nickname" class="text-muted text-capitalize">({{ item.name }})</small>
              </h6>
              <p class="small text-muted mb-1">
                {{ $t('captured.level') }}: {{ item.level || '—' }}
              </p>
              <p class="small text-muted mb-2 text-truncate">{{ item.detail_note }}</p>
              <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" @click="selected = item">
                  <i class="bi bi-pencil"></i> {{ $t('captured.edit') }}
                </button>
                <button class="btn btn-sm btn-outline-danger" @click="release(item)">
                  <i class="bi bi-box-arrow-up"></i> {{ $t('captured.release') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <PokemonModal
      v-if="selected"
      :pokemon="selected"
      mode="edit"
      @close="selected = null"
      @save="save"
    />
  </AppLayout>
</template>
