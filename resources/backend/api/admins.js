import request from '@/utils/request';

export function getList(data) {
    return request({
        url: '/admins.list',
        method: 'post',
        data
    });
}

export function getInfo(data) {
    return request({
        url: '/admins.info',
        method: 'post',
        data
    });
}

export function add(data) {
    return request({
        url: '/admins.add',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/admins.update',
        method: 'post',
        data
    });
}

export function del(data) {
    return request({
        url: '/admins.delete',
        method: 'post',
        data
    });
}

export function restore(data) {
    return request({
        url: '/admins.restore',
        method: 'post',
        data
    });
}

export function setRoles(data) {
    return request({
        url: '/admins.setRoles',
        method: 'post',
        data
    });
}

export function getRoles(data) {
    return request({
        url: '/admins.getRoles',
        method: 'post',
        data
    });
}
