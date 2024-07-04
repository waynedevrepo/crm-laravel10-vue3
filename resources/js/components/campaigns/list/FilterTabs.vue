<script setup>
const props = defineProps({
  filterList: {
    type: Object,
    required: false,
  },
})

const emit = defineEmits([
    'set-filter'
])

const currentFilter = ref("Default");

const setFilter = (label, sitem) => {
    console.log(label, sitem)
    emit('set-filter', {
        status: label, 
        sub_status: sitem
    })
}

</script>

<template>
    <div class="filter-tabs">        
        <div class="d-flex w-full">
          <small>Filter: </small>
        </div>
        <div class="mb-2">
            <VMenu
                v-for="(filter, label) in props.filterList"
                :key="label">

                <template #activator="{ props }">
                    <VBtn
                        v-if="filter.All > 0"
                        variant="text"
                        :color="label == currentFilter ? 'primary' : 'black'"
                        v-bind="props"
                    >
                        <VBadge
                            class="custom__badge mr-4"
                            :content="filter.All"
                            :offset-x="-8"
                            :offset-y="-8">
                            {{ label }}
                        </VBadge>
    
                        <VIcon 
                            v-if="Object.keys(filter).length > 1"
                            icon="tabler-chevron-down" 
                            />
                    </VBtn>
                </template>

                <VList v-if="Object.keys(filter).length > 1">
                    <VListItem
                        v-for="(scount, sitem)  in filter"
                        :key="sitem"
                        @click="setFilter(label, sitem)"
                        >                        
                        <VBadge
                            class="custom__badge"
                            :content="scount"
                            :offset-x="-18"
                            :offset-y="6"
                        >
                            {{ sitem }}
                        </VBadge>
                    </VListItem>
                </VList>
            </VMenu>
        </div>
    </div>
  
</template>
<style lang="scss">
.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-block-start: -10px;
    padding: 10px;
    background-color: #f4f4f4;
    border-radius: 10px;
}
.custom__badge .v-badge__badge {
    font-size: 0.7rem !important;
    height: 1.2rem !important;
    border-radius: 10px !important;
}
.v-list-item__content {
    padding-right: 30px;
}
.w-full {
    width: 100%;
}
</style>