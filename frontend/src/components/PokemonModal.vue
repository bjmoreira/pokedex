<script setup>
// EN: Detail modal. "view" mode offers a Capture button; "edit" mode lets the
//     trainer edit metadata. All data (stats, abilities, region, evolution) is
//     already present on the pokemon object - no extra API calls.
// PT: Modal de detalhes. Modo "view" oferece o botão Capturar; modo "edit"
//     permite editar metadados. Todos os dados (stats, habilidades, região,
//     evolução) já vêm no objeto pokemon - sem chamadas extras à API.
import { computed, ref, watch } from 'vue'

const props = defineProps({
  pokemon: { type: Object, required: true },
  mode: { type: String, default: 'view' }, // 'view' | 'edit'
})

const emit = defineEmits(['close', 'capture', 'save'])

const form = ref({ nickname: '', level: '', detail_note: '' })
const busy = ref(false)

// EN: Friendly stat labels. / PT: Rótulos amigáveis dos atributos.
const statLabels = {
  hp: 'HP',
  attack: 'Atk',
  defense: 'Def',
  'special-attack': 'Sp. Atk',
  'special-defense': 'Sp. Def',
  speed: 'Speed',
}

// EN: Stats as an ordered array with bar widths (max ~180 for display).
// PT: Atributos como array ordenado com largura de barra (máx ~180 para exibir).
const stats = computed(() =>
  Object.entries(props.pokemon.stats || {}).map(([key, value]) => ({
    key,
    label: statLabels[key] || key,
    value,
    pct: Math.min(100, Math.round((value / 180) * 100)),
  })),
)

watch(
  () => props.pokemon,
  (p) => {
    if (!p) return
    form.value = {
      nickname: p.nickname || '',
      level: p.level || '',
      detail_note: p.detail_note || '',
    }
  },
  { immediate: true },
)

async function save() {
  busy.value = true
  try {
    await emit('save', { id: props.pokemon.id, ...form.value })
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <div class="modal d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5)" @click.self="emit('close')">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize">
            {{ pokemon.nickname || pokemon.name }}
            <small v-if="pokemon.region" class="badge bg-info ms-2">
              <i class="bi bi-geo-alt-fill"></i> {{ pokemon.region }}
            </small>
          </h5>
          <button type="button" class="btn-close" :aria-label="$t('modal.close')" @click="emit('close')"></button>
        </div>

        <div class="modal-body">
          <div class="row g-4">
            <!-- EN: Artwork + types / PT: Arte + tipos -->
            <div class="col-md-5 text-center">
              <img :src="pokemon.image" :alt="pokemon.name" class="img-fluid" style="max-height: 200px" />
              <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                <span v-for="type in pokemon.types || [pokemon.type]" :key="type" class="badge bg-secondary type-badge">
                  {{ type }}
                </span>
              </div>
              <p v-if="pokemon.description" class="small text-muted mt-3 fst-italic">{{ pokemon.description }}</p>
            </div>

            <div class="col-md-7">
              <!-- EN: Base info / PT: Informações base -->
              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between px-0 py-1">
                  <span>{{ $t('modal.height') }}</span><strong>{{ (pokemon.height / 10).toFixed(1) }} m</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 py-1">
                  <span>{{ $t('modal.weight') }}</span><strong>{{ (pokemon.weight / 10).toFixed(1) }} kg</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 py-1">
                  <span>{{ $t('modal.baseExp') }}</span><strong>{{ pokemon.base_experience ?? '—' }}</strong>
                </li>
                <li v-if="pokemon.generation" class="list-group-item d-flex justify-content-between px-0 py-1">
                  <span>{{ $t('modal.generation') }}</span><strong class="text-capitalize">{{ pokemon.generation?.replace('generation-', '') }}</strong>
                </li>
                <li v-if="pokemon.habitat" class="list-group-item d-flex justify-content-between px-0 py-1">
                  <span>{{ $t('modal.habitat') }}</span><strong class="text-capitalize">{{ pokemon.habitat }}</strong>
                </li>
              </ul>

              <!-- EN: Abilities / PT: Habilidades -->
              <div v-if="pokemon.abilities?.length" class="mb-3">
                <h6 class="text-muted small mb-1">{{ $t('modal.abilities') }}</h6>
                <span v-for="a in pokemon.abilities" :key="a" class="badge bg-light text-dark border me-1 text-capitalize">
                  {{ a.replace('-', ' ') }}
                </span>
              </div>

              <!-- EN: Editable trainer metadata / PT: Metadados editáveis do treinador -->
              <template v-if="mode === 'edit'">
                <div class="mb-2">
                  <label class="form-label small">{{ $t('modal.nicknamePlaceholder') }}</label>
                  <input v-model="form.nickname" type="text" class="form-control form-control-sm" maxlength="50" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">{{ $t('captured.level') }}</label>
                  <input v-model="form.level" type="text" class="form-control form-control-sm" maxlength="50" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">{{ $t('modal.notePlaceholder') }}</label>
                  <textarea v-model="form.detail_note" class="form-control form-control-sm" rows="2" maxlength="255"></textarea>
                </div>
              </template>
            </div>
          </div>

          <!-- EN: Base stats with bars / PT: Atributos base com barras -->
          <div v-if="stats.length" class="mt-2">
            <h6 class="text-muted small">{{ $t('modal.stats') }}</h6>
            <div v-for="s in stats" :key="s.key" class="d-flex align-items-center gap-2 mb-1">
              <span class="small text-muted" style="width: 64px">{{ s.label }}</span>
              <strong class="small" style="width: 32px">{{ s.value }}</strong>
              <div class="progress flex-grow-1" style="height: 8px">
                <div class="progress-bar bg-danger" :style="{ width: s.pct + '%' }"></div>
              </div>
            </div>
          </div>

          <!-- EN: Evolution chain / PT: Cadeia de evolução -->
          <div v-if="pokemon.evolution?.length > 1" class="mt-3">
            <h6 class="text-muted small">{{ $t('modal.evolution') }}</h6>
            <div class="d-flex flex-wrap align-items-center gap-3">
              <template v-for="(evo, i) in pokemon.evolution" :key="evo.id">
                <i v-if="i > 0" class="bi bi-arrow-right text-muted"></i>
                <div class="text-center">
                  <img :src="evo.image" :alt="evo.name" style="height: 56px" />
                  <div class="small text-capitalize">{{ evo.name }}</div>
                </div>
              </template>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" @click="emit('close')">{{ $t('modal.close') }}</button>
          <button v-if="mode === 'view'" type="button" class="btn btn-danger" @click="emit('capture', pokemon)">
            <i class="bi bi-bag-plus me-1"></i>{{ $t('pokedex.capture') }}
          </button>
          <button v-else type="button" class="btn btn-primary" :disabled="busy" @click="save">
            {{ busy ? $t('modal.saving') : $t('modal.save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
