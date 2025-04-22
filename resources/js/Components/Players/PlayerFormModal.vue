<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    divisions: Array,
    player: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    first_name: props.player?.first_name || '',
    last_name: props.player?.last_name || '',
    paddle_type: props.player?.paddle_type || '',
    division_id: props.player?.division_id || '',
    photo: null,
});

const handleSubmit = () => {
    form.post('/players', {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};

watch(() => props.show, (newVal) => {
    if (!newVal) form.reset();
});
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Añadir Jugador</h2>

            <!-- FORM -->
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block mb-1">Nombre</label>
                    <input v-model="form.first_name" type="text" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block mb-1">Apellidos</label>
                    <input v-model="form.last_name" type="text" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block mb-1">División</label>
                    <select v-model="form.division_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Selecciona una división</option>
                        <option
                            v-for="division in props.divisions"
                            :key="division.id"
                            :value="division.id"
                        >
                            {{ division.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Foto</label>
                    <input type="file" @change="e => form.photo = e.target.files[0]" />
                </div>
                <div class="flex justify-end mt-6 gap-2">
                    <button type="button" @click="emit('close')" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Guardar
                    </button>
                </div>
            </form>

            <!-- Botón cierre (X) -->
            <button @click="emit('close')" class="absolute top-3 right-4 text-gray-400 hover:text-black text-xl">&times;</button>
        </div>
    </div>
</template>
