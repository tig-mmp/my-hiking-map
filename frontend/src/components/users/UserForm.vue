<template>
  <h1>{{ header }}</h1>
  <form class="p-fluid formgrid grid pb-6" @submit.prevent="validateForm">
    <div class="field col-12 md:col-6">
      <label>Username*</label>
      <InputText v-model="formObject.username" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Role*</label>
      <InputText v-model="formObject.role" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Password*</label>
      <Password v-model="formObject.password" :feedback="false" />
    </div>

    <div class="grid sidebar-footer p-2 ml-1">
      <div class="col-12 w-auto">
        <Button :disabled="isLoadingUpdate || isLoadingUpdate" :label="submitLabel" icon="pi pi-check" type="submit" />
      </div>
      <div class="col-12 w-auto">
        <Button :disabled="isLoadingCreate || isLoadingCreate" class="p-button-outlined" icon="pi pi-times"
          label="Cancelar" @click="cancel" />
      </div>
    </div>
  </form>
</template>

<script setup lang="ts">
import { userFormDataType, type UserForm } from "@/models/user/form";
import useApiGet from "@/composables/api/useApiGet";
import useApiPost from "@/composables/api/useApiPost";
import useApiPut from "@/composables/api/useApiPut";
import useApiRoutes from "@/composables/api/useApiRoutes";
import { useToast } from "primevue/usetoast";
import { computed, watch, onMounted } from "vue";
import Password from "primevue/password";

const props = defineProps<{ id: number | null }>();
const emit = defineEmits(["changed", "cancel"]);

const toast = useToast();
const { usersApi, getUsersApi } = useApiRoutes();

const header = computed(() => `${!props.id ? "Criar" : "Editar"} utilizador`);
const submitLabel = computed(() => !props.id ? "Criar" : "Guardar");

watch(() => props.id, (val: number | null) => {
  if (val) {
    getData();
  }
});

const { load: loadUser, data: formObject } = useApiGet<UserForm>(toast, { id: null });
const getData = () => {
  if (!props.id) return;
  loadUser(getUsersApi(props.id), { dataType: userFormDataType });
};

const validateForm = () => !props.id ? create() : update();

const { put, isLoading: isLoadingUpdate } = useApiPut(toast);
const update = () => {
  if (!props.id) return;
  put(getUsersApi(props.id), formObject.value).then(() => getData());
};

const { post, isLoading: isLoadingCreate } = useApiPost(toast);
const create = () => post(usersApi, formObject.value).then(() => afterSubmit());

const afterSubmit = () => {
  emit("changed");
  setTimeout(() => cancel(), 1000);
};

const reset = () => formObject.value = { id: null };
const cancel = () => {
  emit("cancel");
  reset();
};

onMounted(() => {
  if (props.id) {
    getData();
  }
});
</script>
