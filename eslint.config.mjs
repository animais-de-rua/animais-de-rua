import js from '@eslint/js';

export default [
  js.configs.recommended,
  {
    files: [
      'resources/**/*',
      'eslint.config.mjs'
    ],
    rules: {
      'no-trailing-spaces': 'error',
      'no-undef': 'off',
      'func-names': 'off',
      'arrow-parens': [
        'error',
        'as-needed'
      ],
      'quotes': [
        'error',
        'single'
      ],
      'indent': [
        'error',
        2
      ]
    }
  }
];