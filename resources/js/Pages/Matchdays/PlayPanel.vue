<template>
    <Head title="Jornada" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Jornadas', href: route('leagues.matchdays.index', { league: matchday.league_id }) },
                { label: `${matchday.name}` }
            ]" />
        </template>

        <div class="p-6">
            <!-- Configurar ronda -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4 flex items-center gap-2">
                    <GamepadIcon class="w-5 h-5" />
                    Configurar ronda
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="(label, key) in configLabels" :key="key">
                        <label class="block mb-1 text-sm font-medium">{{ label }}</label>
                        <select v-model="form[key]" class="w-full border rounded px-3 py-2">
                            <option v-for="val in configOptions[key]" :key="val" :value="val">{{ val }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button @click="crearRonda"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Calcular encuentros
                    </button>
                </div>
            </div>

            <!-- Grupos -->
            <div class="mt-10">
                <h3 class="text-xl font-medium mb-4">Grupos y Partidos</h3>

                <div v-for="group in matchday.groups" :key="group.id" class="mb-10 bg-white p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2 text-blue-700 uppercase">{{ group.name }}</h4>

                    <!-- Tabla tipo robin -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full text-sm border border-gray-300">
                            <thead>
                            <tr class="bg-blue-100 text-gray-800">
                                <th class="border border-gray-300 p-2 w-6"></th>
                                <th class="border border-gray-300 p-2 text-left">Jugador</th>
                                <th v-for="(_, idx) in group.players" :key="'head-' + idx"
                                    class="border border-gray-300 p-2 text-center w-10">
                                    {{ idx + 1 }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(player, i) in group.players" :key="player.id">
                                <td class="border border-gray-300 p-2 text-center font-bold">{{ i + 1 }}</td>
                                <td class="border border-gray-300 p-2 font-semibold">
                                    <div class="uppercase">{{ player.first_name }} {{ player.last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ player.club?.name || '—' }}</div>
                                </td>
                                <td
                                    v-for="(opponent, j) in group.players"
                                    :key="'cell-' + i + '-' + j"
                                    class="border border-gray-300 p-2 text-center"
                                    :class="obtenerEstiloCelda(group.matches, player.id, opponent.id)"
                                >
                                    <span v-if="i === j" class="text-gray-400">❌</span>
                                    <span v-else class="inline-block w-full text-center whitespace-nowrap">
                                        {{ mostrarResumenResultado(group.matches, player.id, opponent.id) }}
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Encuentros -->
                    <div>
                        <h5 class="font-semibold mb-2 flex items-center gap-2 cursor-pointer" @click="toggleGrupo(group.id)">
                            <ChevronDownIcon
                                :class="{ 'rotate-180': !grupoAbierto[group.id] }"
                                class="w-4 h-4 transition-transform duration-300" />
                            Encuentros <span class="text-gray-400 text-sm">({{ contarFinalizados(group.matches) }} finalizados)</span>
                        </h5>

                        <Transition name="fade">
                            <div v-show="grupoAbierto[group.id]">
                                <div v-for="match in group.matches" :key="match.id" class="mb-4 border p-4 rounded shadow-sm">
                                    <div class="font-semibold">
                                        {{ obtenerNombreJugador(match, 1) }} vs {{ obtenerNombreJugador(match, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-600" v-if="!match.is_finished">
                                        Mesa: <strong>{{ match.field_number || '—' }}</strong>
                                    </div>

                                    <div class="flex gap-2 mt-2">
                                        <input
                                            v-for="(_, i) in getMatchSets(match)"
                                            :key="i"
                                            v-model="match.sets[i]"
                                            type="text"
                                            class="border px-2 py-1 rounded w-16"
                                            placeholder="11-9"
                                            :disabled="match.is_finished"
                                        />
                                    </div>

                                    <button
                                        v-if="!match.is_finished"
                                        @click.prevent="finalizarEncuentro(match)"
                                        class="mt-2 px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700"
                                    >
                                        Finalizar encuentro
                                    </button>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>

        <RecalculateConfirmationModal
            v-if="showModal"
            @close="showModal = false"
            @confirm="recalcularEncuentros"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import RecalculateConfirmationModal from '@/Components/RecalculateConfirmationModal.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { GamepadIcon, ChevronDownIcon } from 'lucide-vue-next'
import { ref, onMounted } from 'vue'

const props = defineProps({ matchday: Object })
const csrfToken = usePage().props.csrf_token

const tiposRonda = ['Todos contra todos']
const clasificados = [1, 2, 3, 4, 5, 6]
const deportes = ['Tenis de mesa']
const setsPorPartido = [1, 2, 3, 4, 5]
const camposDisponibles = Array.from({ length: 30 }, (_, i) => i + 1)

const configLabels = {
    round_type: 'Tipo de ronda',
    qualified_per_group: 'Clasificados por grupo',
    sport: 'Deporte',
    sets_per_match: 'Sets por ecuentro',
    fields_total: 'Campos de juego',
}

const configOptions = {
    round_type: tiposRonda,
    qualified_per_group: clasificados,
    sport: deportes,
    sets_per_match: setsPorPartido,
    fields_total: camposDisponibles,
}

const form = ref({
    round_type: props.matchday.round_type || tiposRonda[0],
    qualified_per_group: props.matchday.qualified_per_group || 2,
    sport: props.matchday.sport || deportes[0],
    sets_per_match: props.matchday.sets_per_match || 3,
    fields_total: props.matchday.fields_total || 2,
})

const showModal = ref(false)
const grupoAbierto = ref({})
onMounted(() => {
    props.matchday.groups.forEach(group => {
        const pendientes = group.matches.filter(m => !m.is_finished).length
        grupoAbierto.value[group.id] = pendientes > 0
    })
})

function toggleGrupo(groupId) {
    grupoAbierto.value[groupId] = !grupoAbierto.value[groupId]
}

function crearRonda() {
    router.post(route('matchdays.generateRound', { matchday: props.matchday.id }), form.value, {
        preserveScroll: true,
        onSuccess: () => router.reload({ only: ['matchday'], preserveScroll: true })
    })
}

function recalcularEncuentros() {
    router.post(route('matchdays.recalculateRound', { matchday: props.matchday.id }), {
        _token: csrfToken
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false
            router.reload({ only: ['matchday'], preserveScroll: true })
        }
    })
}

function finalizarEncuentro(match) {
    router.patch(route('group-matches.finish', { match: match.id }), {
        _token: csrfToken,
        sets: match.sets
    }, {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['matchday'], preserveScroll: true })
        }
    })
}

function obtenerNombreJugador(match, n) {
    const jugador = n === 1 ? match.player1 : match.player2
    return jugador ? `${jugador.first_name} ${jugador.last_name}` : '—'
}

function getMatchSets(match) {
    const expectedLength = form.value.sets_per_match
    if (!Array.isArray(match.sets)) {
        match.sets = Array.from({ length: expectedLength }, () => '')
    }
    if (match.sets.length < expectedLength) {
        match.sets = [...match.sets, ...Array(expectedLength - match.sets.length).fill('')]
    }
    return match.sets
}

function mostrarResumenResultado(matches, id1, id2) {
    const match = matches.find(m =>
        (m.player1_id === id1 && m.player2_id === id2) ||
        (m.player2_id === id1 && m.player1_id === id2)
    )
    if (!match || !match.is_finished || !Array.isArray(match.sets)) return '—'

    let p1 = 0, p2 = 0
    for (const set of match.sets) {
        const [a, b] = (set || '').split('-').map(n => parseInt(n, 10))
        if (!isNaN(a) && !isNaN(b)) {
            if ((match.player1_id === id1 && a > b) || (match.player2_id === id1 && b > a)) {
                p1++
            } else {
                p2++
            }
        }
    }
    return `${p1}-${p2}`
}

function obtenerEstiloCelda(matches, id1, id2) {
    const match = matches.find(m =>
        (m.player1_id === id1 && m.player2_id === id2) ||
        (m.player2_id === id1 && m.player1_id === id2)
    )

    if (!match || !match.is_finished || !Array.isArray(match.sets)) return ''

    let p1 = 0, p2 = 0
    for (const set of match.sets) {
        const [a, b] = (set || '').split('-').map(n => parseInt(n, 10))
        if (!isNaN(a) && !isNaN(b)) {
            if ((match.player1_id === id1 && a > b) || (match.player2_id === id1 && b > a)) {
                p1++
            } else {
                p2++
            }
        }
    }

    if (p1 > p2) return 'bg-green-100 text-green-800 font-semibold'
    if (p2 > p1) return 'bg-red-100 text-red-800 font-semibold'
    if (p1 === p2 && p1 > 0) return 'bg-orange-100 text-orange-800 font-semibold'
    return ''
}

function contarFinalizados(matches) {
    return matches.filter(m => m.is_finished).length
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    height: 0;
    overflow: hidden;
}
</style>
