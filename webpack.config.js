/* eslint-disable */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
    ...defaultConfig,

    entry: {
        //...getWebpackEntryPoints( 'script' )(),
        'js/rrze-search': './src/js/rrze-search.js',
        'js/rrze-search-admin': './src/js/rrze-search-admin.js',
        'js/rrze-search-admin-script': './src/js/rrze-search-admin-script.js',
        'js/rrze-search-script': './src/js/rrze-search-script.js',
    },

    devtool: isProduction ? false : 'eval-source-map',

    module: {
        ...defaultConfig.module,
    },

    resolve: {
        ...defaultConfig.resolve,
        extensions: [ '.tsx', '.ts', '.js', '.json' ],
    },

    optimization: {
        ...defaultConfig.optimization,
    },

    performance: { ...defaultConfig.performance, hints: false },
};
