const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
   ])
   .copy('resources/css/enhanced-ui.css', 'public/css')
   .copy('resources/js/enhanced-ui.js', 'public/js');