import request from '@/utils/request';

export function getAdminsLoginList(data) {
    return request({
        url: '/logs.login.admins',
        method: 'post',
        data
    });
}

export function getAdminsBehaviorList(data) {
    return request({
        url: '/logs.behavior.admins',
        method: 'post',
        data
    });
}

export function getUsersLoginList(data) {
    return request({
        url: '/logs.login.users',
        method: 'post',
        data
    });
}

export function getUsersBehaviorList(data) {
    return request({
        url: '/logs.behavior.users',
        method: 'post',
        data
    });
}

export function getListTablesOperated(data) {
    return request({
        url: '/logs.tables_operated',
        method: 'post',
        data
    });
}

export function getInfoTablesOperated(data) {
    return request({
        url: '/logs.tables_operated.info',
        method: 'post',
        data
    });
}
