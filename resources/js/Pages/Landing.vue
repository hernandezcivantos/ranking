<template>
    <GuestLayout>
        <section class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Liguillas.com</h1>
            <p class="text-gray-600 mb-6">Gestiona tus ligas, jornadas y rankings de forma sencilla.</p>
            <Link href="/login" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Iniciar sesión
            </Link>
        </section>
    </GuestLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
</script>
