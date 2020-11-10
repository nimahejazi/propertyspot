const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//         //
//     ]);

mix.webpackConfig({
    resolve: {
        extensions: ['.ts']
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: [
                    'babel-loader',
                    'ts-loader'
                ]
            },
        ]
    },

});

mix.sass('resources/scss/main.scss', 'public/css').version();
mix.sass('resources/scss/templates/simple.scss', 'public/css/simple.css').version();

mix.js('resources/js/app.js', 'public/js/bundle.js').sourceMaps().version();
mix.js('resources/js/simple.js', 'public/js/simple.js').sourceMaps().version();

mix.copy('resources/js/rk-*.*', 'public/js');
mix.copy('resources/js/vendor/*.js', 'public/js/vendor');
mix.copy('resources/img', 'public/img');
