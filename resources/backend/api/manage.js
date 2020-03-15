import request from '@/utils/request';

export function login(data) {
    return request({
        url: '/login',
        method: 'post',
        data
    });
}

export function getInfo() {
    return request({
        url: '/info',
        method: 'post'
    });
}

export function updateInfo(data) {
    return request({
        url: '/updateInfo',
        method: 'post',
        data
    });
}

export function getRoutes() {
    return request({
        url: '/rules',
        method: 'post'
    });
}

export function logout() {
    return request({
        url: '/logout',
        method: 'post'
    });
}
