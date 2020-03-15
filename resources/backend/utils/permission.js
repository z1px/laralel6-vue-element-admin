import store from '@/store';

/**
 * @param {Array} value
 * @returns {Boolean}
 * @example see @/views/permission/directive.vue
 */
export default function checkPermission(rule) {
    const rules = store.getters && store.getters.permissionRules;
    return rules.includes(rule);
}
