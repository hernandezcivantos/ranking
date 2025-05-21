<template>
    <Head :title="`Jornada #${matchday.id} - Panel de juego`" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
        { label: 'Panel', href: route('dashboard') },
        { label: 'Jornadas', href: route('leagues.matchdays.index', { league: matchday.league_id }) },
        { label: `Jornada #${matchday.id}` }
      ]" />
        </template>

        <div class="p-6 space-y-8">
            <h1 class="text-2xl font-bold">🎮 Panel de juego - Jornada #{{ matchday.id }}</h1>

            <div v-if="mensaje" class="bg-green-100 text-green-800 px-4 py-2 rounded border border-green-300">
                {{ mensaje }}
            </div>

            <!-- Estado de la jornada -->
            <div>
                <p class="text-gray-700 mb-2">Estado actual: <strong>{{ matchday.status }}</strong></p>
                <div class="flex gap-2 flex-wrap">
                    <button
                        v-for="state in estados"
                        :key="state"
                        class="px-3 py-1 rounded text-sm border border-gray-300 hover:bg-gray-100"
                        @click="cambiarEstado(state)"
                    >
                        {{ state }}
                    </button>
                </div>
            </div>

            <!-- Configuración de rondas -->
            <div class="border rounded p-4 bg-white shadow">
                <h2 class="text-xl font-semibold mb-4">⚙️ Configurar ronda</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipo de ronda</label>
                        <select v-model="tipoRonda" class="w-full border rounded p-2">
                            <option value="robin">Todos contra todos</option>
                            <option value="brackets">Eliminatoria directa</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Clasificados por grupo</label>
                        <select v-model="clasificadosPorGrupo" class="w-full border rounded p-2">
                            <option :value="1">1</option>
                            <option :value="2">2</option>
                            <option :value="3">3</option>
                            <option :value="4">4</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Deporte</label>
                        <select v-model="tipoDeporte" class="w-full border rounded p-2">
                            <option value="tenis_mesa">Tenis de mesa</option>
                            <option value="futbol">Fútbol</option>
                            <option value="custom">Otro</option>
                        </select>
                    </div>

                    <div v-if="tipoDeporte === 'tenis_mesa'">
                        <label class="block text-sm font-medium mb-1">Sets por partido</label>
                        <select v-model="setsPorPartido" class="w-full border rounded p-2">
                            <option :value="3">A 3 sets</option>
                            <option :value="5">A 5 sets</option>
                            <option :value="7">A 7 sets</option>
                        </select>
                    </div>

                    <div v-if="tipoDeporte === 'futbol'">
                        <label class="block text-sm font-medium mb-1">Duración (minutos)</label>
                        <input type="number" v-model="duracionMinutos" class="w-full border rounded p-2" min="1" />
                    </div>
                </div>

                <div class="mt-6">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="crearRonda">
                        ➕ Crear ronda
                    </button>
                </div>
            </div>

            <!-- Visualización de grupos y partidos existentes -->
            <div>
                <h2 class="text-xl font-semibold mt-6 mb-2">Grupos y Partidos</h2>
                <div v-for="group in matchday.groups" :key="group.id" class="mb-4">
                    <h3 class="font-semibold">Grupo: {{ group.name }}</h3>
                    <ul class="text-sm ml-4">
                        <li v-for="match in group.matches" :key="match.id">
                            {{ match.player1_name }} vs {{ match.player2_name }} →
                            <span class="font-medium">
                {{ match.winner_name || '⏳ Sin definir' }}
              </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'

const props = defineProps({
    matchday: Object
})

const estados = ['pendiente', 'en_marcha', 'congelada', 'cancelada', 'finalizada']
const tipoRonda = ref('robin')
const clasificadosPorGrupo = ref(2)
const tipoDeporte = ref('tenis_mesa')
const setsPorPartido = ref(3)
const duracionMinutos = ref(10)
const mensaje = ref('')

function cambiarEstado(nuevoEstado) {
    router.post(`/matchdays/${props.matchday.id}`, {
        ...props.matchday,
        status: nuevoEstado
    }, {
        preserveScroll: true
    })
}

function crearRonda() {
    router.post(`/matchdays/${props.matchday.id}/generate-round`, {
        type: tipoRonda.value,
        qualified_per_group: clasificadosPorGrupo.value,
        sport: tipoDeporte.value,
        sets: tipoDeporte.value === 'tenis_mesa' ? setsPorPartido.value : null,
        duration: tipoDeporte.value === 'futbol' ? duracionMinutos.value : null
    }, {
        preserveScroll: true,
        onSuccess: () => {
            mensaje.value = '✅ Ronda generada correctamente.'
            setTimeout(() => mensaje.value = '', 3000)
        }
    })
}
</script>

<style scoped>
</style>
