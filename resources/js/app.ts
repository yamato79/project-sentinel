import "../css/app.css";

import FloatingVue from "floating-vue";
import "floating-vue/dist/style.css";
import { createApp, h, DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faCheck, faCheckCircle, faLongArrowLeft, faDisplay, faGlobe, faGear, faRectangleXmark, faPlus, faRotate, faRightFromBracket, faSave, faPaperPlane, faSearch, faTimes, faTimesCircle, faTrash, faUpRightFromSquare, faUserCircle, faUserGear, faSignOutAlt, faLayerGroup, faCalendarAlt } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(
    faCheck,
    faCheckCircle,
    faCalendarAlt,
    faLongArrowLeft,
    faDisplay,
    faGlobe,
    faGear,
    faRectangleXmark,
    faRightFromBracket,
    faUserGear,
    faSearch,
    faPlus,
    faRotate,
    faSave,
    faPaperPlane,
    faTimes,
    faTimesCircle,
    faTrash,
    faUpRightFromSquare,
    faUserCircle,
    faLayerGroup,
    faSignOutAlt,
);

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>("./pages/**/*.vue")),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(FloatingVue)
            .component("FontAwesomeIcon", FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
