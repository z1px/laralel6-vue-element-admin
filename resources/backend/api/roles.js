import request from '@/utils/request';

export function getAll(data) {
    return request({
        url: '/roles.all',
        method: 'post',
        data
    });
}

export function getList(data) {
    return request({
        url: '/roles.list',
        method: 'post',
        data
    });
}

export function getInfo(data) {
    return request({
        url: '/roles.info',
        method: 'post',
        data
    });
}

export function add(data) {
    return request({
        url: '/roles.add',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/roles.update',
        method: 'post',
        data
    });
}

export function del(data) {
    return request({
        url: '/roles.delete',
        method: 'post',
        data
    });
}

export function setPermissions(data) {
    return request({
        url: '/roles.setPermissions',
        method: 'post',
        data
    });
}

export function getPermissions(data) {
    return request({
        url: 'roles.getPermissions',
        method: 'post',
        data
    });
}
