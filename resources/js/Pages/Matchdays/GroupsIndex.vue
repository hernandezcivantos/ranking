<template>
    <Head title="Grupos de jornada"/>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Jornadas', href: route('leagues.matchdays.index', { league: matchday.league_id }) },
                { label:  matchday.name }
            ]"/>
        </template>

        <div class="px-8 py-6 space-y-4">
            <div class="flex">
                <Link
                    :href="route('matchdays.groups.create', matchday.id)"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    + Crear grupo
                </Link>
            </div>

            <div v-if="groups.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="group in groups"
                    :key="group.id"
                    class="p-4 bg-white border rounded shadow hover:shadow-md transition"
                >
                    <h3 class="text-lg font-bold text-gray-800">{{ group.name }}</h3>
                    <p class="text-sm text-gray-600">
                        Jugadores: {{ group.players_count }}
                    </p>
                    <div class="mt-3">
                        <Link
                            :href="route('matchdays.groups.show', [matchday.id, group.id])"
                            class="text-blue-600 hover:underline text-sm"
                        >
                            Ver detalles →
                        </Link>
                    </div>
                </div>
            </div>

            <div v-else class="text-gray-600">Esta jornada aún no tiene grupos.</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head, Link} from '@inertiajs/vue3'
import { defineProps } from 'vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'

const props = defineProps({
    matchday: Object,
    groups: Array,
})
</script>
