<template>
    <div class="min-h-screen bg-white text-gray-800">
        <header class="bg-white shadow py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold">Liguillas.com</h1>
            <div>
                <Link href="/login" class="text-blue-600 hover:underline">Login</Link>
            </div>
        </header>

        <main class="p-4">
            <slot />
        </main>

        <footer class="text-center text-sm text-gray-500 py-4">
            &copy; {{ new Date().getFullYear() }} Liguillas.com. Todos los derechos reservados.
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
