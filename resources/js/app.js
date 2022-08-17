import 'bootstrap';
import jQuery from "jquery";
Object.assign(window, { $: jQuery, jQuery })
window.$ = jQuery;

import {createApp} from "vue/dist/vue.esm-bundler";

import test from "/resources/js/components/test.vue";

const app = createApp({
    components: {
        test
    }
});

app.mount('#app');
