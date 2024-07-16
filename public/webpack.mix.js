const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/admin-lte/dist/js/adminlte.min.js', 'public/js')
   .copy('node_modules/admin-lte/dist/css/adminlte.min.css', 'public/css')
   .copy('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/css/fontawesome.min.css')
   .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');
