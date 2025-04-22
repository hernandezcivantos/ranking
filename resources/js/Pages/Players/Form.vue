<script setup>
import { reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    player: Object
});

const form = useForm({
    first_name: props.player?.first_name || '',
    last_name: props.player?.last_name || '',
    paddle_type: props.player?.paddle_type || '',
    division: props.player?.division || '',
    photo: null,
});

const submit = () => {
    const options = {
        forceFormData: true
    };

    if (props.player) {
        form.put(`/players/${props.player.id}`, {
            forceFormData: true,
            onSuccess: () => emit('close'),
        });
    } else {
        form.post('/players', options);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4 max-w-md">
        <div>
            <label class="block">Nombre</label>
            <input v-model="form.first_name" class="border rounded px-2 py-1 w-full" required />
        </div>
        <div>
            <label class="block">Apellidos</label>
            <input v-model="form.last_name" class="border rounded px-2 py-1 w-full" required />
        </div>
        <div>
            <label class="block">Tipo de pala</label>
            <input v-model="form.paddle_type" class="border rounded px-2 py-1 w-full" />
        </div>
        <div>
            <label class="block">División</label>
            <input v-model="form.division" class="border rounded px-2 py-1 w-full" />
        </div>
        <div>
            <label class="block">Foto</label>
            <input type="file" @change="e => form.photo = e.target.files[0]" />
            <div v-if="player?.photo" class="mt-2">
                <img :src="`/storage/${player.photo}`" alt="Foto" class="h-24 rounded" />
            </div>
        </div>
        <button :disabled="form.processing" class="bg-green-600 text-white px-4 py-2 rounded">
            {{ form.processing ? 'Guardando...' : 'Guardar' }}
        </button>
    </form>
</template>
