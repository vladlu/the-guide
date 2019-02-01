const  MiniCssExtractPlugin = require("mini-css-extract-plugin"),
       autoprefixer         = require('autoprefixer'),
       cssnano              = require('cssnano'),

       UglifyJSPlugin       = require('uglifyjs-webpack-plugin');




const fs = require('fs');
let files = {};


function load_files() {
    const file_to_read = '../.rules.d/webpack.tsv',
          file_text    = fs.readFileSync(file_to_read, 'utf-8'),
          lines        = file_text.split('\n');

    for (let i = 0; i < lines.length - 1; i++) {
        let entities         = lines[i].split('\t');
        files[ entities[0] ] = entities[1]
    }
}
load_files();




const Main = {
    entry: files,
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
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: {
                            ident: 'postcss',
                            plugins: [
                                autoprefixer(),
                                cssnano(),
                            ]
                        }
                    }
                ],
            },
        ],
    },
    plugins: [
        new UglifyJSPlugin(),
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: "[id].css"
        }),
    ],
};


// Exports Array of Configurations
module.exports = [
    Main
];