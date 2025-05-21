<template>
    <Head title="Ligas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Ligas' }
            ]" />
        </template>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <button
                    @click="openCreateModal"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    + Crear liga
                </button>
            </div>

            <div v-if="leagues.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div
                    v-for="league in leagues"
                    :key="league.id"
                    class="p-4 bg-white border rounded shadow flex flex-col justify-between"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">{{ league.name }}</h2>
<!--                            <p class="text-sm text-gray-500">Slug: {{ league.slug }}</p>-->
                        </div>
                        <!-- Icono de enlace público -->
                        <a
                            :href="route('public.league', { group: groupSlug, league: league.slug })"
                            target="_blank"
                            class="text-gray-400 hover:text-blue-600"
                            title="Ver ranking público"
                        >
                            Ranking público
                        </a>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <button
                            class="text-sm text-blue-600 hover:underline"
                            @click="openEditModal(league)"
                        >
                            Editar
                        </button>
                        <Link
                            :href="route('leagues.ranking', { league: league.id })"
                            class="text-sm text-green-600 hover:underline"
                        >
                            Ranking privado
                        </Link>
                    </div>
                </div>
            </div>

            <div v-else class="text-gray-600">No hay ligas creadas aún en este grupo.</div>

            <!-- Modal para crear/editar -->
            <LeagueFormModal
                :show="showModal"
                :league="selectedLeague"
                :group-id="groupId"
                @close="closeModal"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import LeagueFormModal from '@/Components/LeagueFormModal.vue'

const props = defineProps({
    leagues: Array,
    groupId: Number,
    groupSlug: String, // <--- Añadido
})

const showModal = ref(false)
const selectedLeague = ref(null)

function openCreateModal() {
    selectedLeague.value = null
    showModal.value = true
}

function openEditModal(league) {
    selectedLeague.value = { ...league }
    showModal.value = true
}

function closeModal() {
    showModal.value = false
}
</script>
