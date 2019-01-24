const webpack = require('webpack');


const watching = webpack(require('./webpack.config'), (err, stats) => {
    if (err || stats.hasErrors()) {
        console.error('Webpack: FAIL\nRun "npx webpack" in this directory to get debug info.');
    } else {
        console.log('Webpack: OK');
    }
});

//  The UglifyJS alone is used only for CodeMirror. Because UglifyJS in Webpack bundles the CodeMirror, so it doesn't work properly. 

// -----BEGIN CODEMIRROR CODE BLOCK-----
do_uglifyjs();

function do_uglifyjs() {
    const UglifyJS = require('uglify-js');
    const fs = require('fs');

    const files = {
        '../../libs/codemirror/codemirror.js': './node_modules/codemirror/lib/codemirror.js',
        '../../libs/codemirror/css.js': './node_modules/codemirror/mode/css/css.js',
    };

    for (var file_to_write in files) {
        const file_to_read = files[file_to_write];
        const text_from_file = fs.readFileSync(file_to_read, 'utf-8');
        const result = UglifyJS.minify(text_from_file);
        fs.writeFileSync(file_to_write, result.code);

        if (result.error) {
            console.error('UglifyJS: FAIL');
        } else {
            console.log('UglifyJS: OK');
        }
    }
}

// -----END CODEMIRROR CODE BLOCK-----