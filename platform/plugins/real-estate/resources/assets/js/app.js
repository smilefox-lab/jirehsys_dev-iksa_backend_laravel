/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import sanitizeHTML from 'sanitize-html';
import IksaImages from './components/Images';
import IksaFiles from './components/Files';
import moment from 'moment';
require('./bootstrap');

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

const image = new Vue({
    el: '#image',
    components: {
        IksaImages
    }
});

const file = new Vue({
    el: '#file',
    components: {
        IksaFiles
    }
});

const legal = new Vue({
    el: '#legal',
    components: {
        IksaFiles
    }
});

const plane = new Vue({
    el: '#plane',
    components: {
        IksaFiles
    }
});

