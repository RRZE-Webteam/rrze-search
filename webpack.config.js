/* eslint-disable */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

/**
 * If you ever want to enable WP Block support, make sure to enable the following line.
 * Afterwards wp-scripts will function as expected.
 */
// const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
    ...defaultConfig,

    entry: {
        //...getWebpackEntryPoints( 'script' )(), // <- Remove to enable WPBlock Entry Points
        'js/frontend/rrze-search': './src/js/rrze-search.js',
        'js/backend/rrze-search-admin': './src/js/rrze-search-admin.js',
        'js/frontend/a11y': './src/js/ally.min.js',
        'css/rrze-search' : './src/sass/rrze-search-index.css.js',
    },
    devtool: isProduction ? false : 'eval-source-map',
    module: {
        ...defaultConfig.module,
    },
    resolve: {
        ...defaultConfig.resolve,
        extensions: [ '.tsx', '.ts', '.js', '.json' ],
    },
    plugins: [
        new RemoveEmptyScriptsPlugin({ extensions: /\.(css.js)$/ }),
        ...defaultConfig.plugins,
    ],
    optimization: {
        ...defaultConfig.optimization,
    },
    performance: { ...defaultConfig.performance, hints: false },
};
