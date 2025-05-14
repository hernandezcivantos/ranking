<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">Finalizar jornada: {{ matchday.name }}</h2>
        </template>

        <div class="px-8 py-6 space-y-6">
            <p class="text-gray-700">Asigna las posiciones finales a los jugadores clasificados.</p>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div
                        v-for="(player, index) in players"
                        :key="player.id"
                        class="flex items-center gap-4"
                    >
                        <span class="w-8 text-sm text-gray-600">{{ index + 1 }}.</span>
                        <span class="flex-1 text-gray-800">
              {{ player.first_name }} {{ player.last_name }}
            </span>
                        <input
                            type="number"
                            min="1"
                            v-model="positions[player.id]"
                            class="w-24 border-gray-300 rounded px-2 py-1 text-sm"
                            placeholder="Posición"
                            required
                        />
                    </div>
                </div>

                <div class="mt-6">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                    >
                        Confirmar posiciones y asignar puntos
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    matchday: Object,
    players: Array,
})

const positions = useForm(
    props.players.reduce((acc, player) => {
        acc[player.id] = ''
        return acc
    }, {})
)

function submit() {
    positions.post(route('matchdays.finalize', props.matchday.id))
}
</script>
