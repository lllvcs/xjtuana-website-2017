const path = require('path')

/**
 * Resolve path from project root /
 *
 * @param {string} args
 */
function pathRoot (...args) {
  return path.resolve(__dirname, '..', '..', ...args)
}

/**
 * Resolve path from src
 *
 * @param {string} args
 */
function pathSrc (...args) {
  return pathRoot('client', 'src', ...args)
}

/**
 * Resolve path from dist
 *
 * @param {string} args
 */
function pathDist (...args) {
  return pathRoot('public', ...args)
}

/**
 * Join relative path from static directory (for output filename)
 *
 * @param {string} args
 */
function pathAssets (...args) {
  return path.posix.join('static', ...args)
}

/**
 * Get relative path from dist
 *
 * @param {string} arg
 */
function pathDistRelative (arg) {
  return path.posix.relative(pathDist(), arg)
}

module.exports = {
  pathRoot,
  pathSrc,
  pathDist,
  pathDistRelative,
  pathAssets,
}
