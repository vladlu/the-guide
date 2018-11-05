const UglifyJSPlugin          = require('uglifyjs-webpack-plugin'),
      ExtractTextPlugin       = require('extract-text-webpack-plugin'),
      OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');



const Main = {
	entry: {
        'public/js/the-guide.js':             '../../assets/public/js/the-guide.prod.js',
        
        'admin/js/dashboard-controller-menu.js': '../../assets/admin/js/dashboard-controller-menu.prod.js',
        'admin/js/dashboard-settings-menu.js':   '../../assets/admin/js/dashboard-settings-menu.prod.js',
        'admin/js/dashboard-customize-menu.js':  '../../assets/admin/js/dashboard-customize-menu.prod.js',

        'admin/css/dashboard-controller-menu.css': '../../assets/admin/css/dashboard-controller-menu.prod.css',
        'admin/css/dashboard-customize-menu.css':  '../../assets/admin/css/dashboard-customize-menu.prod.css',
        'admin/css/dashboard-settings-menu.css':   '../../assets/admin/css/dashboard-settings-menu.prod.css',

        // -----BEGIN CODEMIRROR CODE BLOCK-----
        'admin/lib/codemirror/codemirror.css': './node_modules/codemirror/lib/codemirror.css',
        // -----END CODEMIRROR CODE BLOCK-----
    },
    output: {
        path:     __dirname + '/../../assets/',
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
