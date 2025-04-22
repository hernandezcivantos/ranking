<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Administración
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    <!-- BOTÓN: Ranking -->
                    <Link href="/" class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-300 flex items-center justify-center text-center text-lg font-medium text-gray-800 hover:bg-gray-50">
                        🔝 Ranking
                    </Link>

                    <!-- BOTÓN: Jugadores -->
                    <Link href="/players" class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-300 flex items-center justify-center text-center text-lg font-medium text-gray-800 hover:bg-gray-50">
                        🏓 Jugadores
                    </Link>

                    <!-- BOTÓN: Torneos -->
                    <Link href="/torneos" class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-300 flex items-center justify-center text-center text-lg font-medium text-gray-800 hover:bg-gray-50">
                        🏆 Torneos
                    </Link>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
