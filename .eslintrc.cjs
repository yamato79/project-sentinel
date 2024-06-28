/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
    root: true,
    'extends': [
        'plugin:vue/vue3-essential',
        'eslint:recommended',
        '@vue/eslint-config-typescript',
    ],
    parserOptions: {
        ecmaVersion: 'latest',
    },
    rules: {
        'indent': ['error', 4],
        'vue/html-indent': ['error', 4],
        'vue/script-indent': ['error', 4],
        'quotes': ['error', 'double'],
        'semi': ['error', 'always'],
        'vue/no-unused-vars': 'error',
        // 'no-console': 'error',
        'no-debugger': 'error',
        'vue/multi-word-component-names': 'off',
        // 'no-undef': 'error',
        "vue/max-attributes-per-line": ["error", {
            "singleline": {
              "max": 10
            },
            "multiline": {
              "max": 1
            }
        }]
    },
}
