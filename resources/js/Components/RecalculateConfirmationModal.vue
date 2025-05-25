<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h2 class="text-lg font-semibold mb-4">Recalcular encuentros</h2>
            <p class="text-sm text-gray-700">
                Se va a comprobar si ha habido cambios en jugadores o grupos. No se perderá el estado actual de la jornada.
            </p>
            <p class="mt-2 text-sm text-gray-500">¿Deseas continuar?</p>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="onConfirm"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Sí, continuar
                </button>
                <button @click="onClose"
                        class="border px-4 py-2 rounded hover:bg-gray-100">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
const emit = defineEmits(['close', 'confirm']);

function onClose() {
    emit('close');
}

function onConfirm() {
    emit('confirm');
}
</script>
