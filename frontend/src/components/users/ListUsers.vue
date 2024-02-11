<template>
  <DataTable :value="users" responsive-layout="scroll"
    class="p-datatable-striped p-datatable-gridlines p-datatable-sm pb-6 d-grid" :loading="isLoading">
    <template #empty>
      <span>Não foram encontrados utilizadores.</span>
    </template>
    <template #header>
      <Button v-tooltip.left="'Criar utilizador'" class="p-button-rounded f-right" icon="pi pi-plus"
        @click="setForm(true, null)" />
    </template>
    <Column field="username" header="Username" sortable />
    <Column header-style="width: 150px">
      <template #body="slotProps">
        <Button v-tooltip.bottom="'Editar'" class="p-button-rounded p-button-success mr-2" icon="pi pi-pencil"
          @click="setForm(true, slotProps.data.id)" />
      </template>
    </Column>
  </DataTable>
  <Sidebar v-model:visible="showForm" :dismissable="false" class="p-sidebar-md" position="right">
    <UserForm :id="idToEdit" @changed="getData" @cancel="setForm(false, null)" />
  </Sidebar>
</template>

<script setup lang="ts">
import { userListDataType, type UserList } from "@/models/user/list";
import { defineAsyncComponent, onMounted, ref, type Ref } from "vue";
import { useToast } from "primevue/usetoast";
import useApiGet from "@/composables/api/useApiGet";
import useApiRoutes from "@/composables/api/useApiRoutes";

const UserForm = defineAsyncComponent(() => import("@/components/users/UserForm.vue"));

const toast = useToast();
const { usersApi } = useApiRoutes();

const idToEdit: Ref<number | null> = ref(null);
const showForm: Ref<boolean> = ref(false);

const { load: load, data: users, isLoading } = useApiGet<UserList[]>(toast, []);
const loadData = () => load(usersApi, { dataType: userListDataType });

const setForm = (showFormVal: boolean, id: number | null) => {
  showForm.value = showFormVal;
  idToEdit.value = id;
};

const getData = () => loadData();

onMounted(() => getData());
</script>
