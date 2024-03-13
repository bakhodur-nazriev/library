import {createApp} from 'vue';
import App from './App.vue';
import './assets/style.css';
import i18n from "./i18n";
import router from "./router/index.js";
import "./interceptors/axios.js";
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

const app = createApp(App)
app.use(ElementPlus)
app.use(i18n)
app.use(router)
app.mount('#app');
