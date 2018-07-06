var path = require('path')
var webpack = require('webpack')
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');

module.exports   = {
  entry: {
    entry: './src/main.js',
    styles: './src/stylesheets/index.js',
  },
  output: {
    path: path.resolve(__dirname, './dist'),
    publicPath: '/',
    chunkFilename: '[name].bundle.js',
    filename: '[name].bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.css$/,
        use:[
          'style-loader',
          'css-loader'
        ],
      },
      {
        test: /\.scss$/,
        use: [
          'style-loader',
          'css-loader', 
          'postcss-loader',
          'sass-loader'
        ],
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
            // the "scss" and "sass" values for the lang attribute to the right configs here.
            // other preprocessors should work out of the box, no loader config like this necessary.           
            'scss': [
              'vue-style-loader',
              'css-loader',
              'postcss-loader',
              'sass-loader',
              {
                loader: 'sass-resources-loader',
                options: {
                  resources:[
                    './src/stylesheets/variables.scss',
                    './src/stylesheets/mixins.scss',
                  ]
                }
              }
            ],
            'sass': 'vue-style-loader!css-loader!sass-loader?indentedSyntax'
          },
          esModule: false,
          transformToRequire: {
            img: 'src',
            image: 'xlink:href',
            'b-img': 'src',
            'b-img-lazy': ['src', 'blank-src'],
            'b-card': 'img-src',
            'b-card-img': 'img-src',
            'b-carousel-slide': 'img-src',
            'b-embed': 'src'
          }
          // other vue-loader options go here
        }
      },
      { test: /\.eot$/, loader: 'url-loader?limit=100000&mimetype=application/vnd.ms-fontobject' },
      { test: /\.woff2$/, loader: 'url-loader?limit=100000&mimetype=application/font-woff2' },
      { test: /\.woff$/, loader: 'url-loader?limit=100000&mimetype=application/font-woff' },
      { test: /\.ttf$/, loader: 'url-loader?limit=100000&mimetype=application/font-ttf' },
      { test: /\.svg$/,
        include: path.resolve('./src/stylesheets/fonts'), 
        loader: 'url-loader?limit=100000&mimetype=application/font-svg' 
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        loader: 'file-loader',
        exclude: [path.resolve('./src/assets/images/sprites'), path.resolve('./src/stylesheets/fonts')],
        options: {
          name: 'images/[name].[ext]?[hash]'
        }
      },
      {
        test: /\.svg$/,
        include: path.resolve('./src/assets/images/sprites'),
        use: [
          {
            loader: 'svg-sprite-loader',
            options: {
              extract: true,
              spriteFilename: 'images/icons-sprite.svg'
            }
          }
        ],
      }
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
    new SpriteLoaderPlugin({
      spriteAttrs: {
        id: 'svgdefs'
      }
    })
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
  devtool: '#eval-source-map',
}

if (process.env.NODE_ENV === 'production') {
  module.exports.devtool = '#source-map'
  // http://vue-loader.vuejs.org/en/workflow/production.html
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      compress: {
        warnings: false
      }
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true
    })
  ])
}
