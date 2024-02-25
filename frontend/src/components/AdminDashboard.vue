<template>
  <TabMenu :model="items" class="mb-4">
    <template #item="{ item, props }">
      <a :href="item.url" :target="item.target" v-bind="props.action">
        <span v-bind="props.icon" />
        <span v-bind="props.label">{{ item.label }}</span>
      </a>
    </template>
  </TabMenu>
  <RouterView />
</template>

<script setup lang="ts">
import TabMenu from "primevue/tabmenu";
import { RouterView, useRouter } from "vue-router";
import { ref } from "vue";

const router = useRouter();

const items = ref([
  { label: "Trilho", icon: "pi pi-map", command: () => router.push({ name: "tracks" }) },
  { label: "Utilizadores", icon: "pi pi-user", command: () => router.push({ name: "users" }) },
]);
</script>
