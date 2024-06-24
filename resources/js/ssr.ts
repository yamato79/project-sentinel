import { createSSRApp, h, DefineComponent } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from "@inertiajs/vue3";
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faDisplay, faEarthAmericas, faGear, faPlus, faRightFromBracket, faSave, faSearch, faTimes, faTrash, faUserCircle, faUserGear} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(
    faDisplay,
    faEarthAmericas,
    faGear,
    faRightFromBracket,
    faUserGear,
    faSearch,
    faPlus,
    faSave,
    faTimes,
    faTrash,
    faUserCircle,
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
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
