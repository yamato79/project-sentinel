import "../css/app.css";

import { createSSRApp, h, DefineComponent } from "vue";
import { renderToString } from "@vue/server-renderer";
import { createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faCheck, faLongArrowLeft, faDisplay, faGlobe, faGear, faPlus, faRightFromBracket, faSave, faPaperPlane, faSearch, faTimes, faTrash, faUserCircle, faUserGear, faSignOutAlt, faLayerGroup } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(
    faCheck,
    faLongArrowLeft,
    faDisplay,
    faGlobe,
    faGear,
    faRightFromBracket,
    faUserGear,
    faSearch,
    faPlus,
    faSave,
    faPaperPlane,
    faTimes,
    faTrash,
    faUserCircle,
    faLayerGroup,
    faSignOutAlt,
);

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>("./pages/**/*.vue")),
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .component("FontAwesomeIcon", FontAwesomeIcon)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                });
        },
    })
);
