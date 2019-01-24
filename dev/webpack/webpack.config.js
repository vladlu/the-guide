const UglifyJSPlugin = require('uglifyjs-webpack-plugin'),
    ExtractTextPlugin = require('extract-text-webpack-plugin'),
    OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');


const Main = {
    entry: {
        'public/scripts/the-guide.js': '../../public/scripts/the-guide.prod.js',

        'admin/scripts/dashboard-menu-controller.js': '../../admin/scripts/dashboard-menu-controller.prod.js',
        'admin/scripts/dashboard-menu-settings.js': '../../admin/scripts/dashboard-menu-settings.prod.js',
        'admin/scripts/dashboard-menu-customize.js': '../../admin/scripts/dashboard-menu-customize.prod.js',

        'admin/styles/dashboard-menu-controller.css': '../../admin/styles/dashboard-menu-controller.prod.css',
        'admin/styles/dashboard-menu-customize.css': '../../admin/styles/dashboard-menu-customize.prod.css',
        'admin/styles/dashboard-menu-settings.css': '../../admin/styles/dashboard-menu-settings.prod.css',

        // -----BEGIN CODEMIRROR CODE BLOCK-----
        'libs/codemirror/codemirror.css': './node_modules/codemirror/lib/codemirror.css',
        // -----END CODEMIRROR CODE BLOCK-----
    },
    output: {
        path: __dirname + '/../../',
        filename: '[name]'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract('css-loader')
            },
        ]
    },
    plugins: [
        new UglifyJSPlugin(),
        new ExtractTextPlugin('[name]'),
        new OptimizeCSSAssetsPlugin()
    ],
};


// Exports Array of Configurations
module.exports = [
    Main
];
