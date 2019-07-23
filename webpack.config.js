var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('default', './assets/themes/default/default.js')
    .addStyleEntry('css/theme', './assets/css/theme.scss')
    .addStyleEntry('css/app', './assets/css/app.scss')
    .splitEntryChunks()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())

    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .configureBabel((babelConfig) => {
        if (Encore.isProduction()) {
            babelConfig.plugins.push(
                'transform-react-remove-prop-types'
            );
        }
        babelConfig.plugins.push(
            'babel-plugin-transform-object-rest-spread'
        );
        const preset = babelConfig.presets.find(([name]) => name === "@babel/preset-env");
        if (preset !== undefined) {
            preset[1].useBuiltIns = "usage";
            preset[1].corejs = '3.0.0';
        }
    })
    .copyFiles({
        from: './assets/static',
        to: 'static/[path][name].[ext]',
        pattern: /\.(png|gif|jpg|jpeg)$/,
    })


    // enables Sass/SCSS support
    .enableSassLoader()
    .enablePostCssLoader()
    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
