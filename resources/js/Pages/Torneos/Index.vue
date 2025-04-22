<template>
    <Head title="Torneos" />
    <AuthenticatedLayout>

        <TorneoEditModal
            :show="modalEditarVisible"
            :torneo="torneoEditando"
            @close="modalEditarVisible = false"
            @updated="actualizarTorneo"
        />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Torneos</h2>
        </template>

        <div class="py-6">
            <div class="w-full px-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <button @click="mostrarModal = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">
                        Crear nuevo torneo
                    </button>

                    <TorneoFormModal
                        :show="mostrarModal"
                        @close="mostrarModal = false"
                        @created="agregarTorneo"
                    />

                    <div v-if="torneos.length">
                        <table class="w-full mt-4 border border-gray-200">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="text-left p-2">Nombre</th>
                                <th class="text-left p-2">Estado</th>
                                <th class="text-left p-2">Fecha inicio</th>
                                <th class="text-left p-2">Fecha fin</th>
                                <th class="text-left p-2">Grupos</th>
                                <th class="text-left p-2">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="torneo in listaTorneos" :key="torneo.id">
                                <td class="p-2">
                                    <Link :href="`/torneos/${torneo.id}/gestionar`" class="text-blue-600 hover:underline">
                                        {{ torneo.nombre }}
                                    </Link>
                                </td>
                                <td class="p-2 capitalize">{{ torneo.estado.replace('_', ' ') }}</td>
                                <td class="p-2">
                                    {{ torneo.fecha_inicio ? dayjs(torneo.fecha_inicio).format('DD/MM/YYYY') : '-' }}
                                </td>
                                <td class="p-2">
                                    {{ torneo.fecha_fin ? dayjs(torneo.fecha_fin).format('DD/MM/YYYY') : '-' }}
                                </td>
                                <td class="p-2">{{ torneo.grupos_count || 0}}</td>
                                <td class="p-2">
                                    <button @click="abrirModalEdicion(torneo)" class="text-blue-600 hover:underline">Editar</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="mt-4 text-gray-500">No hay torneos creados aún.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import TorneoFormModal from '@/Components/Torneos/TorneoFormModal.vue'
import dayjs from 'dayjs'
import TorneoEditModal from '@/Components/Torneos/TorneoEditModal.vue'
dayjs.locale('es')

const modalEditarVisible = ref(false)
const torneoEditando = ref(null)

const props = defineProps({
    torneos: Array
})

const mostrarModal = ref(false)
const listaTorneos = ref([...props.torneos])

function agregarTorneo(torneo) {
    listaTorneos.value.unshift(torneo)
}

function abrirModalEdicion(torneo) {
    torneoEditando.value = torneo
    modalEditarVisible.value = true
}

function actualizarTorneo(data) {
    const index = listaTorneos.value.findIndex(t => t.id === torneoEditando.value.id)
    if (index !== -1) {
        listaTorneos.value[index] = { ...listaTorneos.value[index], ...data }
    }
}
</script>
