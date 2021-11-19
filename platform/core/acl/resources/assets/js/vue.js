/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import sanitizeHTML from 'sanitize-html';
import IksaFiles from './components/Files';
import moment from 'moment';

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = (key) => {
    return _.get(window.trans, key, key);
};

Vue.prototype.$sanitize = sanitizeHTML;

Vue.filter('formatDateTime', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY/MM/DD hh:mm')
    }
});

const file = new Vue({
    el: '#files',
    components: {
        IksaFiles
    }
});
