import {
  createRouter,
  createWebHistory,
  NavigationGuardNext,
  RouteLocationNormalized,
  type RouteRecordRaw,
} from "vue-router";

const login = () => import("@/components/auth/Login.vue");
const listRoutes = () => import("@/components/ListRoutes.vue");
const listUsers = () => import("@/components/users/ListUsers.vue");
const listTracks = () => import("@/components/tracks/ListTracks.vue");

import { getAxiosAuth } from "@/composables/axiosInstance";
import { useAuthStore } from "@/stores/auth";

const routes: RouteRecordRaw[] = [
  {
    path: "/login",
    name: "login",
    component: login,
  },
  {
    path: "/routes",
    name: "routes",
    component: listRoutes,
    meta: { admin: true },
  },
  {
    path: "/users",
    name: "users",
    component: listUsers,
    meta: { admin: true },
  },
  {
    path: "/tracks",
    name: "tracks",
    component: listTracks,
    meta: { admin: true },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(
  async (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
  ) => {
    const authStore = useAuthStore();

    if (!authStore.user && !!localStorage.getItem("jwtToken")) {
      authStore.setTokenFromLocalStorage();
    }
    if (to.meta && to.meta.admin && !getAxiosAuth() && !authStore.user) {
      return next({ name: "login" });
    }
    if (to.name === "login" && authStore.user) {
      return next({ name: "routes" });
    }

    next();
  }
);

export default router;
