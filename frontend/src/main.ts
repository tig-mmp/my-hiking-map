import { createApp } from "vue";
import "@/style.css";
import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "primeflex/primeflex.min.css";
import { createPinia } from "pinia";
import router from "@/router";
import PrimeVue from "primevue/config";
import App from "@/App.vue";

const pinia = createPinia();
const app = createApp(App);

import InputText from "primevue/inputtext";
import Button from "primevue/button";
import FileUpload from "primevue/fileupload";
import Column from "primevue/column";
import Sidebar from "primevue/sidebar";
import DataTable from "primevue/datatable";
import Tooltip from "primevue/tooltip";
import ToastService from "primevue/toastservice";
import FocusTrap from "primevue/focustrap";

app.use(PrimeVue);
app.use(pinia);
app.use(ToastService);
app.directive("focustrap", FocusTrap);
app.component("InputText", InputText);
app.component("Button", Button);
app.component("FileUpload", FileUpload);
app.component("Column", Column);
app.component("Sidebar", Sidebar);
app.component("DataTable", DataTable);
app.directive("tooltip", Tooltip);
app.use(router);
app.mount("#app");
