<template>
    <nav class="mb-4 text-2xl font-semibold text-gray-800 flex items-center gap-1">
        <template v-for="(crumb, index) in items" :key="index">
            <template v-if="index > 0">
                <span class="text-gray-400">›</span>
            </template>

            <Link
                v-if="crumb.href"
                :href="crumb.href"
                class="text-blue-600 hover:underline"
            >
                {{ crumb.label }}
            </Link>

            <span v-else>
        {{ crumb.label }}
      </span>
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ items: Array })
</script>
