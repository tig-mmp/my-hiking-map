<template>
    <div v-if="formObject" class="grid">
        <div class="field col-12 md:col-6">
            <label>Nome</label>
            <InputText v-model="formObject.name" type="text" />
        </div>
        <div class="field col-12 md:col-6">
            <label>Tipo</label>
            <Dropdown v-model="formObject.landmarkTypeId" editable :options="landmarkTypes" optionLabel="name"
                optionValue="id" inputId="id" />
        </div>
        <div class="field col-12">
            <div class="grid pt-3 m-0">
                <FileUpload :url="uploadApi" mode="basic" name="file" choose-label="Adicionar ficheiro" auto
                    @upload="afterUpload" @progress="setUploadProgress" accept="image/*" />
                <div v-if="formObject.file && !formObject.file.id" class="col-3">
                    <Button class="p-button-outlined" type="button" label="Cancelar" icon="pi pi-times"
                        @click="cancelUpload" />
                </div>
                <div v-if="formObject.file && formObject.file.url">
                    <Image :src="`${BASE}${formObject.file?.url}`" :alt="formObject.file?.name" height="150" class="w-auto"
                        preview />
                </div>
            </div>
            <ProgressBar v-if="uploadProgress > 0 && uploadProgress < 100" :value="uploadProgress" />
        </div>

        <div v-if="!!coordinate" class="field col-12">
            <Button type="button" :label="showMap ? 'Esconder mapa' : 'Mostrar mapa'" icon="pi pi-map" class="w-max"
                @click="showMap = !showMap" />
        </div>
        <div v-if="showMap && !!coordinate" class="field col-12 h-8">
            <MapView :coordinate="coordinate" />
        </div>

        <div v-if="formObject.point" class="field col-12 md:col-3">
            <label>Elevação</label>
            <InputNumber v-model="formObject.point.elevation" :maxFractionDigits="3" />
        </div>
        <div v-if="formObject.point" class="field col-12 md:col-3">
            <label>Latitude</label>
            <InputNumber v-model="formObject.point.latitude" :maxFractionDigits="7" />
        </div>
        <div v-if="formObject.point" class="field col-12 md:col-3">
            <label>Longitude</label>
            <InputNumber v-model="formObject.point.longitude" :maxFractionDigits="7" />
        </div>
        <div v-if="formObject.point" class="field col-12 md:col-3">
            <label>Data</label>
            <Calendar v-model="formObject.point.date" />
        </div>
    </div>
</template>

<script setup lang="ts">
import useApiGet from '@/composables/api/useApiGet';
import useApiRoutes from '@/composables/api/useApiRoutes';
import useEnvironment from '@/composables/useEnvironment';
import { LandmarkForm } from '@/models/landmark/form';
import { LandmarkTypeShort, landmarkTypeShortDataType } from '@/models/landmarkType/short';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import { FileUploadProgressEvent, FileUploadUploadEvent } from 'primevue/fileupload';
import Image from 'primevue/image';
import InputNumber from 'primevue/inputnumber';
import ProgressBar from 'primevue/progressbar';
import { useToast } from 'primevue/usetoast';
import { Ref, computed, defineAsyncComponent, onMounted, ref } from 'vue';

const MapView = defineAsyncComponent(() => import("@/components/MapView.vue"));

const formObject = defineModel<LandmarkForm>({ default: { file: null, point: null } });

const toast = useToast();
const { landmarkTypesApi, uploadApi } = useApiRoutes();
const { BASE } = useEnvironment();

const uploadProgress: Ref<number> = ref(0);
const showMap: Ref<boolean> = ref(false);

const coordinate = computed((): number[] | null => formObject.value.point ? [formObject.value.point.longitude, formObject.value.point.latitude] : null);

const { load: loadLandmarkTypes, data: landmarkTypes } = useApiGet<LandmarkTypeShort[]>(toast, []);
const getLandmarkTypes = () => loadLandmarkTypes(landmarkTypesApi, { dataType: landmarkTypeShortDataType });

const setUploadProgress = (event: FileUploadProgressEvent) => uploadProgress.value = event.progress;
const afterUpload = (request: FileUploadUploadEvent) => formObject.value.file = JSON.parse(request.xhr.response).data;
const cancelUpload = () => formObject.value.file = null;

onMounted(() => {
    getLandmarkTypes();
    if (!formObject.value.point) {
        formObject.value.point = {};
    }
});
</script>
