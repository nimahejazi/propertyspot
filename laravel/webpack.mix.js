const mix = require("laravel-mix");

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
        extensions: [".ts"],
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: ["babel-loader", "ts-loader"],
            },
        ],
    },
});

mix
    .setPublicPath('public')
    .sass("resources/scss/main.scss", "public/css")
    .sass(
        "resources/scss/templates/simple.scss",
        "public/css/simple.css"
    )
    .js("resources/js/app.js", "public/js/bundle.js")
    .js("resources/js/simple.js", "public/js/simple.js")
    .sourceMaps()
    .version()
    .copy("resources/js/vendor", "public/js/vendor")
    .copy("resources/img", "public/img");