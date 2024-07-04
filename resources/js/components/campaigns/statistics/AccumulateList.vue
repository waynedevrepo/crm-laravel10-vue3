<script setup>
import StatisticsList from './StatisticsList.vue';

const data = ref([])

</script>

<template>
    <div>
        <div class="v-card-title">Accumulated Data</div>
        <StatisticsList :data="data" />
    </div>
</template>