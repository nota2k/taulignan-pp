import uupVite from "vite-plugin-uup";
import autoprefixer from "autoprefixer";
import liveReload from "vite-plugin-live-reload";
import { checker } from "vite-plugin-checker";
import externalGlobals from "rollup-plugin-external-globals";
import { defineConfig } from "vite";

const port = process.env.VITE_PORT || 1559;
const isCI = process.env.CI === 'true' || process.env.GITHUB_ACTIONS === 'true';

export default defineConfig({
  server: {
    port,
  },

  build: {
    rollupOptions: {
      input: [
        "src/js/main.js",
        "src/js/editor.js",
        "src/scss/_theme.scss",
        "src/scss/_block-editor.scss",
      ],
    },
    manifest: true,
    outDir: 'dist',
  },

  plugins: [
    // Désactiver uupVite en CI/CD car il n'y a pas de WordPress
    !isCI && uupVite(),

    // Désactiver liveReload en CI/CD (pas de serveur de dev)
    !isCI && liveReload(__dirname + "/**/**.php"),

    checker({
      stylelint: {
        lintCommand: "stylelint ./src/scss/**/*.scss --fix",
      },
    }),

    externalGlobals({
      jquery: "jQuery",
      "@wordpress/blocks": "wp.blocks",
      "@wordpress/i18n": "wp.i18n",
    }),
  ].filter(Boolean), // Retirer les plugins false/null
  css: {
    postcss: {
      plugins: [autoprefixer()],
    },
    devSourcemap: true,
  },
  resolve: {
    alias: {
      "@mixins": "/src/scss/generic/mixins",
      // "@functions": "/src/scss/generic/functions",
      "@variables": "/src/scss/generic/variables",
      // "@components": "/src/scss/components",
      // "@generic": "/src/scss/generic",
    },
  },
});
