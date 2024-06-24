import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faDisplay, faGlobe, faGear, faPlus, faRightFromBracket, faSave, faSearch, faTimes, faTrash, faUserCircle, faUserGear, faSignOutAlt, faLayerGroup } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(
    faDisplay,
    faGlobe,
    faGear,
    faRightFromBracket,
    faUserGear,
    faSearch,
    faPlus,
    faSave,
    faTimes,
    faTrash,
    faUserCircle,
    faLayerGroup,
    faSignOutAlt,
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("FontAwesomeIcon", FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
