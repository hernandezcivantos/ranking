<template>
    <Head title="Panel principal"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">Panel principal</h2>
        </template>

        <div class="py-6 px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg cursor-pointer transition-transform hover:-translate-y-1"
                    @click="goTo(`/groups/${groupId}/leagues`)"
                >
                    <h3 class="text-lg font-bold text-blue-800 mb-1">Ligas</h3>
                    <p class="text-sm text-gray-600">Consulta o crea nuevas ligas</p>
                </div>

                <div
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg cursor-pointer transition-transform hover:-translate-y-1"
                    @click="goTo('/matchdays')"
                >
                    <h3 class="text-lg font-bold text-blue-800 mb-1">Jornadas</h3>
                    <p class="text-sm text-gray-600">Organiza y edita tus jornadas</p>
                </div>

                <div
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg cursor-pointer transition-transform hover:-translate-y-1"
                    @click="goTo('/players')"
                >
                    <h3 class="text-lg font-bold text-blue-800 mb-1">Jugadores</h3>
                    <p class="text-sm text-gray-600">Gestiona los jugadores registrados</p>
                </div>

                <div
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg cursor-pointer transition-transform hover:-translate-y-1"
                    @click="goTo('/clubs')"
                >
                    <h3 class="text-lg font-bold text-blue-800 mb-1">Clubes</h3>
                    <p class="text-sm text-gray-600">Gestiona los clubes registrados</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head, router} from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    groupId: Number
})

function goTo(path) {
    router.visit(path)
}
</script>
