<template>
  <DataTable :value="tracks" responsive-layout="scroll" :loading="isLoading"
    class="p-datatable-striped p-datatable-gridlines p-datatable-sm pb-6 d-grid">
    <template #empty>
      <span>Não foram encontrados trilhos.</span>
    </template>
    <template #header>
      <Button v-tooltip.left="'Criar trilho'" class="p-button-rounded f-right" icon="pi pi-plus"
        @click="setForm(true, null)" />
    </template>
    <Column field="name" header="Nome" sortable />
    <Column header-style="width: 150px">
      <template #body="slotProps">
        <Button v-tooltip.bottom="'Editar'" class="p-button-rounded p-button-success mr-2" icon="pi pi-pencil"
          @click="setForm(true, slotProps.data.id)" />
      </template>
    </Column>
  </DataTable>
  <Sidebar v-model:visible="showForm" :dismissable="false" class="w-full" position="right"
    :header="`${!!idToEdit ? 'Criar' : 'Editar'} trilho`">
    <TrackForm :id="idToEdit" @changed="getData" @cancel="setForm(false, null)" />
  </Sidebar>
</template>

<script setup lang="ts">
import { trackListDataType, type TrackList } from "@/models/track/list";
import { defineAsyncComponent, onMounted, ref, type Ref } from "vue";
import { useToast } from "primevue/usetoast";
import useApiRoutes from "@/composables/api/useApiRoutes";
import useApiGet from "@/composables/api/useApiGet";

const TrackForm = defineAsyncComponent(() => import("@/components/tracks/TrackForm.vue"));

const toast = useToast();
const { tracksApi } = useApiRoutes();

const idToEdit: Ref<number | null> = ref(null);
const showForm: Ref<boolean> = ref(false);

const { load: load, data: tracks, isLoading } = useApiGet<TrackList[]>(toast, []);
const loadData = () => load(tracksApi, { dataType: trackListDataType });

const setForm = (showFormVal: boolean, id: number | null) => {
  showForm.value = showFormVal;
  idToEdit.value = id;
};

const getData = () => loadData();

onMounted(() => getData());
</script>
