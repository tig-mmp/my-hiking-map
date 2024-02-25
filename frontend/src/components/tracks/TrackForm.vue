<template>
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
    <div v-if="!formObject.isMoita" class="field col-12 md:col-12">
      <label>Descrição</label>
      <Textarea v-model="formObject.description" rows="5" cols="30" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Distância</label>
      <InputNumber v-model="formObject.distance" :minFractionDigits="2" :maxFractionDigits="2" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Elevação</label>
      <InputNumber v-model="formObject.slope" :minFractionDigits="2" :maxFractionDigits="2" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Código da rota</label>
      <InputText v-model="formObject.routeCode" type="text" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Dificuldade</label>
      <InputNumber v-model="formObject.difficulty" :min="1" :max="10" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Paisagem</label>
      <InputNumber v-model="formObject.landscape" :min="1" :max="10" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Aproveitamento</label>
      <InputNumber v-model="formObject.enjoyment" :min="1" :max="10" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Url do trilho gravado</label>
      <InputText v-model="formObject.trackUrl" type="text" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Url do trilho oficial</label>
      <InputText v-model="formObject.officialUrl" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Grupo</label>
      <InputText v-model="formObject.groupName" type="text" />
    </div>
    <div v-if="!formObject.isMoita" class="field col-12 md:col-6">
      <label>Guia</label>
      <InputText v-model="formObject.guide" type="text" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Número da semana</label>
      <InputNumber v-model="formObject.weekNumber" :min="1" :max="53" />
    </div>
    <div class="field col-12 md:col-6">
      <label>É moita?</label>
      <InputSwitch v-model="formObject.isMoita" class="flex" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Duração</label>
      <Calendar v-model="formObject.duration" timeOnly dateFormat="yy-mm-dd" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Dia</label>
      <Calendar v-model="formObject.date" dateFormat="yy-mm-dd" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Hora de começo</label>
      <Calendar v-model="formObject.startTime" timeOnly dateFormat="yy-mm-dd" />
    </div>
    <div class="field col-12 md:col-6">
      <label>Hora de finalização</label>
      <Calendar v-model="formObject.endTime" timeOnly dateFormat="yy-mm-dd" />
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
import { DistrictShort, districtShortDataType } from "@/models/locations/district/short";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import InputSwitch from "primevue/inputswitch";
import Calendar from "primevue/calendar";
import { PointForm } from "@/models/point/form";
import { LandmarkForm } from "@/models/landmark/form";
import dayjs from "dayjs";
import { getDistance } from "geolib";

const props = defineProps<{ id: number | null }>();
const emit = defineEmits(["changed", "cancel"]);

const toast = useToast();
const { tracksApi, getTracksApi, uploadApi, districtsApi, fileApi } = useApiRoutes();

const fileUploaded: Ref<FileUploaded | null> = ref(null);
const uploadProgress: Ref<number> = ref(0);

let didReset = false;

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
const afterUpload = (request: FileUploadUploadEvent) => {
  formObject.value.fileUrl = JSON.parse(request.xhr.response).data.url;
  getFile();
}
const cancelUpload = (): null => fileUploaded.value = null;
const { load: loadFile } = useApiGet<string>(toast, "");
const getFile = () => {
  if (!formObject.value.fileUrl) return;
  loadFile(fileApi, { "url": formObject.value.fileUrl.replace(/\.[^.]+$/, "") })
    .then((response) => {
      if (!response || typeof response.data !== "string") return;
      const gpxDoc = new DOMParser().parseFromString(response.data, "text/xml");
      if (!gpxDoc) {
        return;
      }
      formObject.value.isMoita = false;
      getDataFromDocument(gpxDoc);
    });
};
const getDataFromDocument = (gpxDoc: Document) => {
  setUrl(gpxDoc);
  setName(gpxDoc);

  formObject.value.points = [];
  let previousDate: Date | null = null;
  const trkpts = gpxDoc.querySelectorAll("trkpt");
  for (const trkpt of trkpts) {
    const point = setPoint(trkpt);
    if (!point || !formObject.value.points) {
      continue;
    }
    setIsMoita(point);
    if (setReset(previousDate, point.date)) {
      break;
    }
    formObject.value.points.push(point);
    previousDate = point.date;
  }

  if (formObject.value.isMoita) {
    setMoitaData();
  }
  setLandmarks(gpxDoc);
  setDateTimes();
  setDistances();
}

