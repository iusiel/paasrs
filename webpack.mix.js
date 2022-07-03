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

mix.js("resources/js/app.js", "public/js").vue();
mix.sass("resources/css/app.scss", "public/css");
mix.scripts(
    "resources/plugins/select2-4.1.0-rc.0/dist/js/select2.min.js",
    "public/js/select2.min.js"
);

mix.scripts(
    "node_modules/simplemde/dist/simplemde.min.js",
    "public/js/simplemde.min.js"
);
