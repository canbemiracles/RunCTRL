const path = require('path');
const webpack = require('webpack');
let testConfig  = {
    output: {
        // use absolute paths in sourcemaps (important for debugging via IDE)
        devtoolModuleFilenameTemplate: '[absolute-resource-path]',
        devtoolFallbackModuleFilenameTemplate: '[absolute-resource-path]?[hash]'
    },
    module: {
        rules: [
            { test: /\.scss$/, loader: 'null-loader' },
            { test: /\.css$/, loader: 'null-loader' },
            { test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                    // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
                    // the "scss" and "sass" values for the lang attribute to the right configs here.
                    // other preprocessors should work out of the box, no loader config like this necessary.           
                    'scss': 'null-loader',
                    'sass': 'null-loader',
                    },
                    esModule: false,
                    // other vue-loader options go here
                }
            },  
            { test: /\.eot$/, loader: 'url-loader?limit=100000&mimetype=application/vnd.ms-fontobject' },
            { test: /\.woff2$/, loader: 'url-loader?limit=100000&mimetype=application/font-woff2' },
            { test: /\.woff$/, loader: 'url-loader?limit=100000&mimetype=application/font-woff' },
            { test: /\.ttf$/, loader: 'url-loader?limit=100000&mimetype=application/font-ttf' },
            { test: /\.svg$/,
                loader: 'null-loader'
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.(png|jpg|gif)$/,
                loader: 'file-loader',
                exclude: [path.resolve('./src/stylesheets/fonts')],
                options: {
                name: 'images/[name].[ext]?[hash]'
                }
            },
        ]
    },
    plugins:[
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            moment: 'moment',
            Popper: ['popper.js', 'default']
        }),
    ],
    resolve: {
        alias: {
        'vue$': 'vue/dist/vue.esm.js',
        'bootstrap-vue$': 'bootstrap-vue/dist/bootstrap-vue.esm.js',
        'images': path.resolve(__dirname, 'src/assets/images')
        },
        extensions: ['.js', '.vue']
    },
    devServer: {
        historyApiFallback: true,
        noInfo: false,
        overlay: true,
    },
    performance: {
        hints: false
    },
    devtool: 'inline-cheap-module-source-map',
    externals: [require('webpack-node-externals')()],
}

module.exports = testConfig;