const setUrl = (gpxDoc: Document) => {
  const linkElement = gpxDoc.querySelector("link[href*=\"walking-trails\"]");
  if (linkElement) {
    const url = linkElement.getAttribute("href");
    if (url) {
      const parts = url.split("/");
      const lastPart = parts[parts.length - 1];
      const wikilocId = lastPart.match(/\d+/);
      formObject.value.trackUrl = url.replace(lastPart, `--${wikilocId}`);
    }
  }
};
const setName = (gpxDoc: Document) => {
  const name = gpxDoc.querySelector("metadata name");
  if (name?.textContent) {
    formObject.value.name = name.textContent.replace("Wikiloc - ", "");
  }
};
const setPoint = (trkpt: Element): PointForm | null => {
  const ele = trkpt.querySelector("ele");
  const time = trkpt.querySelector("time");
  const lat = trkpt.getAttribute("lat");
  const lon = trkpt.getAttribute("lon");
  if (lon && lat && ele?.textContent && time?.textContent) {
    const latitude = parseFloat(lat);
    const longitude = parseFloat(lon);
    return {
      elevation: parseFloat(ele.textContent),
      latitude: latitude,
      longitude: longitude,
      date: new Date(time.textContent),
    };
  }
  return null;
};
const setIsMoita = (point: PointForm) => {
  const moitaCoordinates = { latitude: 39.64957895555594, longitude: -8.667871756075451 };
  const nearDistance = 50;
  if (!formObject.value.isMoita) {
    formObject.value.isMoita = getDistance({ latitude: point.latitude, longitude: point.longitude }, moitaCoordinates) <= nearDistance;
  }
};
const setReset = (previousDate: Date | null, date: Date): boolean => {
  if (formObject.value.isMoita && previousDate && (date.getTime() - previousDate.getTime()) > (3 * 60 * 60 * 1000)) {
    if (didReset) {
      return true;
    }
    formObject.value.points = [];
    didReset = true;
  }
  return false;
};
const setLandmarks = (gpxDoc: Document) => {
  formObject.value.landmarks = [];
  const wpts = gpxDoc.querySelectorAll("wpt");
  if (wpts) {
    wpts.forEach(wpt => {
      setLandmark(wpt);
    });
  }
};
const setLandmark = (wpt: Element) => {
  const point = setPoint(wpt);
  if (point) {
    const name = wpt.querySelector("name");
    if (name && name.textContent) {
      const landmark: LandmarkForm = {
        name: name.textContent,
        point: point,
      };
      formObject.value.landmarks?.push(landmark);
    }
  }
};
const setDateTimes = () => {
  if (!formObject.value.points) {
    return;
  }
  const [first, ...rest] = formObject.value.points;
  const lastPoint = rest.pop();
  if (!lastPoint) {
    return;
  }
  const firstDate = dayjs(first.date);
  const lastDate = dayjs(lastPoint.date);

  const duration = dayjs.duration(lastDate.diff(firstDate));
  formObject.value.duration = duration.format("HH:mm");

  formObject.value.date = firstDate.toDate();
  formObject.value.weekNumber = dayjs(firstDate).week();
  formObject.value.startTime = firstDate.format("HH:mm");
  formObject.value.endTime = lastDate.format("HH:mm");
};
const setDistances = () => {
  if (!formObject.value.points) {
    return;
  }
  let totalDistance = 0;
  let totalSlope = 0;
  let pointA: PointForm | null = null;
  formObject.value.points.forEach(pointB => {
    if (!pointA) {
      pointA = pointB;
    } else {
      totalDistance += haversineDistance(pointA, pointB);
      totalSlope += Number(Math.abs(pointA.elevation - pointB.elevation).toFixed(3));
      pointA = pointB;
    }
  });
  formObject.value.distance = totalDistance;
  formObject.value.slope = Number((totalSlope / 2).toFixed(3));
};
const haversineDistance = (coord1: PointForm, coord2: PointForm) => {
  function toRadians(degrees: number) {
    return degrees * Math.PI / 180;
  }
  const R = 6371;
  const lat1 = toRadians(coord1.latitude);
  const lon1 = toRadians(coord1.longitude);
  const lat2 = toRadians(coord2.latitude);
  const lon2 = toRadians(coord2.longitude);
  const dLat = lat2 - lat1;
  const dLon = lon2 - lon1;
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1) * Math.cos(lat2) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  const distance = R * c * 1000;
  return distance;
}
const setMoitaData = () => {
  formObject.value.groupName = "Sozinho";
  formObject.value.startDistrictId = "Santarém";
  formObject.value.startCountyId = "Ourém";
  formObject.value.startLocationId = "Moita";
}

const { load: loadDistricts, data: districts } = useApiGet<DistrictShort[]>(toast, []);
const getDistricts = () => loadDistricts(districtsApi, { dataType: districtShortDataType });

onMounted(() => {
  if (props.id) {
    getData();
  }
  getDistricts();
});
</script>
