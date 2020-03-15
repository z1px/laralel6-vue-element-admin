import axios from 'axios';
import {MessageBox, Message} from 'element-ui';
import store from '@/store';
import {getToken} from '@/utils/auth';
import checkPermission from '@/utils/permission';
import {encrypt, decrypt} from '@/utils/aes';

// create an axios instance
const service = axios.create({
    baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: 30000 // request timeout
});

const whiteList = ['login', 'info', 'updateInfo', 'rules', 'logout']; // no redirect whitelist

// request interceptor
service.interceptors.request.use(
    config => {

        // do something before request is sent

        // 操作权限拦截
        const rule = config.url.replace(/(^\/)|(\/$)/g,'');
        if(!whiteList.includes(rule) && !checkPermission('admin.' + rule)){
            return Promise.reject(new Error('无权限！'));
        }

        if(!config.data || typeof config.data !== 'object'){
            config.data = new Object()
        }
        config.data['accessToken'] = getToken();

        // 数据进行AES加密传输
        if(config.data){
            if(typeof config.data === 'object'){
                config.data = JSON.stringify(config.data);
            }
            config.data = encrypt(config.data);
        }

        return config
    },
    error => {
        // do something with request error
        console.log(error); // for debug
        return Promise.reject(error);
    }
);

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        // 数据进行AES解密
        if('string' === typeof response.data){
            response.data = JSON.parse(decrypt(response.data));
        }

        if('object' !== typeof response.data){
            Message({
                message: 'Error',
                type: 'error',
                duration: 5 * 1000
            });
            return Promise.reject(new Error(response.data));
        }

        // if the custom code is not 20000, it is judged as an error.
        if (response.data.code !== 1) {
            Message({
                message: response.data.message || 'Error',
                type: 'error',
                duration: 5 * 1000
            });

            // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
            if (response.data.code === -1) {
                // to re-login
                MessageBox.confirm('You have been logged out, you can cancel to stay on this page, or log in again', 'Confirm logout', {
                    confirmButtonText: 'Re-Login',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    store.dispatch('manage/resetToken').then(() => {
                        location.reload()
                    });
                })
            }
            return Promise.reject(new Error(response.data.message || 'Error'));
        } else {
            return response.data;
        }
    },
    error => {
        console.log('err' + error); // for debug
        Message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000
        });
        return Promise.reject(error);
    }
);

export default service;
