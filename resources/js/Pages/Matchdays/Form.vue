<template>
    <Head :title="form.id ? 'Editar jornada' : 'Crear jornada'" />

    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[
        { label: 'Panel', href: route('dashboard') },
        { label: 'Ligas', href: route('group.leagues.index', league.group_id) },
        { label: league.name, href: route('leagues.ranking', league.id) },
        { label: form.id ? 'Editar jornada' : 'Crear jornada' }
      ]" />
        </template>

        <div class="px-8 py-6 max-w-xl space-y-6">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input v-model="form.name" class="w-full border rounded px-3 py-1" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea v-model="form.description" class="w-full border rounded px-3 py-1" rows="3" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" v-model="form.date" class="w-full border rounded px-3 py-1" />
                </div>

                <div>
                    <button
                        type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar jornada' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    league: Object,
    matchday: {
        type: Object,
        default: null
    }
})

const form = useForm({
    id: props.matchday?.id ?? null,
    name: props.matchday?.name ?? '',
    description: props.matchday?.description ?? '',
    date: props.matchday?.date ?? '',
})

function submit() {
    const routeName = form.id ? 'matchdays.update' : 'matchdays.store'
    const params = form.id ? [props.league.id, form.id] : props.league.id

    form.post(route(routeName, params), {
        forceFormData: true,
        preserveScroll: true
    })
}
</script>
