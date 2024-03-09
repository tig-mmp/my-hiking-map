<script setup lang="ts">
</script>

<template>
    <div class="loadingBox">
        <img src="/loading.svg" class="loading" alt="loading">
    </div>
</template>

<style scoped>
@keyframes fadeInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }

    50% {
        opacity: 1;
        transform: translateX(0%);
    }

    100% {
        opacity: 0;
        transform: translateX(100%);
    }
}

div.loadingBox {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

img.loading {
    height: 30vh;
    animation: fadeInLeft 3s linear infinite;
}
</style>