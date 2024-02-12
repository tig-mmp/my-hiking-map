<template>
  <h1>{{ header }}</h1>
  <form class="p-fluid formgrid grid pb-6" @submit.prevent="validateForm">
    <div class="field col-12 md:col-12">
      <label>Título*</label>
      <InputText v-model="formObject.name" type="text" />
    </div>

    <div class="field col-12">
      <div class="grid pt-3 m-0">
        <FileUpload :url="uploadApi" :max-file-size="100000000" class="p-fileupload-buttonbar p-fileupload-content"
          mode="basic" name="file" choose-label="Adicionar ficheiro" auto @upload="afterUpload"
          @progress="setUploadProgress" />
        <div v-if="fileUploaded" class="col-3">
          <Button class="p-button-outlined" type="button" label="Cancelar" icon="pi pi-times" @click="cancelUpload" />
        </div>
      </div>
      <ProgressBar v-if="(isLoadingUpdate || isLoadingCreate) && uploadProgress !== 100" :value="uploadProgress"
        class="process-bar-separator" />
      <ProgressBar v-if="(isLoadingUpdate || isLoadingCreate) && (uploadProgress === 100 || uploadProgress == null)"
        class="text-center mb-2" mode="indeterminate" />
    </div>

    <div class="field col-12 md:col-12">
      <label>Ponto de começo</label>
      <div class="p-fluid formgrid grid">
        <div class="field col-12 md:col-4">
          <label>Distrito</label>
          <Dropdown v-model="formObject.startDistrictId" editable :options="districts" optionLabel="name" inputId="id" />
        </div>
        <div class="field col-12 md:col-4">
          <label>Concelho</label>
          <Dropdown v-model="formObject.startCountyId" editable :options="startCounties" optionLabel="name"
            inputId="id" />
        </div>
        <div class="field col-12 md:col-4">
          <label>Localização</label>
          <Dropdown v-model="formObject.startLocationId" editable :options="startLocations" optionLabel="name"
            inputId="id" />
        </div>
      </div>
    </div>
    <div class="field col-12 md:col-12">
      <label>Descrição</label>
      <Textarea v-model="formObject.description" rows="5" cols="30" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Distância</label>
      <InputNumber v-model="formObject.distance" :minFractionDigits="2" :maxFractionDigits="2" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Código da rota</label>
      <InputText v-model="formObject.routeCode" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Dificuldade</label>
      <InputNumber v-model="formObject.difficulty" :min="1" :max="10" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Paisagem</label>
      <InputNumber v-model="formObject.landscape" :min="1" :max="10" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Aproveitamento</label>
      <InputNumber v-model="formObject.enjoyment" :min="1" :max="10" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Url do trilho gravado</label>
      <InputText v-model="formObject.trackUrl" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Url do trilho oficial</label>
      <InputText v-model="formObject.officialUrl" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Grupo</label>
      <InputText v-model="formObject.groupName" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Guia</label>
      <InputText v-model="formObject.guide" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Número da semana</label>
      <InputNumber v-model="formObject.weekNumber" :min="1" :max="53" />
    </div>
    <div class="field col-12 md:col-6">
      <label>É moita?</label>
      <InputSwitch v-model="formObject.isMoita" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Duração</label>
      <Calendar v-model="formObject.duration" timeOnly />
    </div>
    <div class="field col-12 md:col-6">
      <label>Dia</label>
      <Calendar v-model="formObject.date" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Hora de começo</label>
      <Calendar v-model="formObject.startedAt" timeOnly />
    </div>
    <div class="field col-12 md:col-6">
      <label>Hora de finalização</label>
      <Calendar v-model="formObject.endedAt" timeOnly />
    </div>

    <div class="grid sidebar-footer p-2 ml-1">
      <div class="col-12 w-auto">
        <Button :disabled="isLoadingUpdate || isLoadingUpdate" :label="submitLabel" icon="pi pi-check" type="submit" />
      </div>
      <div class="col-12 w-auto">
        <Button :disabled="isLoadingUpdate || isLoadingUpdate" class="p-button-outlined" icon="pi pi-times"
          label="Cancelar" @click="cancel" />
      </div>
    </div>
  </form>
</template>

<script setup lang="ts">
import { trackFormDataType, type TrackForm } from "@/models/track/form";
import type { FileUploaded } from "@/models/file/uploaded";
import type { FileUploadProgressEvent, FileUploadUploadEvent } from "primevue/fileupload";
import { computed, ref, watch, type Ref, onMounted } from "vue";
import ProgressBar from "primevue/progressbar";
import useApiRoutes from "@/composables/api/useApiRoutes";
import { useToast } from "primevue/usetoast";
import useApiGet from "@/composables/api/useApiGet";
import useApiPut from "@/composables/api/useApiPut";
import useApiPost from "@/composables/api/useApiPost";
import { DistrictShort, districtShortDataType } from "@/models/locatinos/district/short";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import InputSwitch from "primevue/inputswitch";
import Calendar from "primevue/calendar";

const props = defineProps<{ id: number | null }>();
const emit = defineEmits(["changed", "cancel"]);

const toast = useToast();
const { tracksApi, getTracksApi, uploadApi, districtsApi } = useApiRoutes();

const fileUploaded: Ref<FileUploaded | null> = ref(null);
const uploadProgress: Ref<number> = ref(0);

const header = computed(() => `${!props.id ? "Criar" : "Editar"} trilho`);
const submitLabel = computed(() => !props.id ? "Criar" : "Guardar");
const startCounties = computed(() => formObject.value.startDistrictId && !!districts.value ? districts.value.find(d => formObject.value.startDistrictId === d.id)?.states : []);
const startLocations = computed(() => formObject.value.startCountyId && !!startCounties.value ? startCounties.value.find(c => formObject.value.startCountyId === c.id)?.locations : []);

watch(() => props.id, (val: number | null) => {
  if (val) {
    getData();
  }
});

const { load: loadTrack, data: formObject } = useApiGet<TrackForm>(toast, {});
const getData = () => {
  if (!props.id) return;
  loadTrack(getTracksApi(props.id), { dataType: trackFormDataType });
};

const validateForm = () => !props.id ? create() : update();

const { put, isLoading: isLoadingUpdate } = useApiPut(toast);
const update = () => {
  if (!props.id) return;
  put(getTracksApi(props.id), formObject.value).then(() => getData());
};

const { post, isLoading: isLoadingCreate } = useApiPost(toast);
const create = () => post(tracksApi, formObject.value).then(() => afterSubmit());

const afterSubmit = () => {
  emit("changed");
  setTimeout(() => cancel(), 1000);
};

const reset = () => formObject.value = {};
const cancel = () => {
  emit("cancel");
  reset();
};

const setUploadProgress = (event: FileUploadProgressEvent) => uploadProgress.value = event.progress;
const afterUpload = (request: FileUploadUploadEvent) => formObject.value.url = JSON.parse(request.xhr.response).data[0].url;
const cancelUpload = (): null => fileUploaded.value = null;

const { load: loadDistricts, data: districts } = useApiGet<DistrictShort[]>(toast, []);
const getDistricts = () => loadDistricts(districtsApi, { dataType: districtShortDataType });

onMounted(() => {
  if (props.id) {
    getData();
  }
  getDistricts();
});
</script>
