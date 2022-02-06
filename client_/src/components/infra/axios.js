import _axios from 'axios'
import appConfig from '../../config/app.conf'
import primeToast from './primeToast'

// toast.add({severity:'info', summary: 'Info Message', detail:'Message Content', life: 3000});
// import Toast from "primevue/toast";
// import Vue from 'vue';
// import Toast from 'primevue/toast';

import NProgress from 'nprogress';

const debug = true;

const showNotifications = function(headers) {
   
    // primeToast['success']('blahhhh');

    let notifications = [];
    if(headers['x-notification'] != null) {
        notifications = JSON.parse(headers['x-notification'])
    }
    notifications.map((notification) => {
        if(notification.type === 'info' && debug === true) {
            console.info(notification.type + ' : ' + notification.message)
        } else {
            primeToast[notification.type](notification.message);
        } 
    }) 
} 

const axios = _axios.create({
    baseURL: appConfig.baseUrl
});

axios.interceptors.request.use(function (config) {
    NProgress.start();
    return config;
  }, function (error) {
    console.log(error)
    NProgress.done();
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {
    NProgress.done();

    try {
        JSON.parse(response.request.responseText);
    } catch (e) {
        console.error('Could not parse json, see next error')
        console.error(response)
        primeToast[notification.type](notification.message);
        throw "Invalid Json"
    }
    showNotifications(response.headers)
    return response;
  }, function (error) {
    NProgress.done();
    let status = error.response ? error.response.status : 0;

    if(status == 401) {
        primeToast['error']('You are not logged in. Please Log in.');
        window.location.href = "/login";
    }

    if(status >= 400 && status < 500) {
        showNotifications(error.response.headers)
    }

    if(status >= 500 && status < 600) {
        primeToast['error']('System error, Contact IT.');
        console.warn('Server error')
        console.warn(error)
    }
    
    return Promise.reject(error.response);
  });


export default axios;