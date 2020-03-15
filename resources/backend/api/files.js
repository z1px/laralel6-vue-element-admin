import request from '@/utils/request';

export function getList(data) {
    return request({
        url: '/files.list',
        method: 'post',
        data
    });
}

export function setVisible(data) {
    return request({
        url: '/files.visible',
        method: 'post',
        data
    });
}

export function setInvisible(data) {
    return request({
        url: '/files.invisible',
        method: 'post',
        data
    });
}

export function del(data) {
    return request({
        url: '/files.delete',
        method: 'post',
        data
    });
}
