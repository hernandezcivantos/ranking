<template>
    <div class="min-h-screen bg-gray-100 text-gray-800">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">Liguillas.com</h1>
            <div class="flex items-center gap-4">
                <span>{{ $page.props.auth.user.name }}</span>
                <button
                    @click="logout"
                    class="text-sm text-red-600 hover:underline"
                >
                    Logout
                </button>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-gray-200 px-6 py-3 flex gap-4 text-sm font-medium">
            <Link href="/dashboard" class="hover:underline">Dashboard</Link>
            <Link href="/groups" class="hover:underline">Grupos</Link>
            <Link href="/leagues" class="hover:underline">Ligas</Link>
            <Link href="/matchdays" class="hover:underline">Jornadas</Link>
            <Link href="/players" class="hover:underline">Jugadores</Link>
        </nav>

        <!-- Page Content -->
        <main class="p-6">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

function logout() {
    router.post(route('logout'))
}
</script>
