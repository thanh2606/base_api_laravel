/* global module */
module.exports = {
    env: {
        node: true
    },
    extends: [
        'eslint:recommended',
        'standard',
        'plugin:vue/vue3-recommended',
        '@vue/eslint-config-typescript',
        'prettier'
    ],
    rules: {
        'vue/attribute-hyphenation': 'off',
        'vue/no-v-html': 'off',
        'quotes': [1, 'single', { 'avoidEscape': true }],
        'vue/multi-word-component-names': 'off'
    },
    ignorePatterns: ['resources/css/presets/*', 'resources/js/StyleComponents/*']
}
