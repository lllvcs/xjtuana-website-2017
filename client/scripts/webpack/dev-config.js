const webpackMerge = require('webpack-merge')
const webpackBaseConfig = require('./base-config')

const webpackDevConfig = webpackMerge(webpackBaseConfig, {
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
        options: {
          fix: true,
        },
      },
    ],
  },
})

module.exports = webpackDevConfig
