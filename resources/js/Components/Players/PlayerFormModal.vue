<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4">
                {{ props.player ? 'Editar Jugador' : 'Añadir Jugador' }}
            </h2>

            <!-- FORM FORZADO CON :key -->
            <form @submit.prevent="handleSubmit" class="space-y-4" :key="formKey">
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
                        <option v-for="division in divisions" :key="division.id" :value="division.id">
                            {{ division.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Foto</label>
                    <input type="file" @change="e => form.photo = e.target.files[0]" />
                    <div v-if="props.player?.photo" class="mt-2">
                        <img :src="`/storage/${props.player.photo}`" alt="Foto" class="h-24 rounded" />
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-2">
                    <button type="button" @click="emit('close')" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Guardar
                    </button>
                </div>
            </form>

            <!-- Botón cierre -->
            <button @click="emit('close')" class="absolute top-3 right-4 text-gray-400 hover:text-black text-xl">
                &times;
            </button>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, ref, computed } from 'vue';

const props = defineProps({
    show: Boolean,
    divisions: Array,
    player: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    first_name: '',
    last_name: '',
    paddle_type: '',
    division_id: '',
    photo: null,
});

// Forzar recarga del formulario con key única
const formKey = ref(Date.now());

watch(() => props.player, (player) => {
    if (player) {
        form.first_name = player.first_name || '';
        form.last_name = player.last_name || '';
        form.paddle_type = player.paddle_type || '';
        form.division_id = player.division_id || '';
        form.photo = null;
    } else {
        form.reset();
    }

    // Cambiar la key para forzar re-render
    formKey.value = Date.now();
}, { immediate: true });

watch(() => props.show, (visible) => {
    if (!visible) {
        form.reset();
    }
});

const handleSubmit = () => {
    const isEdit = !!props.player;
    const url = isEdit ? `/players/${props.player.id}` : '/players';

    form.post(url, {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};
</script>
