<template>
  <Head title="Ranking"/>
  <AuthenticatedLayout>
    <template #header>
      <Breadcrumb :items="[
                { label: 'Panel', href: route('dashboard') },
                { label: 'Ligas', href: route('group.leagues.index', league.group_id) },
                { label: `${league.name}` }
              ]"/>
    </template>

    <div class="px-4 py-6">
      <div class="max-w-6xl mx-auto bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
          <h2 class="text-2xl font-bold text-gray-800">Ranking de Jugadores</h2>
        </div>

        <div v-if="players.length" class="overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase tracking-wider text-xs">
            <tr>
              <th class="px-4 py-3">#</th>
              <th class="px-4 py-3">Foto</th>
              <th class="px-4 py-3">Nombre</th>
              <th class="px-4 py-3">Puntos</th>
              <th class="px-4 py-3">Jugados</th>
              <th class="px-4 py-3">Victorias</th>
              <th class="px-4 py-3">Derrotas</th>
              <th class="px-4 py-3">División</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(player, index) in players"
                :key="player.id"
                :class="[
                  index === 0 ? 'bg-yellow-100' :
                  index === 1 ? 'bg-gray-200' :
                  index === 2 ? 'bg-orange-100' :
                  'bg-white',
                  'border-b'
                ]"
            >
              <td class="px-4 py-3 font-bold text-center">
                <span v-if="index === 0">🥇</span>
                <span v-else-if="index === 1">🥈</span>
                <span v-else-if="index === 2">🥉</span>
                <span v-else>#{{ index + 1 }}</span>
              </td>

              <td class="px-4 py-3">
                <img
                    :src="player.photo ? `/storage/${player.photo}` : '/images/default-image.png'"
                    class="w-10 h-10 rounded-full object-cover border"
                />
              </td>

              <td class="px-4 py-3 whitespace-nowrap">
                {{ player.first_name }} {{ player.last_name }}
              </td>

              <td class="px-4 py-3 text-right font-semibold text-gray-800">
                {{ player.pivot.points ?? 0 }}
              </td>

              <td class="px-4 py-3 text-center">{{ player.matches_played }}</td>
              <td class="px-4 py-3 text-center text-green-600 font-medium">{{ player.matches_won }}</td>
              <td class="px-4 py-3 text-center text-red-500 font-medium">{{ player.matches_lost }}</td>

              <td class="px-4 py-3">
                  <span
                      class="inline-block px-3 py-1 text-xs rounded-full font-medium text-white"
                      :class="getDivisionColor(player.division?.name)"
                  >
                    {{ player.division?.name ?? 'Sin división' }}
                  </span>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="px-6 py-4 text-sm text-gray-600">
          Esta liga aún no tiene jugadores registrados.
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import {defineProps} from 'vue'
import {Head} from "@inertiajs/vue3";

const props = defineProps({
  league: Object,
  players: Array,
})

function getDivisionColor(name) {
  if (!name) return 'bg-gray-400'

  if (name.includes('Honor')) return 'bg-blue-600'
  if (name.includes('Primera')) return 'bg-orange-500'
  if (name.includes('Segunda')) return 'bg-pink-600'
  if (name.includes('Nacional')) return 'bg-green-600'
  if (name.includes('Local')) return 'bg-gray-500'

  return 'bg-gray-400'
}
</script>
