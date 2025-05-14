<template>
    <Modal :show="show" @close="closeModal">
        <div class="bg-white p-6 rounded shadow w-full">
            <h2 class="text-xl font-bold mb-4">
                {{ isEditMode ? 'Editar liga' : 'Crear liga' }}
            </h2>

            <form @submit.prevent="submitForm" class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded border border-gray-300 px-3 py-2"
                        required
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <!-- Slug
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <input
                        v-model="form.slug"
                        type="text"
                        class="w-full rounded border border-gray-300 px-3 py-2"
                        required
                        disabled
                    />
                    <InputError :message="form.errors.slug" class="mt-1" />
                </div>
                -->
                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200"
                        @click="closeModal"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        {{ isEditMode ? 'Guardar cambios' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'
import InputError from '@/Components/InputError.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    show: Boolean,
    league: Object,
    groupId: Number,
})
const emit = defineEmits(['close'])

const isEditMode = computed(() => !!props.league)

const form = useForm({
    name: '',
    slug: '',
})

watch(() => props.league, (league) => {
    if (league) {
        form.name = league.name
        form.slug = league.slug
    } else {
        form.reset()
    }
})

function closeModal() {
    form.reset()
    emit('close')
}

function submitForm() {
    const routeName = isEditMode.value ? 'group.leagues.update' : 'group.leagues.store'
    const params = isEditMode.value
        ? { group: props.groupId, league: props.league.id }
        : { group: props.groupId }

    form.post(route(routeName, params), {
        preserveScroll: true,
        onSuccess: closeModal,
    })
}
</script>
