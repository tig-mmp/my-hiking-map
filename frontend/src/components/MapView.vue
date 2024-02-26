<template>
    <div style="height:200px; position: relative;" class="relative h-10rem">
        <MglMap :mapStyle="MAP_STYLE" :zoom="zoom" :center="props.coordinate">
            <MglNavigationControl position="top-right" />
            <MglGeoJsonSource source-id="geojson" :data="geoJsonSource.data">
                <MglLineLayer v-if="geoJsonSource.show" layer-id="geojson" :layout="layout" :paint="paint" />
            </MglGeoJsonSource>

            <MglMarker :coordinates="props.coordinate" color="blue" />
        </MglMap>
    </div>
</template>

<script setup lang="ts">
import {
    MglMap,
    MglNavigationControl,
    MglGeoJsonSource,
    MglLineLayer,
    MglMarker,
} from "vue-maplibre-gl";
import { Ref, onMounted, ref } from "vue";
import { FeatureCollection } from "geojson";
import useEnvironment from "@/composables/useEnvironment";

const props = defineProps<{ coordinate: number[] }>();

const { MAP_STYLE } = useEnvironment();

const zoom: Ref<number> = ref(16);
const paint = ref({
    "line-color": "#FF0000",
    "line-width": 8
});
const layout = ref({
    "line-cap": "round",
    "line-join": "round",
});
const geoJsonSource = ref({
    show: true,
    data: <FeatureCollection>{
        type: "FeatureCollection",
        features: [{
            type: "Feature",
            properties: {},
            geometry: {
                type: "Point",
                coordinates: [],
            },
        }],
    },
});

onMounted(() => geoJsonSource.value.data.features[0].geometry.coordinates = [props.coordinate]);
</script>

<style lang="scss">
@import "https://unpkg.com/maplibre-gl@2.3.0/dist/maplibre-gl.css";

.mgl-wrapper {
    position: absolute;
    inset: 0;
}
</style>
