import request from '@/utils/request';

export function getAll(data) {
    return request({
        url: '/config.all',
        method: 'post',
        data
    });
}

export function getList(data) {
    return request({
        url: '/config.list',
        method: 'post',
        data
    });
}

export function getInfo(data) {
    return request({
        url: '/config.info',
        method: 'post',
        data
    });
}

export function getConfig(data) {
    return request({
        url: '/config.config',
        method: 'post',
        data
    });
}

export function add(data) {
    return request({
        url: '/config.add',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/config.update',
        method: 'post',
        data
    });
}

export function del(data) {
    return request({
        url: '/config.delete',
        method: 'post',
        data
    });
}
