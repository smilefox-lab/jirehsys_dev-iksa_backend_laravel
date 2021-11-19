/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import PropertyComponent from './components/PropertyComponent';
import NewsComponent from './components/NewsComponent';

window.Vue = require('vue');

window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('property-component', PropertyComponent);
Vue.component('news-component', NewsComponent);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = (key) => {
    return _.get(window.trans, key, key);
};

import sanitizeHTML from 'sanitize-html';
Vue.prototype.$sanitize = sanitizeHTML;

const app = new Vue({
    el: '#app'
});
