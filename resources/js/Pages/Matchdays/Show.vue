<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Jornadas' }
            ]"/>
        </template>

        <div class="px-8 py-6 space-y-6">

            <!-- Botón para ver los grupos de esta jornada -->
            <div class="flex justify-between">
                <Link
                    :href="route('matchdays.groups.index', matchday.id)"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                >
                    Ver grupos de esta jornada
                </Link>

                <Link
                    :href="route('matchdays.final.edit', matchday.id)"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                    Finalizar jornada
                </Link>
            </div>

            <!-- Información de la jornada -->
            <div class="bg-white border rounded shadow p-4">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Detalles de la jornada</h3>
                <p class="text-sm text-gray-700">Nombre: {{ matchday.name }}</p>
                <p class="text-sm text-gray-700">Fecha: {{ matchday.date }}</p>
                <!-- Puedes añadir más datos aquí si tu modelo tiene más información -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    matchday: Object
})
</script>
