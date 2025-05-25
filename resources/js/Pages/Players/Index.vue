<template>
    <Head title="Jugadores"/>
    <PlayerFormModal
        :show="showCreateModal"
        :divisions="props.divisions"
        :clubs="props.clubs"
        @close="showCreateModal = false"
    />
    <PlayerEditModal
        v-if="showEditModal"
        :key="selectedPlayer?.id || 'new'"
        :show="true"
        :player="selectedPlayer"
        :divisions="props.divisions"
        :clubs="props.clubs"
        @close="closeEditModal"
    />
    <ConfirmDeleteModal
        :show="showConfirmModal"
        :playerName="selectedPlayer ? `${selectedPlayer.first_name} ${selectedPlayer.last_name}` : ''"
        @cancel="cancelDelete"
        @confirm="proceedDelete"
    />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Jugadores' }
            ]"/>
        </template>

        <div class="p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <button @click="showCreateModal = true"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Añadir Jugador
                </button>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar jugador..."
                    class="mt-4 sm:mt-0 border rounded px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring focus:border-blue-400"
                />
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="p-4 text-center">Nombre</th>
                        <!--                        <th class="p-4">Pala</th>-->
                        <th class="p-4 text-center">Partidos</th>
                        <th class="p-4 text-center">Victorias</th>
                        <th class="p-4 text-center">Derrotas</th>
                        <th class="p-4">División</th>
                        <th class="p-4">Club</th>
                        <th class="p-4">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="player in props.players.data" :key="player.id" class="border-t hover:bg-gray-50">
                        <td class="p-4 whitespace-nowrap">{{ player.first_name }} {{ player.last_name }}</td>
                        <!--                        <td class="p-4">{{ player.paddle_type || '—' }}</td>-->
                        <td class="p-4 text-center">{{ player.matches_played }}</td>
                        <td class="p-4 text-center">{{ player.matches_won }}</td>
                        <td class="p-4 text-center">{{ player.matches_lost }}</td>
                        <td class="p-4">{{ player.division?.name || '—' }}</td>
                        <td class="p-4">{{ player.club?.name || '—' }}</td>
                        <td class="p-4">
                            <button @click="openEditModal(player)" class="text-blue-600 hover:underline">Editar</button>
                            <button @click="destroyPlayer(player)" class="text-red-600 hover:underline ml-4">Eliminar
                            </button>
                        </td>
                    </tr>
                    <tr v-if="props.players.data.length === 0">
                        <td colspan="5" class="p-4 text-center text-gray-500">No se encontraron jugadores</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div v-if="props.players.data.length > 0" class="mt-8 pb-16 flex justify-center">
            <nav class="inline-flex rounded-md shadow-sm isolate" aria-label="Paginación">
                <template v-for="(link, index) in props.players.links" :key="index">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        :class="[
          'relative inline-flex items-center px-4 py-2 text-sm font-medium border',
          link.active
            ? 'z-10 bg-blue-600 border-blue-600 text-white'
            : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'
        ]"
                        v-html="translateLabel(link.label)"
                    />
                    <span
                        v-else
                        :class="'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 border border-gray-300 bg-gray-100'"
                        v-html="translateLabel(link.label)"
                    />
                </template>
            </nav>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PlayerFormModal from '@/Components/Players/PlayerFormModal.vue';
import PlayerEditModal from '@/Components/Players/PlayerEditModal.vue';
import ConfirmDeleteModal from '@/Components/Players//ConfirmDeleteModal.vue';
import {ref, computed} from 'vue';
import {Head, Link, router} from '@inertiajs/vue3';
import {watch} from 'vue';
import Breadcrumb from '@/Components/Breadcrumb.vue'

const props = defineProps({
    players: Object,
    filters: Object,
    divisions: Array,
    clubs: Array,
});

const translateLabel = (label) => {
    if (label.includes('Previous')) return '« Anterior';
    if (label.includes('Next')) return 'Siguiente »';
    return label;
};

// Búsqueda reactiva
const search = ref(props.filters.search || '');

const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedPlayer = ref(null);

const openEditModal = (player) => {
    selectedPlayer.value = player;
    showEditModal.value = true;
};

const showConfirmModal = ref(false); // controlar el modal
const selectedPlayerId = ref(null);  // guardar el ID del jugador a eliminar

const destroyPlayer = (player) => {
    selectedPlayer.value = player;         // para el nombre en el modal
    selectedPlayerId.value = player.id;    // para hacer el delete
    showConfirmModal.value = true;
};

const cancelDelete = () => {
    selectedPlayer.value = null;
    selectedPlayerId.value = null;
    showConfirmModal.value = false;
};

const proceedDelete = () => {
    router.delete(`/players/${selectedPlayerId.value}`, {
        onFinish: () => {
            showConfirmModal.value = false;
            selectedPlayer.value = null;
            selectedPlayerId.value = null;
        }
    });
};

watch(search, (newValue) => {
    router.get('/players', {search: newValue}, {
        preserveState: true,
        replace: true,
    });
});

function closeEditModal() {
    selectedPlayer.value = null
    showEditModal.value = false
}
</script>

