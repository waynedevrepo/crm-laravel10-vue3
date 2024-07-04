<script setup>
import axios from '@axios';
import { toRef } from 'vue';

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: false
    },
    isUpdate: {
      type: Boolean,
      required: true
    },
    campaign: {
      type: Object,
      required: true
    },
    categories: {
      type: Array,
      required: true
    },
    sub_categories: {
      type: Array,
      required: true
    }
})

const emit = defineEmits([
    'close-dialog'
])

const isDialogVisible = toRef(props, 'isDialogVisible')
const campaign = toRef(props, 'campaign')

const categories = toRef(props, 'categories')

const sub_categories = toRef(props, 'sub_categories')

const filtered_subcategories = computed(()=> {
  const category = campaign.value.category;
  return sub_categories.value.filter(item => item.parent == category)
})

const isError = computed(() => error.value.length > 0)
const error = ref('')

const close = () => {
    emit('close-dialog', false)
}

const save = () => {
  const _campaign = campaign.value
  if (_campaign.name.length == 0) {
    error.value = 'Campaign Name must be not empty.'
    return;
  }

  if (_campaign.company.length == 0) {
    error.value = 'Company must be not empty.'
    return;
  }

  if (_campaign.category.length == 0) {
    error.value = 'Category must be not empty.'
    return;
  }

  if (_campaign.sub_category.length == 0) {
    error.value = 'Sub Category must be not empty.'
    return;
  }

  if (_campaign.start_date.length == 0) {
    error.value = 'You must select start date.'
    return;
  }

  if (_campaign.end_date.length == 0) {
    error.value = 'You must select end date.'
    return;
  }

  if (_campaign.status != 'active' && _campaign.status != 'inactive') {
    error.value = 'Status is wrong.'
    return;
  }

  _campaign.start_date = new Date(_campaign.start_date).toISOString().slice(0, 19).replace('T', ' ')
  _campaign.end_date = new Date(_campaign.end_date).toISOString().slice(0, 19).replace('T', ' ')

  _campaign.category = categories.value.find(item => item.value == _campaign.category).title
  _campaign.sub_category = sub_categories.value.find(item => item.value == _campaign.sub_category).title

  if (props.isUpdate)
    axios.put('/admin/campaigns', _campaign)
      .then((res) => {
        emit('close-dialog', true)
      })
  else
    axios.post('/admin/campaigns', _campaign)
      .then((res) => {
        emit('close-dialog', true)
      })

}

onMounted(() => {
  if (categories.length > 0) {
    campaign.value.category = categories[0].value
  }
})

</script>

<template>
  
  <VDialog
    v-model="isDialogVisible"
    max-width="720"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="close" />

    <!-- Dialog Content -->
    <VCard title="Campaign Detail">
      <VCardText class="px-8">
        <VRow class="mt-4">
          <VCol
            cols="12"
            md="6"
          >
            <AppTextField
              v-model="campaign.name"
              label="Campaign Name"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <AppTextField
              v-model="campaign.company"
              label="Company"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <AppSelect
              v-model="campaign.category"
              :items="categories"
              label="Category"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >

            <AppSelect
              v-model="campaign.sub_category"
              :items="filtered_subcategories"
              label="Sub Category"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VLabel
              for="start-date"
              class="mb-1 text-body-2 text-high-emphasis"
            >
              Start Date
            </VLabel>

            <AppDateTimePicker
              id="end-date"
              class="mr-4"
              v-model="campaign.start_date"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VLabel
              for="end-date"
              class="mb-1 text-body-2 text-high-emphasis"
            >
              End Date
            </VLabel>
            
            <AppDateTimePicker
              class="mr-4"
              v-model="campaign.end_date"
            />
          </VCol>
          <VCol 
            cols="12"
            md="6">
            <AppTextField
              v-model="campaign.today_sales"
              label="Today Sales"
              type="number"
            />
          </VCol>
          <VCol 
            cols="12"
            md="6">
            <AppTextField
              v-model="campaign.total_sales"
              label="Total Sales"
              type="number"
            />
          </VCol>
          
        </VRow>
      </VCardText>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="close"
        >
          Close
        </VBtn>
        <VBtn @click="save">
          Save
        </VBtn>
      </VCardText>
    </VCard>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ error }}
    </VSnackbar>
  </VDialog>
</template>