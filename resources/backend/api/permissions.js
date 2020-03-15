import request from '@/utils/request';

export function getAll(data) {
    return request({
        url: '/permissions.all',
        method: 'post',
        data
    });
}

export function getList(data) {
    return request({
        url: '/permissions.list',
        method: 'post',
        data
    });
}

export function getInfo(data) {
    return request({
        url: '/permissions.info',
        method: 'post',
        data
    });
}

export function add(data) {
    return request({
        url: '/permissions.add',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/permissions.update',
        method: 'post',
        data
    });
}

export function del(data) {
    return request({
        url: '/permissions.delete',
        method: 'post',
        data
    });
}
