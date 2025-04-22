<template>
    <Modal :show="show" @close="close">
        <template #header>
            <h2 class="text-lg font-bold">Crear Torneo</h2>
        </template>

        <template #body>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input v-model="form.nombre" type="text" class="w-full rounded border-gray-300" />
                    <p v-if="form.errors.nombre" class="text-red-500 text-sm mt-1">{{ form.errors.nombre }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Fecha de inicio</label>
                    <input v-model="form.fecha_inicio" type="date" class="w-full rounded border-gray-300" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Fecha de fin</label>
                    <input v-model="form.fecha_fin" type="date" class="w-full rounded border-gray-300" />
                    <p v-if="form.errors.fecha_fin" class="text-red-500 text-sm mt-1">{{ form.errors.fecha_fin }}</p>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="pt-5">
                <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Guardar
                </button>
                <button @click="close" class="ml-2 px-4 py-2 text-gray-600 hover:text-gray-800">Cancelar</button>
            </div>
        </template>
    </Modal>
</template>

<script setup>
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({ show: Boolean })
const emit = defineEmits(['close', 'created'])

const form = useForm({
    nombre: '',
    fecha_inicio: '',
    fecha_fin: ''
})

async function submit() {
    try {
        const response = await axios.post('/torneos', form.data())

        emit('created', response.data) // ⚡ Emitimos el torneo creado
        close()
    } catch (error) {
        if (error.response?.status === 422) {
            form.setErrors(error.response.data.errors)
        } else {
            console.error('Error al crear torneo:', error)
        }
    }
}

function close() {
    emit('close')
    form.reset()
    form.clearErrors()
}
</script>
