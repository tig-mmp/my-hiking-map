<template>
  <div class="flex flex-column md:flex-row">
    <div class="w-full md:w-5 flex flex-column align-items-center justify-content-center gap-3 py-5">
      <router-link :to="{ name: 'tracks' }">Trilhos</router-link>
    </div>
    <div class="w-full md:w-2">
      <Divider layout="vertical"><b>OR</b></Divider>
    </div>
    <div class="w-full md:w-5 flex align-items-center justify-content-center py-5">
      <router-link :to="{ name: 'users' }">Utilizadores</router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import Divider from "primevue/divider";
</script>
