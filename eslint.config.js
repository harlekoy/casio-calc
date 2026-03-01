import js from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'

export default [
  js.configs.recommended,
  ...pluginVue.configs['flat/recommended'],
  {
    languageOptions: {
      globals: {
        window: 'readonly',
        document: 'readonly',
        localStorage: 'readonly',
        crypto: 'readonly',
        console: 'readonly',
      },
    },
    rules: {
      semi: ['error', 'never'],
      'vue/multi-word-component-names': 'off',
    },
  },
  {
    files: ['resources/js/components/ButtonGrid.vue'],
    rules: {
      'vue/no-v-html': 'off',
    },
  },
]
