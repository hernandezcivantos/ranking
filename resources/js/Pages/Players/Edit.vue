<script setup>
import Form from './Form.vue';
defineProps({ player: Object });
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Editar Jugador</h1>
        <Form :player="player" />
    </div>
</template>
