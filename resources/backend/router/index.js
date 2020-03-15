import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

/* Layout */
import Layout from '@/layout';

/**
 * 解决Vue-Router升级导致的 Uncaught (in promise)问题
 * V3.1.0版本里面新增功能：push和replace方法会返回一个promise
 */
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject);
    return originalPush.call(this, location).catch(error => error)
};

/* Router Modules */

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if set true, the page will no be cached(default is false)
    affix: true                  if set true, the tag will affix in the tags-view
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all users can be accessed
 */
export const constantRoutes = [
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                path: '/redirect/:path*',
                component: () => import('@/views/redirect/index')
            }
        ]
    },
    {
        path: '/login',
        component: () => import('@/views/login/index'),
        hidden: true
    },
    {
        path: '/404',
        component: () => import('@/views/error-page/404'),
        hidden: true
    },
    {
        path: '/401',
        component: () => import('@/views/error-page/401'),
        hidden: true
    },
    {
        path: '/',
        component: Layout,
        redirect: '/dashboard',
        children: [
            {
                path: 'dashboard',
                component: () => import('@/views/dashboard/index'),
                name: 'dashboard',
                meta: {
                    title: 'dashboard',
                    icon: 'dashboard',
                    affix: true
                }
            }
        ]
    },
    {
        path: '/profile',
        component: Layout,
        redirect: '/profile/index',
        hidden: true,
        children: [
            {
                path: 'index',
                component: () => import('@/views/profile/index'),
                name: 'profile',
                meta: {
                    title: 'profile',
                    icon: 'user',
                    noCache: true
                }
            }
        ]
    },
    {
        path: '/icon',
        component: Layout,
        hidden: false,
        children: [
            {
                path: 'index',
                component: () => import('@/views/icons/index'),
                name: 'icons',
                meta: {
                    title: 'icons',
                    icon: 'icon',
                    noCache: true
                }
            }
        ]
    }
];

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user
 */
export const asyncRoutes = [
    {
        path: '/manage',
        component: Layout,
        redirect: 'noRedirect',
        name: 'admin.manage',
        meta: {
            title: 'manage',
            icon: 'nested',
        },
        children: [
            {
                path: 'admins',
                component: () => import('@/views/nested'), // Parent router-view
                redirect: 'noRedirect',
                name: 'admin.admins.index',
                meta: {
                    title: 'admins.index',
                    icon: 'user',
                },
                children: [
                    {
                        path: 'list',
                        component: () => import('@/views/manage/admins/list'),
                        name: 'admin.admins.list',
                        meta: {
                            title: 'admins.list',
                            icon: 'user'
                        }
                    },
                    {
                        path: 'add',
                        component: () => import('@/views/manage/admins/add'),
                        name: 'admin.admins.add',
                        meta: {
                            title: 'admins.add',
                            icon: 'edit',
                            noCache: true,
                            activeMenu: '/manage/admins/list'
                        },
                        hidden: true
                    },
                    {
                        path: 'update',
                        component: () => import('@/views/manage/admins/update'),
                        name: 'admin.admins.update',
                        meta: {
                            title: 'admins.update',
                            icon: 'edit',
                            noCache: true,
                            activeMenu: '/manage/admins/list'
                        },
                        hidden: true
                    }
                ]
            },
            {
                path: 'roles',
                component: () => import('@/views/nested'), // Parent router-view
                redirect: 'noRedirect',
                name: 'admin.roles.index',
                meta: {
                    title: 'roles.index',
                    icon: 'peoples',
                },
                children: [
                    {
                        path: 'list',
                        component: () => import('@/views/manage/roles/list'),
                        name: 'admin.roles.list',
                        meta: {
                            title: 'roles.list',
                            icon: 'peoples'
                        }
                    }
                ]
            },
            {
                path: 'permissions',
                component: () => import('@/views/nested'), // Parent router-view
                redirect: 'noRedirect',
                name: 'admin.permissions.index',
                meta: {
                    title: 'permissions.index',
                    icon: 'lock',
                },
                children: [
                    {
                        path: 'list',
                        component: () => import('@/views/manage/permissions/list'),
                        name: 'admin.permissions.list',
                        meta: {
                            title: 'permissions.list',
                            icon: 'lock'
                        }
                    },
                    {
                        path: 'add',
                        component: () => import('@/views/manage/permissions/add'),
                        name: 'admin.permissions.add',
                        meta: {
                            title: 'permissions.add',
                            icon: 'edit',
                            noCache: true,
                            activeMenu: '/manage/permissions/list'
                        },
                        hidden: true
                    },
                    {
                        path: 'update',
                        component: () => import('@/views/manage/permissions/update'),
                        name: 'admin.permissions.update',
                        meta: {
                            title: 'permissions.update',
                            icon: 'edit',
                            noCache: true,
                            activeMenu: '/manage/permissions/list'
                        },
                        hidden: true
                    },
                ]
            }
        ]
    },
    {
        path: '/system',
        component: Layout,
        redirect: 'noRedirect',
        name: 'admin.system',
        meta: {
            title: 'system',
            icon: 'setting',
        },
        children: [
            {
                path: 'files',
                component: () => import('@/views/system/files/list'),
                name: 'admin.files.list',
                meta: {
                    title: 'files.list',
                    icon: 'folder',
                }
            },
            {
                path: 'config',
                component: () => import('@/views/system/config/list'),
                name: 'admin.config.list',
                meta: {
                    title: 'config.list',
                    icon: 'filter',
                }
            }
        ]
    },
    {
        path: '/logs',
        component: Layout,
        redirect: 'noRedirect',
        name: 'admin.logs',
        meta: {
            title: 'logs.index',
            icon: 'education',
        },
        children: [
            {
                path: 'login',
                component: () => import('@/views/logs/login/list'),
                name: 'admin.logs.login.admins',
                meta: {
                    title: 'logs.login',
                    icon: 'documentation',
                }
            },
            {
                path: 'behavior',
                component: () => import('@/views/logs/behavior/list'),
                name: 'admin.logs.behavior.admins',
                meta: {
                    title: 'logs.behavior',
                    icon: 'form',
                }
            },
            {
                path: 'tables_operated',
                component: () => import('@/views/logs/tables_operated/list'),
                name: 'admin.logs.tables_operated',
                meta: {
                    title: 'logs.tables_operated',
                    icon: 'table',
                }
            }
        ]
    }
];

const createRouter = () => new Router({
    // mode: 'history', // require service support
    scrollBehavior: () => ({y: 0}),
    routes: constantRoutes
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
    const newRouter = createRouter();
    router.matcher = newRouter.matcher; // reset router
}

export default router;
