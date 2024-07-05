<template>
  <div>
    <VCard>
      <VCardItem>
        <VCardTitle>Campaign Detail</VCardTitle>
        <template v-if="isAdminOrTeamLeader()" #append>
          <ActionButton 
            class="mr-2"
            menuTitle="Assign" 
            @update-action="setAction"
            :menuList="assignList"
          />

          <ActionButton 
            class="mr-2"
            menuTitle="Lead Progress" 
            @update-action="setAction"
            :menuList="progressList" 
          />

          <VBtn 
            class="mr-2"
            color="success"
            rounded="pill"
            @click="() => setAction('list')">
            Manage
          </VBtn>

          <VBtn 
            color="error"
            rounded="pill"
            @click="() => setAction('blocking')">
            Blocking
          </VBtn>
        </template>
      </VCardItem>
      <VCardText>
        <div v-if="action == 'auto-assign'">
          <AutoAssign />
        </div>

        <div v-if="action == 'manual-assign'">
          <ManualAssign />
        </div>

        <div v-if="action == 'manual-assign2'">
          <ManualAssign2 />
        </div>

        <div v-if="action == 'list'">
          <ListWithFilter :campaign_id="id"/>
        </div>

        <div v-if="action == 'accumulate'">
          <AccumulateList />
        </div>

        <div v-if="action == 'today'">
          <DateAccumulateList />
        </div>

        <div v-if="action == 'blocking'">
          <Blocking />
        </div>

      </VCardText>
    </VCard>
  </div>
</template>
<script setup>
import ActionButton from "@/components/campaigns/ActionButton.vue";
import Blocking from "@/components/campaigns/Blocking.vue";
import AutoAssign from "@/components/campaigns/assign/AutoAssign.vue";
import ManualAssign from "@/components/campaigns/assign/ManualAssign.vue";
import ManualAssign2 from "@/components/campaigns/assign/ManualAssign2.vue";
import ListWithFilter from "@/components/campaigns/list/ListWithFilter.vue";
import AccumulateList from "@/components/campaigns/statistics/AccumulateList.vue";
import DateAccumulateList from "@/components/campaigns/statistics/DateAccumulateList.vue";
import { isAdminOrTeamLeader } from "@/plugins/auth";

const assignList = [
  {
    title: 'Auto Assign',
    value: 'auto-assign',
  },
  {
    title: 'Manual Assign',
    value: 'manual-assign',
  },
  {
    title: 'Manual Assign2',
    value: 'manual-assign2',
  },
]

const progressList = [
  {
    title: 'Accumulate',
    value: 'accumulate',
  },
  {
    title: 'Today',
    value: 'today',
  }
]

const route = useRoute()
const id = parseInt(route.params.id);
const action = ref("list")

const setAction = (name) => {
  action.value = name;
}

</script>

<style>
.w-full {
  width: 100%;
}
</style>