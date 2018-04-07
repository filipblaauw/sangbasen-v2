import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import Form from './services/form'
import helper from './services/helper'
import VTooltip from 'v-tooltip'
import VueMask from 'v-mask'
import VuejsDialog from "vuejs-dialog"
import Sortable from 'vue-sortable'
import showTip from './components/show-tip'
import paginationRecord from './components/pagination-record'
import showError from './components/show-error'
import moduleInfo from './components/module-info'
import * as VueGoogleMaps from "vue2-google-maps"

window._get = require('lodash/get');
window._eachRight = require('lodash/eachRight');
window._replace = require('lodash/replace');
window._has = require('lodash/has');
window._size = require('lodash/size');

window.Vue = Vue;
Vue.use(VueRouter);
window.axios = axios;
window.Form = Form;
window.helper = helper;
Vue.prototype.trans = (string, args) => {
    let value = _get(window.i18n, string);

    _eachRight(args, (paramVal, paramKey) => {
        value = _replace(value, `:${paramKey}`, paramVal);
    });
    return value;
};
Vue.prototype.$last = function (item, list) {
  return item == list[list.length - 1]
};

Vue.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyA0o_0_xldFVJxeGjjcwoHF4ZVSfoWdFIM",
    libraries: "places" // necessary for places input
  }
});
Vue.use(VTooltip);
Vue.use(VueMask);
Vue.use(VuejsDialog, {
    html: true,
    loader: true,
    message: i18n.general.proceed_with_request,
    okText: i18n.general.yes,
    cancelText: i18n.general.no,
    animation: 'bounce',
})
Vue.use(Sortable)
Vue.component('show-tip',showTip);
Vue.component('pagination-record',paginationRecord);
Vue.component('show-error',showError);
Vue.component('module-info',moduleInfo);
Vue.component('google-cluster', VueGoogleMaps.Cluster);

moment.locale('nb')

// Global Vue filters
Vue.filter('toKg', function (value) {
  if (value < 1000) {
    return value + ' g'
  }
  else {
    return (value / 1000).toFixed(2).replace(".", ",") + ' kg'
  }
})
Vue.filter('formatDate', function (value) {
  if (value) {
    return moment(value, 'YYYY-MM-DD').format('DD. MMMM YYYY')
  }
})
Vue.filter('formatUnixDate', function (value) {
  if (value) {
    return moment(value, 'X').format('DD. MMMM YYYY')
  }
})
Vue.filter('formatDateTime', function (value) {
  if (value) {
    return moment(value).fromNow()
  }
})

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
