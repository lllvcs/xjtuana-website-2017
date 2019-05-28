module.exports = {
  root: true,

  parserOptions: {
    parser: 'babel-eslint',
  },

  env: {
    node: true,
    browser: true,
  },

  extends: [
    'plugin:vue/essential',
    'standard',
  ],
  
  globals: {
    '$': true,
    'swal': true,
  },

  rules: {
    'comma-dangle': ['error', 'always-multiline'],
  },
}
