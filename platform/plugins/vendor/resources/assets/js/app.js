/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Botble from './utils';
import sanitizeHTML from 'sanitize-html';
import ActivityLogComponent from './components/dashboard/ActivityLogComponent';
import PackagesComponent from './components/dashboard/PackagesComponent';
import PaymentHistoryComponent from './components/dashboard/PaymentHistoryComponent';
import moment from 'moment';

require('./bootstrap');

require('./utils');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('activity-log-component', ActivityLogComponent);
Vue.component('packages-component', PackagesComponent);
Vue.component('payment-history-component', PaymentHistoryComponent);

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

const app = new Vue({
    el: '#app'
});

require('./form');
require('./avatar');

$(document).ready(() => {
    if (window.noticeMessages) {
        window.noticeMessages.forEach(message => {
            Botble.showNotice(message.type, message.message, message.type === 'error' ? _.get(window.trans, 'notices.error') : _.get(window.trans, 'notices.success'));
        });
    }

    $(document).on('click', '.button-renew', event => {
        event.preventDefault();
        let _self = $(event.currentTarget);

        $('.button-confirm-renew').data('section', _self.data('section')).data('parent-table', _self.closest('.table').prop('id'));
        $('.modal-confirm-renew').modal('show');
    });

    $('.button-confirm-renew').on('click', (event) => {
        event.preventDefault();
        let _self = $(event.currentTarget);

        let url = _self.data('section');

        _self.addClass('button-loading');

        $.ajax({
            url: url,
            type: 'POST',
            success: (data) => {
                if (data.error) {
                    Botble.showError(data.message);
                } else {
                    window.LaravelDataTables[_self.data('parent-table')].row($('a[data-section="' + url + '"]').closest('tr')).remove().draw();
                    Botble.showSuccess(data.message);
                }

                _self.closest('.modal').modal('hide');
                _self.removeClass('button-loading');
            },
            error: (data) => {
                Botble.handleError(data);
                _self.removeClass('button-loading');
            }
        });
    });
});
