import {asyncRoutes, constantRoutes} from '@/router';

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param rules
 */
export function filterAsyncRoutes(routes, rules) {
    const res = [];

    routes.forEach(route => {
        const tmp = {...route};
        // console.log(tmp.name, rules.includes(tmp.name))
        if (rules.includes(tmp.name)) {
            if (tmp.children) {
                tmp.children = filterAsyncRoutes(tmp.children, rules)
            }
            res.push(tmp)
        }
    });

    return res
}

const state = {
    routes: [],
    addRoutes: []
};

const mutations = {
    SET_ROUTES: (state, routes) => {
        state.addRoutes = routes;
        state.routes = constantRoutes.concat(routes);
    },
    RESET_ROUTES: (state) => {
        state.routes = [];
    }
};

const actions = {
    generateRoutes({commit, state, dispatch}) {
        return new Promise(async resolve => {
            let accessedRoutes;

            const rules = await dispatch('manage/getRoutes', null, {root: true});
            // console.log('rules：', rules)

            accessedRoutes = filterAsyncRoutes(asyncRoutes, rules);
            // 404 page must be placed at the end !!!
            accessedRoutes.push({path: '*', redirect: '/404', hidden: true});

            commit('SET_ROUTES', accessedRoutes);
            resolve(accessedRoutes);
        });
    },
    resetRoutes({commit, state, dispatch}) {
        commit('RESET_ROUTES', []);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
