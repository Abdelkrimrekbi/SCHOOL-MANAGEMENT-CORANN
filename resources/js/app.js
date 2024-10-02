import './bootstrap';
import '../css/app.css';
import 'primevue/resources/themes/lara-light-teal/theme.css'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createI18n } from 'vue-i18n';
import { createPinia } from 'pinia'
import messages from '@/lang/messages.js'
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';

import VueApexCharts from 'vue3-apexcharts';


import { role } from './directive/role/role';
import { permission } from './directive/permission/permission';
import InsideLayout from "@/Layouts/InsideLayout.vue";





const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem("language") || 'fr',
    fallbackLocale: 'fr',
    messages,
});
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
 
    // resolve: async name => {
    //     const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue"));
    //     if (name.startsWith('Auth/')){
    //      page.default.layout ??= page.default.layout;
    //     }else{
    //      page.default.layout ??= InsideLayout;
    //     }
    //     return page;
    //    },


    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .use(PrimeVue, { inputStyle: "filled" })
            .use(ToastService)

            .use(VueApexCharts)

            .use(pinia)
            .directive('role', role)
            .directive('permission', permission)
            .mount(el);

    },
    progress: {
        color: '#4B5563',
    },
});
