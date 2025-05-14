<template>
    <Head title="Jornadas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
        { label: 'Panel', href: route('dashboard') },
        { label: 'Jornadas' }
      ]" />
        </template>

        <div class="p-6">
            <!-- Si hay ligas disponibles -->
            <div v-if="leagues.length">

                <!-- Selector de liga -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Selecciona una liga:
                    </label>
                    <select
                        v-model="selectedLeagueId"
                        class="border rounded p-2 w-full max-w-sm"
                    >
                        <option
                            v-for="league in leagues"
                            :key="league.id"
                            :value="league.id.toString()"
                        >
                            {{ league.name }}
                        </option>
                    </select>
                </div>

                <!-- Botón crear jornada -->
                <div class="mb-6">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        @click="goToCreateMatchday"
                    >
                        Crear jornada
                    </button>
                </div>

                <!-- Lista de jornadas -->
                <div v-if="matchdays.length">
                    <ul class="space-y-4">
                        <li
                            v-for="matchday in matchdays"
                            :key="matchday.id"
                            class="p-4 bg-white border rounded shadow"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        Jornada #{{ matchday.id }}
                                    </h3>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="text-sm text-blue-600 hover:underline"
                                        @click="goToEditMatchday(matchday.id)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="text-sm text-green-600 hover:underline"
                                        @click="goToFinalizeMatchday(matchday.id)"
                                    >
                                        Finalizar
                                    </button>
                                    <button
                                        class="text-sm text-purple-600 hover:underline"
                                        @click="goToGroups(matchday.id)"
                                    >
                                        Ver grupos
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Si no hay jornadas -->
                <div v-else class="text-gray-600">
                    Esta liga no tiene jornadas aún.
                </div>
            </div>

            <!-- Si no hay ligas -->
            <div v-else class="text-red-600 text-lg">
                ❌ No hay ligas disponibles. No se puede acceder a las jornadas.
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
    leagues: Array,
    currentLeagueId: Number,
    matchdays: Array,
})

const selectedLeagueId = ref(props.currentLeagueId?.toString() ?? '')

watch(selectedLeagueId, (newVal) => {
    if (newVal) {
        router.visit(route('leagues.matchdays.index', { league: newVal }), {
            preserveState: false,
            replace: true,
        })
    }
})

onMounted(() => {
    console.log('currentLeagueId:', props.currentLeagueId)
    console.log('matchdays:', props.matchdays)
})

function goToCreateMatchday() {
    router.visit(`/leagues/${selectedLeagueId.value}/matchdays/create`)
}

function goToEditMatchday(matchdayId) {
    router.visit(`/leagues/${selectedLeagueId.value}/matchdays/${matchdayId}/edit`)
}

function goToFinalizeMatchday(matchdayId) {
    router.visit(`/matchdays/${matchdayId}/finalize`)
}

function goToGroups(matchdayId) {
    router.visit(`/matchdays/${matchdayId}/groups`)
}
</script>
