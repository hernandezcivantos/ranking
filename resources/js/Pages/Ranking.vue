<template>
    <Head title="Ranking" />

    <AuthenticatedLayout>

    <div class="p-6 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Ranking de Jugadores</h1>

        <!-- Tabla -->
        <div v-if="filteredPlayers.length === 0" class="text-center text-gray-500">No se encontraron jugadores.</div>

        <div v-else class="overflow-x-auto">
            <table class="w-full table-auto border-collapse shadow-md rounded overflow-hidden">
                <thead class="bg-gray-100 text-left text-sm uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3 ">Foto</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3 text-center">Puntos</th>
                    <th class="px-4 py-3 text-center">Jugados</th>
                    <th class="px-4 py-3 text-center">Victorias</th>
                    <th class="px-4 py-3 text-center">Derrotas</th>
                    <th class="px-4 py-3">División</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(player, index) in filteredPlayers"
                    :key="player.id"
                    :class="[
              'border-b hover:bg-gray-50 transition-all',
              index === 0 ? 'bg-yellow-100 font-bold' :
              index === 1 ? 'bg-gray-200 font-semibold' :
              index === 2 ? 'bg-orange-100 font-semibold mb-3 border-b-4 border-gray-300' : '',
              index === 2 ? 'mb-3' : ''
            ]"
                >
                    <td class="px-4 py-3 text-center text-xl">
                        {{ index + 1 }}
                        <span v-if="index === 0">🥇</span>
                        <span v-else-if="index === 1">🥈</span>
                        <span v-else-if="index === 2">🥉</span>
                    </td>
                    <td class="px-4 py-2">
                        <img
                            :src="player.photo ? `/storage/${player.photo}` : '/images/default-image.png'"
                            alt="Foto"
                            class="w-12 h-12 object-cover rounded-full border"
                        />
                    </td>
                    <td class="px-4 py-2">{{ player.first_name }} {{ player.last_name }}</td>
                    <td class="px-4 py-2 text-center">{{ player.score }}</td>
                    <td class="px-4 py-2 text-center">{{ player.matches_played }}</td>
                    <td class="px-4 py-2 text-center font-semibold text-green-700">{{ player.matches_won }}</td>
                    <td class="px-4 py-2 text-center text-red-600">{{ player.matches_lost }}</td>
                    <td class="px-4 py-2">
              <span
                  class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-white"
                  :class="divisionColor(player.division_id)"
              >
                {{ player.division.name }}
              </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

const players = ref([])
const search = ref('')
const selectedDivision = ref('')
const showMenu = ref(false)

const divisions = ref(['Primera', 'Segunda', 'Tercera', 'Promesas'])

onMounted(() => {
    fetchPlayers()

    setInterval(() => {
        fetchPlayers()
    }, 5000)
})

const fetchPlayers = async () => {
    try {
        const response = await fetch('/api/players')
        players.value = await response.json()
    } catch (error) {
        console.error('Error cargando jugadores:', error)
    }
}

const filteredPlayers = computed(() => {
    return players.value
        .filter(player =>
            (selectedDivision.value === '' || player.division === selectedDivision.value) &&
            (`${player.first_name} ${player.last_name}`.toLowerCase().includes(search.value.toLowerCase()))
        )
        .sort((a, b) => b.matches_won - a.matches_won)
})

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

function toggleMenu() {
    showMenu.value = !showMenu.value
}
</script>
