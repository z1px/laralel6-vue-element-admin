const getters = {
    sidebar: state => state.app.sidebar,
    language: state => state.app.language,
    size: state => state.app.size,
    device: state => state.app.device,
    visitedViews: state => state.tagsView.visitedViews,
    cachedViews: state => state.tagsView.cachedViews,
    manageInfo: state => state.manage.info,
    permissionRules: state => state.manage.rules,
    permissionRoutes: state => state.permission.routes,
    errorLogs: state => state.errorLog.logs
};
export default getters;
