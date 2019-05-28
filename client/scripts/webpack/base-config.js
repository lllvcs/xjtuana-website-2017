const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const fs = require('fs')
const utils = require('../utils')

const isProd = process.env.NODE_ENV === 'production'

// 为cache-loader设置目录和identifier
const cacheDirectory = utils.pathRoot('node_modules/.cache/client')
const cacheIdentifier = JSON.stringify({
  'cache-loader': require('cache-loader').version,
  'vue-loader': require('vue-loader').version,
  isProd,
})

const webpackBaseConfig = {
  mode: isProd ? 'production' : 'development',

  devtool: isProd ? false : 'cheap-module-eval-source-map',

  context: utils.pathRoot(),

  entry: {},

  output: {
    path: utils.pathDist(),
    filename: utils.pathAssets(`js/[name]${isProd ? '.[contenthash:8]' : ''}.js`),
    chunkFilename: utils.pathAssets(`js/[name]${isProd ? '.[contenthash:8]' : ''}.js`),
    publicPath: '/',
  },

  resolve: {
    extensions: ['.js', '.vue', '.json', '.md'],
    alias: {
      '@': utils.pathSrc(),
      // 现在home不能用runtime-only版本
      // 'vue$': 'vue/dist/vue.runtime.esm.js',
      'vue$': 'vue/dist/vue.esm.js',
    },
  },

  module: {
    rules: [
      {
        test: /\.md$/,
        use: [
          {
            loader: 'vue-loader',
            options: {
              cacheDirectory,
              cacheIdentifier,
            },
          },
          {
            loader: 'vue-markdown-loader/lib/markdown-compiler',
            options: {
              linkify: true,
              raw: true,
            },
          },
        ],
      },

      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          cacheDirectory,
          cacheIdentifier,
        },
      },

      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'cache-loader',
            options: {
              cacheDirectory,
            },
          },
          'babel-loader',
        ],
      },

      {
        test: /\.css$/,
        use: [
          ...useCommonStyleLoaders(isProd),
        ],
      },

      {
        test: /\.styl(us)?$/,
        use: [
          ...useCommonStyleLoaders(isProd),
          {
            loader: 'stylus-loader',
            options: {
              preferPathResolver: 'webpack',
            },
          },
        ],
      },

      {
        test: /\.scss$/,
        use: [
          ...useCommonStyleLoaders(isProd),
          {
            loader: 'sass-loader',
          },
        ],
      },

      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/i,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: utils.pathAssets(`fonts/[name]${isProd ? '.[hash:8]' : ''}.[ext]`),
        },
      },

      {
        test: /\.(png|jpe?g|gif)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: utils.pathAssets(`img/[name]${isProd ? '.[hash:8]' : ''}.[ext]`),
        },
      },

      {
        test: /\.(svg)(\?.*)?$/,
        loader: 'file-loader',
        options: {
          name: utils.pathAssets(`img/[name]${isProd ? '.[hash:8]' : ''}.[ext]`),
        },
      },
    ],
  },

  plugins: [
    new VueLoaderPlugin(),

    new CopyWebpackPlugin([
      {
        from: 'client/public',
        to: utils.pathAssets(),
      },
    ]),
  ],

  node: {
    Buffer: false,
    setImmediate: false,
    global: false,
    process: false,
    dgram: 'empty',
    fs: 'empty',
    net: 'empty',
    tls: 'empty',
    child_process: 'empty',
  },
}

// 生成每个页面对应的entry和html-plugin
autoGenPages()

function autoGenPages () {
  // 获取client/src/pages下所有页面
  const pages = fs.readdirSync(utils.pathSrc('pages'))

  // 暂时不自动inject的页面
  const noInject = ['home']

  for (const entryName of pages) {
    // 生成对应entry
    webpackBaseConfig.entry[entryName] = `./client/src/pages/${entryName}/main.js`

    // 添加对应html-plugin
    webpackBaseConfig.plugins.push(genHtmlPlugin(entryName, { inject: !noInject.includes(entryName) }))
  }
}

function useCommonStyleLoaders () {
  const VueStyleLoader = {
    loader: 'vue-style-loader',
  }

  const MiniCssExtractPluginLoader = {
    loader: MiniCssExtractPlugin.loader,
  }

  const CSSLoader = {
    loader: 'css-loader',
    options: {
      localIdentName: `[local]_[hash:base64:8]`,
      sourceMap: !isProd,
    },
  }

  const PostCSSLoader = {
    loader: 'postcss-loader',
    options: {
      plugins: [require('autoprefixer')],
      sourceMap: !isProd,
    },
  }

  return [
    isProd ? MiniCssExtractPluginLoader : VueStyleLoader,
    CSSLoader,
    PostCSSLoader,
  ]
}

function genHtmlPlugin (entryName, options = {}) {
  return new HtmlWebpackPlugin(Object.assign({
    template: `client/src/pages/${entryName}/index.html`,
    filename: utils.pathDistRelative(`resources/views/${entryName}.blade.php`),
    chunks: ['chunk-vendors', 'chunk-common', entryName],
    chunksSortMode: 'manual',
    inject: true,
    minify: isProd ? {
      removeComments: true,
      collapseWhitespace: true,
      removeAttributeQuotes: true,
      collapseBooleanAttributes: true,
      removeScriptTypeAttributes: true,
    } : false,
  }, options))
}

module.exports = webpackBaseConfig
