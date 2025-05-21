<template>
    <div class="space-y-10 max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center">📣 Jornada en curso</h1>

        <div class="flex justify-center gap-4">
            <button @click="abrirEnlace" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M12.293 2.293a1 1 0 011.414 0l4 4a1 1 0 01-.707 1.707H16v7a2 2 0 01-2 2H6a2 2 0 01-2-2V7h-.586a1 1 0 01-.707-1.707l4-4a1 1 0 011.414 0z" />
                </svg>
                Abrir enlace
            </button>
            <button @click="copiarEnlace" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2h2a2 2 0 002-2V5a2 2 0 00-2-2H4z" />
                </svg>
                Copiar enlace
            </button>
        </div>

        <transition name="fade">
            <div v-if="copiado" class="fixed bottom-6 right-6 bg-green-600 text-white px-4 py-2 rounded shadow-lg">
                ✅ ¡Enlace copiado!
            </div>
        </transition>

        <div v-for="group in groups" :key="group.id" class="space-y-4">
            <h2 class="text-xl font-semibold">Clasificación - {{ group.name }}</h2>
            <GroupRanking :group="group" sport="tenis" />
        </div>

        <div v-if="brackets.length">
            <h2 class="text-xl font-semibold text-center mt-10 mb-4">Eliminatoria</h2>
            <div v-for="(round, i) in brackets" :key="i" class="mb-6">
                <h3 class="font-medium mb-2">Ronda {{ i + 1 }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="(match, j) in round" :key="j" class="p-3 border rounded shadow-sm bg-white">
                        <div class="font-semibold">{{ match.player1 }} vs {{ match.player2 }}</div>
                        <div class="text-sm mt-1">Ganador: <strong>{{ match.winner || 'Pendiente' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="brackets.length && brackets.at(-1)[0]?.winner">
            <FinalPodium :brackets="brackets" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import GroupRanking from './GroupRanking.vue'
import FinalPodium from './FinalPodium.vue'

const { props } = usePage()
const jornadaId = props.jornadaId
const groups = ref([])
const brackets = ref([])
const copiado = ref(false)

onMounted(async () => {
    const response = await fetch(`/public/matchday/${jornadaId}/json`)
    const data = await response.json()
    groups.value = data.groups
    brackets.value = data.brackets
})

function abrirEnlace() {
    const url = window.location.href
    window.open(url, '_blank')
}

function copiarEnlace() {
    const url = window.location.href
    navigator.clipboard.writeText(url)
        .then(() => {
            copiado.value = true
            setTimeout(() => (copiado.value = false), 2000)
        })
        .catch(() => alert('Error al copiar'))
}
</script>

<style scoped>
body {
    background-color: #f9fafb;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
