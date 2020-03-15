import {login, logout, getInfo, getRoutes} from '@/api/manage';
import {setToken, removeToken} from '@/utils/auth';
import router, {resetRouter} from '@/router';

const state = {
    info: [],
    rules: [],
};

const mutations = {
    SET_INFO: (state, data) => {
        state.info = data
    },
    SET_RULES: (state, data) => {
        state.rules = data
    },
};

const actions = {
    // user login
    login({commit}, manageInfo) {
        const {username, password} = manageInfo;
        return new Promise((resolve, reject) => {
            login({username: username.trim(), password: password}).then(response => {
                const {data} = response;
                commit('SET_INFO', data);
                setToken(data.access_token);
                resolve();
            }).catch(error => {
                reject(error);
            })
        });
    },

    // get user info
    getInfo({commit}) {
        return new Promise((resolve, reject) => {
            getInfo().then(response => {
                const {data} = response;

                if (!data) {
                    reject('Verification failed, please Login again.');
                }
                commit('SET_INFO', data);
                resolve(data);
            }).catch(error => {
                reject(error);
            });
        })
    },

    // get user rules
    getRoutes({commit}) {
        return new Promise((resolve, reject) => {
            getRoutes().then(response => {
                const rules = response.data.map(data => data.route_name);
                commit('SET_RULES', rules);
                resolve(rules);
            }).catch(error => {
                reject(error);
            });
        })
    },

    // user logout
    logout({commit, state, dispatch}) {
        return new Promise((resolve, reject) => {
            logout(state.token).then(() => {
                commit('SET_INFO', []);
                commit('SET_RULES', []);
                removeToken();
                resetRouter();

                dispatch('permission/resetRoutes', null, {root: true});

                // reset visited views and cached views
                // to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
                dispatch('tagsView/delAllViews', null, {root: true});

                resolve();
            }).catch(error => {
                reject(error);
            })
        });
    },

    // remove token
    resetToken({commit}) {
        return new Promise(resolve => {
            commit('SET_INFO', []);
            commit('SET_RULES', []);
            removeToken();
            resolve();
        });
    },

    // dynamically modify permissions
    refreshRoutes({commit, dispatch}) {
        return new Promise(async resolve => {

            commit('SET_RULES', []);
            resetRouter();

            // generate accessible routes map based on roles
            const accessRoutes = await dispatch('permission/generateRoutes', null, {root: true});

            // dynamically add accessible routes
            router.addRoutes(accessRoutes);

            // reset visited views and cached views
            dispatch('tagsView/delAllViews', null, {root: true});

            resolve();
        });
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
