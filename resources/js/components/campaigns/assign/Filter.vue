<script setup>
const users = ref([
    '(1952) NICOLE',
    '(1953) JENNY',
])

</script>
<template>
    <div>
        <div class="w-full align-center justify-between mb-4">
            <span class="mr-2">Filter</span>
            <VBtn>
                Apply
            </VBtn> 
        </div>
        <VExpansionPanels multiple>
            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Basic: </VExpansionPanelTitle>
                <VExpansionPanelText>
                    <VSelect
                        class="mt-2"
                        :items="users"
                        variant="outlined"
                        label="Assigned User"
                    />

                    <VSelect
                        class="mt-2"
                        :items="users"
                        variant="outlined"
                        label="Project Status"
                    />

                    <VSelect
                        class="mt-2"
                        :items="users"
                        variant="outlined"
                        label="Sub Status"
                    />

                    <AppDateTimePicker
                        class="mt-2"
                        label="Import Date"
                        v-model="dateRange"
                    />

                    <VTextField
                        class="mt-2"
                        label="Id"
                    />

                    <VTextField
                        class="mt-2"
                        label="Current Status Date"
                    />                            
                    
                    <VTextField
                        class="mt-2"
                        label="Product Group"
                    />

                    <VTextField
                        class="mt-2"
                        label="Product Name"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Applicant: </VExpansionPanelTitle>
                <VExpansionPanelText>

                    <VTextField
                        class="mt-2"
                        label="Applicant Type Name"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Company"
                    />

                    <VTextField
                        class="mt-2"
                        label="Application Business Registration Number"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Name"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Identity"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Gender"
                    />

                    <VTextField
                        class="mt-2"
                        label="Race Name"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Mobile"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Fax"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Other Phone"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Address1"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Address2"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Address3"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant City"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant State"
                    />

                    <VTextField
                        class="mt-2"
                        label="Applicant Email"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Instanllation: </VExpansionPanelTitle>
                <VExpansionPanelText>
                    <VTextField
                        class="mt-2"
                        label="Installation Address1"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Address2"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Address3"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation City"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation State"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Property Type"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Contact Person"
                    />

                    <VTextField
                        class="mt-2"
                        label="Installation Contact Number"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Billing: </VExpansionPanelTitle>
                <VExpansionPanelText>
                    <VTextField
                        class="mt-2"
                        label="Billing Address1"
                    />
                    
                    <VTextField
                        class="mt-2"
                        label="Billing Address2"
                    />
                    
                    <VTextField
                        class="mt-2"
                        label="Billing Address3"
                    />
                    
                    <VTextField
                        class="mt-2"
                        label="Billing Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        label="Billing City"
                    />
                    
                    <VTextField
                        class="mt-2"
                        label="Billing State"
                    />

                </VExpansionPanelText>
            </VExpansionPanel>

        </VExpansionPanels>
    </div>
</template>