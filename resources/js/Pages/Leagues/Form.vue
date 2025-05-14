<template>
  <Head title="Ligas" />

  <AuthenticatedLayout>
    <template #header>
      <Breadcrumb :items="[
        { label: 'Panel', href: route('dashboard') },
        { label: 'Ligas', href: route('group.leagues.index', group.id) },
        { label: league?.id ? 'Editar liga' : 'Crear liga' }
      ]" />
    </template>

    <div class="px-8 py-6 space-y-6">
      <form @submit.prevent="submit" class="space-y-6 max-w-xl">

        <div>
          <label class="block text-sm font-medium text-gray-700">Nombre</label>
          <input v-model="form.name" class="w-full border rounded px-3 py-1" required />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-1" />
        </div>

        <div>
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Reglas de puntuación</h3>

          <div
              v-for="(rule, index) in form.point_rules"
              :key="index"
              class="flex items-center gap-2 mb-2"
          >
            <input v-model="rule.position" type="number" min="1" placeholder="Posición"
                   class="w-24 border rounded px-2 py-1" />
            <input v-model="rule.points" type="number" min="0" placeholder="Puntos"
                   class="w-24 border rounded px-2 py-1" />
            <button type="button" @click="removeRule(index)" class="text-red-600 text-sm">Eliminar</button>
          </div>

          <button type="button" @click="addRule" class="text-blue-600 hover:underline text-sm">
            + Añadir regla
          </button>
        </div>

        <div>
          <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          >
            {{ form.processing ? 'Guardando...' : 'Guardar liga' }}
          </button>

          <div v-if="league?.slug && group?.slug" class="mt-4 text-sm">
            <span class="font-semibold text-gray-700">Enlace público:</span>
            <a
                :href="`/public/${group.slug}/${league.slug}`"
                target="_blank"
                class="text-blue-600 hover:underline ml-2"
            >
              /public/{{ group.slug }}/{{ league.slug }}
            </a>
          </div>
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
  group: Object,
  league: {
    type: Object,
    default: null,
  },
  existingRules: {
    type: Array,
    default: () => [],
  },
})

const form = useForm({
  id: props.league?.id ?? null,
  name: props.league?.name ?? '',
  description: props.league?.description ?? '',
  point_rules: props.existingRules.length
      ? props.existingRules.map(rule => ({
        position: rule.position,
        points: rule.points,
      }))
      : [{ position: 1, points: 10 }]
})

function addRule() {
  form.point_rules.push({ position: '', points: '' })
}

function removeRule(index) {
  form.point_rules.splice(index, 1)
}

function submit() {
  if (!form.name) return alert('El nombre es obligatorio')
  if (!form.point_rules.length) return alert('Debes definir al menos una regla de puntuación')

  const groupId = props.group.id

  if (form.id) {
    form.post(route('group.leagues.update', [groupId, form.id]), {
      data: {
        ...form.data(),
        _method: 'put'  // 👈 esto sí lo procesa Laravel
      },
      forceFormData: true
    })
  } else {
    form.post(route('group.leagues.store', groupId), {
      data: form.data(),
      forceFormData: true
    })
  }
}

</script>
