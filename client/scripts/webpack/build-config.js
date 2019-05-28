const webpackMerge = require('webpack-merge')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const webpackBaseConfig = require('./base-config')
const utils = require('../utils')

const webpackBuildConfig = webpackMerge(webpackBaseConfig, {
  optimization: {
    splitChunks: {
      cacheGroups: {
        vendors: {
          name: 'chunk-vendors',
          test: /[\\/]node_modules[\\/]/,
          priority: -10,
          chunks: 'initial',
        },
        common: {
          name: 'chunk-common',
          minChunks: 2,
          priority: -20,
          chunks: 'initial',
          reuseExistingChunk: true,
        },
      },
    },
  },

  plugins: [
    new MiniCssExtractPlugin({
      filename: utils.pathAssets(`css/[name].[hash:8].css`),
      chunkFilename: utils.pathAssets(`css/[name].[hash:8].css`),
    }),

    new OptimizeCssAssetsPlugin({
      canPrint: false,
      cssProcessorOptions: {
        autoprefixer: { disable: true },
        mergeLonghand: false,
      },
    }),
  ],
})

module.exports = webpackBuildConfig
