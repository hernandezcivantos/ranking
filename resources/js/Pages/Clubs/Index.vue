<template>
    <Head title="Clubes" />

    <ClubFormModal
        :show="showModal"
        :club="selectedClub"
        @update:show="showModal = $event"
        @close="closeModal"
    />

    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
        { label: 'Panel', href: route('dashboard') },
        { label: 'Clubes' }
      ]" />
        </template>

        <div class="p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <button @click="openModal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Añadir Club
                </button>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar club..."
                    class="mt-4 sm:mt-0 border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring focus:border-blue-400"
                />
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="p-4">Nombre</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="club in filteredClubs" :key="club.id" class="border-t hover:bg-gray-50">
                        <td class="p-4">{{ club.name }}</td>
                        <td class="p-4 text-right">
                            <button @click="openModal(club)" class="text-blue-600 hover:underline">Editar</button>
                        </td>
                    </tr>
                    <tr v-if="filteredClubs.length === 0">
                        <td colspan="2" class="p-4 text-center text-gray-500">No se encontraron clubes</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import ClubFormModal from '@/Components/Clubs/ClubFormModal.vue'
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    clubs: Array
})

const showModal = ref(false)
const selectedClub = ref({ id: null, name: '' })
const search = ref('')

const openModal = (club = { id: null, name: '' }) => {
    selectedClub.value = { ...club }
    showModal.value = true
}

const closeModal = () => {
    selectedClub.value = { id: null, name: '' }
    showModal.value = false
}

const filteredClubs = computed(() =>
    props.clubs.filter(c =>
        c.name.toLowerCase().includes(search.value.toLowerCase())
    )
)
</script>
