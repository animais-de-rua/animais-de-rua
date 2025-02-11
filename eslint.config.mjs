import js from '@eslint/js';

export default [
  js.configs.recommended,
  {
    files: [
      'src/**/*',
      'eslint.config.mjs',
    ],
    rules: {
      'comma-dangle': [
        'error',
        'always-multiline',
      ],
      'no-trailing-spaces': 'error',
      'no-undef': 'off',
      'func-names': 'off',
      'arrow-parens': [
        'error',
        'as-needed',
      ],
      'quotes': [
        'error',
        'single',
      ],
      'indent': [
        'error',
        2,
        {
          'SwitchCase': 1,
        },
      ],
    },
  },
];