<template>
    <Head title="Grupo"/>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Jornadas', href: route('leagues.matchdays.index', { league: leagueId }) },
                { label: matchday.name, href: route('matchdays.groups.index', matchday.id) },
                { label: group.name }
            ]" />
        </template>

        <div class="px-8 py-6 space-y-4">
            <div class="flex justify-between items-center">
                <button
                    @click="goToEdit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Editar grupo
                </button>
            </div>

            <div v-if="players.length">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg shadow-sm text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">División</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(player, index) in players"
                        :key="player.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                :src="player.photo ?? '/images/default-image.png'"
                                class="w-10 h-10 rounded-full object-cover border"
                                alt="Foto del jugador"
                            />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ player.first_name }} {{ player.last_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
        <span
            class="inline-block text-xs font-semibold px-2 py-1 rounded-full"
            :class="badgeColor(player.division?.name)"
        >
          {{ player.division?.name ?? '-' }}
        </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="text-gray-600">No hay jugadores asignados a este grupo.</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head, router} from '@inertiajs/vue3'
import { defineProps } from 'vue'
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    group: Object,
    players: Array,
    matchday: Object,
    leagueId: Number,
})

function goToEdit() {
    router.visit(route('matchdays.groups.edit', [props.matchday.id, props.group.id]))
}

function badgeColor(name) {
    if (!name) return 'bg-gray-300 text-gray-800'
    if (name.includes('Nacional')) return 'bg-pink-600 text-white'
    if (name.includes('Honor')) return 'bg-blue-600 text-white'
    if (name.includes('Local')) return 'bg-gray-600 text-white'
    if (name.includes('Autonómica')) return 'bg-fuchsia-600 text-white'
    return 'bg-gray-400 text-white'
}
</script>
