<template>
    <div class="min-h-screen bg-gray-100 py-10 px-4">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Ligas del grupo {{ group.name }}
            </h1>

            <div v-if="leagues.length" class="space-y-4">
                <div
                    v-for="league in leagues"
                    :key="league.id"
                    class="p-4 border rounded shadow hover:shadow-md transition"
                >
                    <h2 class="text-lg font-semibold text-gray-800">{{ league.name }}</h2>
                    <p class="text-sm text-gray-600 mb-2">{{ league.description }}</p>
                    <Link
                        :href="route('public.league', { group: group.slug, league: league.slug })"
                        class="text-blue-600 hover:underline text-sm"
                    >
                        Ver ranking →
                    </Link>
                </div>
            </div>

            <div v-else class="text-gray-600 text-sm">Este grupo aún no tiene ligas.</div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    group: Object,
    leagues: Array,
})
</script>
