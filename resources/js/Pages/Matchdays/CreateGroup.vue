<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">
                Nuevo grupo para: {{ matchday.name }}
            </h2>
        </template>

        <div class="py-6 px-8">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del grupo</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border-gray-300 rounded-md shadow-sm"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona jugadores</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        <div
                            v-for="player in players"
                            :key="player.id"
                            class="flex items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                :value="player.id"
                                v-model="form.player_ids"
                                class="rounded border-gray-300"
                            />
                            <label>{{ player.first_name }} {{ player.last_name }}</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Guardar grupo
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    matchday: Object,
    players: Array
})

const form = useForm({
    name: '',
    player_ids: []
})

function submit() {
    form.post(`/matchdays/${props.matchday.id}/groups`)
}
</script>
