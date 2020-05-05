const path = require('path');
const {series, parallel, src, dest} = require('gulp');
const zip = require('gulp-zip');

const config = {
    srcPath: path.resolve(__dirname, './plugins/editors/gutenberg'),
    buildPath: path.resolve(__dirname, './package'),
    packageName: 'joomla-gutenberg-editor_v1.0.0.zip'
};

function editorPlugin() {
    return src([`${config.srcPath}/**`, `!${config.srcPath}/assets/editor/**`])
        .pipe(dest(config.buildPath));
}

function makePackage() {
    return src(config.buildPath + '/**')
        .pipe(zip(config.packageName))
        .pipe(dest(config.buildPath));
}

exports.default = series(editorPlugin, makePackage);