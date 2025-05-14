<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">Editar grupo: {{ form.name }}</h2>
        </template>

        <div class="py-6 px-8 space-y-4">
            <form @submit.prevent="updateGroup">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre del grupo</label>
                    <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <div>
                    <h3 class="mt-4 text-sm font-semibold text-gray-800">Jugadores asignados</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                        <div v-for="player in players" :key="player.id" class="flex items-center">
                            <input
                                type="checkbox"
                                :id="'player-' + player.id"
                                :value="player.id"
                                v-model="form.player_ids"
                                class="mr-2"
                            />
                            <label :for="'player-' + player.id" class="text-sm text-gray-700">{{ player.first_name }} {{ player.last_name }}</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
                    >
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { defineProps } from 'vue'

const props = defineProps({
    group: Object,
    players: Array,
    assignedPlayerIds: Array,
})

const form = useForm({
    name: props.group.name,
    player_ids: props.assignedPlayerIds,
})

function updateGroup() {
    form.put(route('matchdays.groups.update', [props.group.matchday_id, props.group.id]))
}
</script>
