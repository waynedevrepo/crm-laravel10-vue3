<template>
    <VDataTable
        :headers="tableHeaders.campaignDetail"
        :items="data"
        :items-per-page="options.itemsPerPage"
        :page="options.page"
        :show-select="showSelect"
        @update:options="options = $event"
        @click:row="goToDetail"
      >
        <template #bottom>
          <VCardText class="mt-8">
            <VRow>
              <VCol
                lg="2"
                cols="3"
              >
                <VTextField
                  v-model="options.itemsPerPage"
                  label="Rows per page:"
                  type="number"
                  min="-1"
                  max="15"
                  hide-details
                />
              </VCol>

              <VCol
                lg="10"
                cols="9"
                class="d-flex justify-end"
              >
                <VPagination
                  v-model="options.page"
                  total-visible="5"
                  :length="Math.ceil(data.length / options.itemsPerPage)"
                />
              </VCol>
            </VRow>
          </VCardText>
        </template>
      </VDataTable>
</template>
<script setup>
import { tableHeaders, tableOption } from '@/plugins/constant';
import { VDataTable } from 'vuetify/labs/VDataTable';

const props = defineProps({
  showSelect: {
    type: Boolean,
    required: false,
  },
  data: {
    type: Array,
    required: true
  }
})

const router = useRouter()
const data = toRef(props, 'data')

const options = ref(tableOption)

const goToDetail = (item) => {
  router.push(`/campaign-detail/${item.id}`)
}
</script>