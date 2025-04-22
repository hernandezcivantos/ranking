<template>
    <Head title="Gestión de torneos"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestionar {{ torneo.nombre }}</h2>
        </template>

        <div class="py-6">
            <div class="w-full px-4">
                <div class="flex gap-10 h-[calc(100vh-120px)]">
                    <!-- Lista de jugadores -->
                    <div class="w-1/3 bg-white shadow rounded flex flex-col">
                        <h3 class="font-bold text-lg p-4 border-b">Jugadores</h3>
                        <div class="flex-1 overflow-y-auto px-4 py-2">
                            <div
                                v-for="player in jugadoresDisponibles"
                                :key="player.id"
                                class="p-2 border rounded mb-1 cursor-move bg-gray-50"
                                draggable="true"
                                @dragstart="onDragStart(player)"
                            >
                                {{ player.first_name }} {{ player.last_name }}

                                <span
                                class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-white"
                                :class="divisionColor(player.division.id)"
                            >
                {{ player.division.name }}
              </span>
                            </div>
                        </div>
                    </div>

                    <!-- Área de grupos -->
                    <div class="bg-white shadow rounded flex flex-col overflow-hidden">
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-300 px-4 pt-4 mb-2 overflow-x-auto">
                            <div
                                v-for="(grupo, index) in grupos"
                                :key="index"
                                @click="grupoActivo = index"
                                class="px-4 py-2 cursor-pointer border-b-2"
                                :class="grupoActivo === index ? 'border-blue-600 text-blue-600 font-semibold' : 'text-gray-600'"
                            >
                                {{ grupo.nombre }}
                            </div>
                            <div
                                @click="agregarGrupo"
                                class="px-4 py-2 cursor-pointer text-green-600 font-bold hover:text-green-800"
                            >
                                +
                            </div>
                        </div>

                        <!-- Contenido del grupo activo -->
                        <div class="flex-1 px-4 py-2 overflow-auto">
                            <template v-if="grupos.length">
                                <h3 class="text-lg font-semibold mb-2">{{ grupos[grupoActivo].nombre }}</h3>

                                <div
                                    class="min-h-[200px] p-4 border-2 border-dashed rounded"
                                    @dragover.prevent
                                    @drop="onDrop"
                                >
                                    <div
                                        v-for="jugador in grupos[grupoActivo].jugadores"
                                        :key="jugador.id"
                                        class="p-2 bg-blue-100 rounded mb-1"
                                    >
                                        {{ jugador.nombre }} {{ jugador.apellidos }}
                                    </div>
                                </div>
                            </template>

                            <template v-else>
                                <div class="text-gray-500 text-sm italic">
                                    Aún no hay grupos creados. Haz clic en el botón "+" para añadir uno.
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from "@inertiajs/vue3";
import { ref, computed } from 'vue'

const { torneo, players } = defineProps({
    torneo: Object,
    players: Array
})

console.log(players)

const grupos = ref([
    {nombre: 'Grupo 1', jugadores: []}
])

const jugadorArrastrado = ref(null)

const jugadoresDisponibles = computed(() => {
    const asignados = grupos.value.flatMap(g => g.jugadores.map(j => j.id))
    return players.filter(p => !asignados.includes(p.id))
})

const grupoActivo = ref(0)

function agregarGrupo() {
    const numero = grupos.value.length + 1
    grupos.value.push({nombre: `Grupo ${numero}`, jugadores: []})
    grupoActivo.value = grupos.value.length - 1 // ir al nuevo grupo
}

function onDragStart(player) {
    jugadorArrastrado.value = player
}

function onDrop() {
    const grupo = grupos.value[grupoActivo.value]
    if (!jugadorArrastrado.value) return

    // Evitar duplicados
    if (!grupo.jugadores.some(j => j.id === jugadorArrastrado.value.id)) {
        grupo.jugadores.push(jugadorArrastrado.value)
    }

    jugadorArrastrado.value = null
}

function divisionColor(division) {
    switch (division) {
        case 1: return 'bg-blue-500'
        case 2: return 'bg-emerald-500'
        case 3: return 'bg-amber-500'
        case 4: return 'bg-orange-500'
        case 5: return 'bg-pink-500'
        default: return 'bg-gray-400'
    }
}
</script>
