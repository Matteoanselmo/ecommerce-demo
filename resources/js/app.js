import './bootstrap';
import '../css/app.css';
import 'aos/dist/aos.css'; // Importa i file CSS di AOS
import AOS from 'aos'; // Importa AOS
import vuetify from "./vuetifyOptions";
import i18n from './Modules/i18n';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from "pinia";
import GuestLayout from './Layouts/GuestLayout.vue';
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';
import AdminLayout from './Layouts/AdminLayout.vue'; // Importa AdminLayout

import DarkMode from './Components/DarkMode.vue';



const pinia = createPinia();
const appName = import.meta.env.VITE_APP_NAME || 'Security Fire';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')).then(module => {
            const page = module.default;

            // Determina il layout in base al percorso della pagina
            if (name.startsWith('Admin/')) {
                page.layout = page.layout || AdminLayout;
            } else if (name.startsWith('User/')) {
                page.layout = page.layout || AuthenticatedLayout;
            } else {
                page.layout = page.layout || GuestLayout;
            }

            return module;
        });
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .use(i18n)
            .component('dark-mode', DarkMode)
            .use(vuetify);

        app.mount(el);

        // Inizializza AOS dopo aver montato l'app
        AOS.init({
            duration: 1300,
            delay: 500,
            offset: 120, // Inizia l'animazione quando l'elemento è a 120px dal viewport
            once: true, // Esegui l'animazione solo una volta
        });

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
