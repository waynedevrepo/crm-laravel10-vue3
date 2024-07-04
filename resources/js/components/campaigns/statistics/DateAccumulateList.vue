<script setup>
import StatisticsList from './StatisticsList.vue';

const data = ref([])
const dateRange = ref('')
const dateOptions = [
    "By Progress Updated Date",
    "By Import Date",
    "By Successful Date"
]

</script>

<template>
    <div>
        <div class="v-card-title">Filtered Accumulated Data</div>
        <div class="d-flex mb-2 mx-4 ">
            <small>Filter: </small>
        </div>
        <div class="d-flex mx-4 align-center">
            <VSelect
                class="mr-2 date-option"
                :items="dateOptions"
                label="Date Option"
            />
            
            <AppDateTimePicker
                class="mr-4 date-picker"
                v-model="dateRange"
                :config="{ mode: 'range' }"
            />

            <VBtn>
                Apply
            </VBtn>
        </div>
        <StatisticsList :data="data" />
    </div>
</template>
<style>
.date-option {
    width: 240px !important;
    flex: none !important;
}
.date-picker {
    width: 240px !important;
}
</style>