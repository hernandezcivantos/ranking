<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, computed } from 'vue';
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    show: Boolean,
    player: Object,
    divisions: Array,
    clubs: Array,
});

const emit = defineEmits(['close']);

const form = useForm({
    first_name: '',
    last_name: '',
    paddle_type: '',
    division_id: '',
    club_id: '',
    photo: null,
});

watch(
    () => props.player,
    (newPlayer) => {
        if (newPlayer) {
            form.first_name = newPlayer.first_name || '';
            form.last_name = newPlayer.last_name || '';
            form.paddle_type = newPlayer.paddle_type || '';
            form.division_id = newPlayer.division_id || '';
            form.club_id = newPlayer.club_id || '';
            form.photo = null;
        }
    },
    { immediate: true }
);

const imageUrl = computed(() => {
    if (form.photo) return URL.createObjectURL(form.photo);
    return props.player?.photo ? `/storage/${props.player.photo}` : '/images/default-image.png';
});

const handleSubmit = () => {
    form.post(`/players/${props.player.id}`, {
        method: 'put', // le indica a Laravel que es un PUT "emulado"
        forceFormData: true,
        onSuccess: () => emit('close'),
        onError: (errors) => {
            console.error('Errores del formulario:', errors);
        }
    });
};

watch(() => props.show, (newVal) => {
    if (!newVal) form.reset();
});
</script>



<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Editar Jugador</h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block mb-1">Nombre</label>
                    <input v-model="form.first_name" type="text" class="w-full border rounded px-3 py-2" required />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <label class="block mb-1">Apellidos</label>
                    <input v-model="form.last_name" type="text" class="w-full border rounded px-3 py-2" required />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
                <div>
                    <label class="block mb-1">División</label>
                    <select v-model="form.division_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Selecciona una división</option>
                        <option
                            v-for="division in divisions"
                            :key="division.id"
                            :value="division.id"
                        >
                            {{ division.name }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.division_id" />
                </div>
                <div>
                    <label class="block mb-1">Club</label>
                    <select v-model="form.club_id" class="w-full border rounded px-3 py-2">
                        <option value="">Sin club</option>
                        <option v-for="club in clubs" :key="club.id" :value="club.id">
                            {{ club.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Foto</label>
                    <input type="file" @change="e => form.photo = e.target.files[0]" />
                    <div class="mt-2">
                        <img :src="imageUrl" alt="Foto" class="w-24 h-24 rounded-full object-cover border" />
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-2">
                    <button type="button" @click="emit('close')" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
                </div>
            </form>

            <button @click="emit('close')" class="absolute top-3 right-4 text-gray-400 hover:text-black text-xl">&times;</button>
        </div>
    </div>
</template>
