import { setAxiosAuth } from "@/composables/axiosInstance";
import { defineStore } from "pinia";

interface UserAuth {
  role: string;
}
interface DecodedToken {
  exp: number;
  iat: number;
  data: UserAuth;
}
interface State {
  token: string | null;
  user: UserAuth | null;
}

export const useAuthStore = defineStore("auth", {
  state: (): State => {
    return {
      token: localStorage.getItem("jwtToken") || null,
      user: null,
    };
  },
  actions: {
    setTokenFromLocalStorage: function () {
      var token = localStorage.getItem("jwtToken");
      if (token) {
        this.setToken(token);
      }
    },
    setToken: function (token: string) {
      this.token = token;
      localStorage.setItem("jwtToken", token);
      const decodedToken = parseJwt(token);
      if ("data" in decodedToken) {
        this.user = decodedToken.data;
        setAxiosAuth(token);
      }
    },
    logout: function () {
      this.token = null;
      this.user = null;
    },
  },
});

function parseJwt(token: string): DecodedToken {
  var payload = token.split(".")[1];
  return JSON.parse(atob(payload));
}
