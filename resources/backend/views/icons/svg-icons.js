const req = require.context('@/icons/svg', false, /\.svg$/);
const requireAll = requireContext => requireContext.keys();

const reg = /\.\/(.*)\.svg/;

const svgIcons = requireAll(req).map(data => {
    return data.match(reg)[1];
});

export default svgIcons;
