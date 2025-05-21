<template>
    <div class="p-4 space-y-6">
        <!-- Botonera de Estados -->
        <div class="flex gap-2 flex-wrap">
            <Button @click="changeState('started')">Iniciar</Button>
            <Button @click="changeState('finished')">Finalizar</Button>
            <Button @click="changeState('cancelled')">Cancelar</Button>
            <Button @click="changeState('frozen')">Congelar</Button>
            <Button @click="changeState('resumed')">Reanudar</Button>
        </div>

        <!-- Configuración del Formato -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <Label>Formato</Label>
                <select v-model="config.format" class="w-full border rounded p-2">
                    <option value="robin">Round Robin</option>
                    <option value="brackets">Brackets</option>
                    <option value="mixed">Mixto</option>
                </select>
            </div>

            <div v-if="config.format !== 'brackets'">
                <Label>Clasificados por grupo</Label>
                <select v-model="config.qualified" class="w-full border rounded p-2">
                    <option value="1">1º</option>
                    <option value="2">2 primeros</option>
                    <option value="3">3 primeros</option>
                    <option value="all">Todos</option>
                </select>
            </div>
        </div>

        <!-- Selector de Deporte -->
        <div>
            <Label>Deporte</Label>
            <select v-model="config.sport" class="w-full border rounded p-2">
                <option value="tenis">Tenis de mesa</option>
                <option value="futbol">Fútbol</option>
                <option value="padel">Pádel</option>
            </select>
        </div>

        <!-- Tabs de Grupos -->
        <Tabs v-model="activeGroup">
            <TabsList>
                <TabsTrigger v-for="group in groups" :key="group.id" :value="group.id">
                    {{ group.name }}
                </TabsTrigger>
            </TabsList>

            <TabsContent v-for="group in groups" :key="group.id" :value="group.id">
                <GroupMatches :group="group" :sport="config.sport" @result-updated="handleResult" />
            </TabsContent>
        </Tabs>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs'
import GroupMatches from './GroupMatches.vue'

const config = ref({
    format: 'robin',
    qualified: '2',
    sport: 'tenis'
})

const groups = ref([
    { id: 'group1', name: 'Grupo A', matches: [] },
    { id: 'group2', name: 'Grupo B', matches: [] }
])

const activeGroup = ref('group1')

function changeState(state) {
    console.log('Cambio de estado:', state)
    // Aquí se puede hacer la petición al backend para cambiar el estado de la jornada
}

function handleResult(matchResult) {
    console.log('Resultado actualizado:', matchResult)
    // Aquí se puede actualizar el marcador, clasificaciones, etc.
}
</script>

<style scoped>
select {
    background-color: white;
}
</style>
