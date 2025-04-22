<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md text-center">
            <h2 class="text-xl font-semibold mb-4">¿Estás seguro?</h2>
            <p class="mb-6">Esta acción eliminará al jugador <strong>{{ playerName }}</strong> de forma permanente.</p>
            <div class="flex justify-end gap-4">
                <button @click="$emit('cancel')" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                <button @click="$emit('confirm')" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Eliminar</button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    playerName: String,
});
defineEmits(['confirm', 'cancel']);
</script>
