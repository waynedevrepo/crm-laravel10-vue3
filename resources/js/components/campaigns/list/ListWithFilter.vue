<script setup>
import axios from '@axios';
import FilterTabs from './FilterTabs.vue';
import FilteredList from './FilteredList.vue';

const props = defineProps({
  campaign_id: {
    type: Number,
    required: true
  }
})

const filterList = ref({})
const dataList = ref([])
const status = ref("Default")
const sub_status = ref("")

const getCampaignDetailList = () => {
  axios.post(`/admin/campaigns/${props.campaign_id}/detail`, {
    status: status.value,
    sub_status: sub_status.value
  })
    .then(res => {
      const _filterdList = {}
      const { counts_status, counts_substatus, list } = res.data

      for (var status of counts_status) {
        const key = status.progressStatus == '' ? 'Default' : status.progressStatus

        const items = {
          'All': status.count
        }
        if (key != 'Default')
          counts_substatus.filter(substatus => substatus.progressStatus == status.progressStatus)
            .forEach(substatus => {
              items[substatus.progressSubStatus] = substatus.count
            });

        _filterdList[`${key}`] = items
      }

      filterList.value = _filterdList
      dataList.value = list
    })
}

const setFilter = (filter) => {
  if (status.value != filter.status || sub_status.value != filter.sub_status) {
    status.value = filter.status
    sub_status.value = filter.sub_status
    getCampaignDetailList()
  }  
}

onMounted(() => {
  getCampaignDetailList()
})

</script>
<template>
    <div>
        <FilterTabs 
          v-if="Object.keys(filterList).length > 0" 
          :filterList="filterList"
          :status = "status"
          :sub_status="sub_status"
          @set-filter="setFilter" />

        <FilteredList 
          class="mt-2" 
          :data="dataList"/>
    </div>
</template>
