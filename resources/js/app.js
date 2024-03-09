import "./bootstrap";
// import "../css/app.css";//comentado para que no cargue los estilos por defecto y solo vuetify

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";


// sweetalert2
import Swal from "sweetalert2";
window.Swal = Swal;

// import this after install `@mdi/font` package
import "@mdi/font/css/materialdesignicons.css";

// Vuetify
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components,
    directives,
});

// Pinia
import { createPinia } from "pinia";
const pinia = createPinia();

// mis scripts
import "./assets/css/config.css";
import "./assets/css/datatables.css";
import "./assets/css/form.css";

// Default Layout
import App from "@/Layouts/App.vue";

// App
const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        page.then((module) => {
            module.default.layout = module.default.layout ?? App;
        });
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(vuetify)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#ffffff",
    },
});
