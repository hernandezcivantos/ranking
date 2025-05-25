<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4">
                {{ club?.id ? 'Editar Club' : 'Nuevo Club' }}
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block mb-1">Nombre del club</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <div class="flex justify-end mt-6 gap-2">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        Guardar
                    </button>
                </div>
            </form>

            <!-- Botón de cierre superior -->
            <button @click="emit('close')" class="absolute top-3 right-4 text-gray-400 hover:text-black text-xl">
                &times;
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    club: Object
})

const emit = defineEmits(['close'])

const form = useForm({
    name: ''
})

watch(() => props.club, (club) => {
    if (club && club.id) {
        form.name = club.name || ''
    } else {
        form.reset()
    }
}, { immediate: true })

watch(() => props.show, (visible) => {
    if (!visible) {
        form.reset()
    }
})

const handleSubmit = () => {
    const isEdit = !!props.club?.id
    const url = isEdit ? `/clubs/${props.club.id}` : '/clubs'

    const method = isEdit ? 'put' : 'post'

    form[method](url, {
        onSuccess: () => {
            form.reset()
            emit('close')
        }
    })
}
</script>
