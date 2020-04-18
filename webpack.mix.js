const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')
const PurgecssPlugin = require('purgecss-webpack-plugin')
const glob = require('glob-all')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    //.sourceMaps(true, 'source-map') // development mode? https://github.com/JeffreyWay/laravel-mix/issues/1793
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
    	autoprefixer: {
            options: {
                browsers: [
                    'last 6 versions',
                ]
            }
        },
	    processCssUrls: false,
	    postCss: [ 
	    	// tailwindcss('tailwind.config.js'),
	    	require('autoprefixer')({
	            cascade: false
	        }) 
	    ],
	  })
    .webpackConfig({
        cache: true,
        plugins: [
            new PurgecssPlugin({
              paths: glob.sync([
                path.join(__dirname, './resources/**/*.php'),
                path.join(__dirname, './resources/**/*.js'),
                path.join(__dirname, './resources/**/*.vue')
              ]),
              whitelistPatterns: [/^animation|cke|v-|active/]
            }),
        ],
        resolve: {
          alias: {
            '@': __dirname + '/resources'
          },
        }
    })
    .disableNotifications()
    
