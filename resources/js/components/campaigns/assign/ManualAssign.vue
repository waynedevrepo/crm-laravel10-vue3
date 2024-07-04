<script setup>
import FilteredList from '../list/FilteredList.vue';
import Filter from './Filter.vue';

const manualAssign = () => {
    console.log('here');
}

</script>
<template>
    <div>
        <div class="v-card-title">Manual Assign</div>
        <VRow class="my-4">
            <VCol cols="12" md="3">
                <Filter />
            </VCol>
            <VCol cols="12" md="9">
                <div class="mx-2">
                    <VAlert
                        variant="outlined"
                        color="error">
                        Note: This action will replace on assigned data, if data assigned, and each time will limit 200 result
                    </VAlert>

                    <div class="d-flex align-center justify-center my-4">
                        <VSelect
                            :items="users"
                            variant="outlined"
                            label="Assign Data To"
                        />

                        <VBtn 
                            class="ml-2"
                            @click="manualAssign">
                            Assign
                        </VBtn>
                    </div>

                    <FilteredList showSelect />
                </div>

            </VCol>
        </VRow>
    </div>
</template>