<script setup lang="ts">
import useApiPost from "@/composables/api/useApiPost";
import useApiRoutes from "@/composables/api/useApiRoutes";
import { setAxiosAuth } from "@/composables/axiosInstance";
import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import ProgressBar from "primevue/progressbar";
import { useToast } from "primevue/usetoast";
import { useAuthStore } from "@/stores/auth";
import { type Ref, ref } from "vue";
import { useRouter } from "vue-router";

const toast = useToast();
const { loginApi } = useApiRoutes();
const router = useRouter();
const authStore = useAuthStore();

const password: Ref<string | null> = ref(null);
const username: Ref<string | null> = ref(null);

const { post, isLoading } = useApiPost(toast);
const login = () => post(loginApi, { username: username.value, password: password.value })
    .then((response) => {
        authStore.setToken(response.data.token);
        setAxiosAuth(response.data.token);
        router.push({ name: "routes" });
    });
</script>

<template>
    <div class="flex justify-content-center">
        <Card class="text-center mt-8 w-max">
            <template #title>Login</template>
            <template #content>
                <form @submit.prevent="login">
                    <div class="field flex flex-column">
                        <label>Username</label>
                        <InputText v-focustrap id="username" v-model="username" />
                    </div>
                    <div class="field flex flex-column">
                        <label>Password</label>
                        <Password v-model="password" :feedback="false" />
                    </div>
                    <Button :disabled="isLoading" :loading="isLoading" label="Login" severity="success" rounded
                        type="submit" class="mt-2" />
                    <ProgressBar v-if="isLoading" mode="indeterminate" class="mt-2" />
                </form>
            </template>
        </Card>
    </div>
</template>