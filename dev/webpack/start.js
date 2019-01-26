'use strict';


// UglifyJS
// Used for CodeMirror. Because UglifyJS in Webpack bundles the CodeMirror so it doesn't work properly. 


const fs = require('fs');
let files = {};

if (fs.existsSync('../.rules.d/uglifyjs.tsv')) {
    function load_files() {
        const file_to_read = '../.rules.d/uglifyjs.tsv',
              file_text    = fs.readFileSync(file_to_read, 'utf-8'),
              lines        = file_text.split('\n');

        for (let i = 0; i < lines.length - 1; i++) {
            let entities         = lines[i].split('\t');
            files[ entities[0] ] =  entities[1];
        }
    }
    load_files();


    function do_uglifyjs() {
        const UglifyJS = require('uglify-js');

        for (var file_to_write in files) {
            const file_to_read = files[file_to_write],
                  file_text    = fs.readFileSync(file_to_read, 'utf-8'),
                  result       = UglifyJS.minify(file_text);

            fs.writeFileSync(file_to_write, result.code);

            if (result.error) {
                console.error('UglifyJS: FAIL');
            } else {
                console.log('UglifyJS: OK');
            }
        }
    }
    do_uglifyjs();
}


// Webpack


if (fs.existsSync('../.rules.d/webpack.tsv')) {
    const webpack = require('webpack');

    const watching = webpack(require('./webpack.config'), (err, stats) => {
        if (err || stats.hasErrors()) {
            console.error('Webpack: FAIL\nRun "npx webpack" in this directory to get debug info.');
        } else {
            console.log('Webpack: OK');
        }
    });
}
