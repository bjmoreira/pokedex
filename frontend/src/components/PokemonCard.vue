<script setup>
// EN: Compact card showing a Pokémon's artwork, id, name and type.
// PT: Cartão compacto mostrando arte, id, nome e tipo de um Pokémon.
defineProps({
  pokemon: { type: Object, required: true },
})

defineEmits(['select'])
</script>

<template>
  <div class="card pokemon-card h-100 text-center border-0 shadow-sm" @click="$emit('select', pokemon)">
    <div class="card-body">
      <span class="pokemon-id small fw-bold">#{{ String(pokemon.id).padStart(3, '0') }}</span>
      <img :src="pokemon.image" :alt="pokemon.name" class="d-block mx-auto my-2" loading="lazy" />
      <h6 class="card-title text-capitalize mb-2">{{ pokemon.nickname || pokemon.name }}</h6>
      <div class="d-flex flex-wrap justify-content-center gap-1">
        <span
          v-for="type in pokemon.types || [pokemon.type]"
          :key="type"
          class="badge bg-secondary type-badge"
        >
          {{ type }}
        </span>
      </div>
    </div>
  </div>
</template>
