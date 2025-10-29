# Checklist de déploiement en production

## ✅ Fichiers à INCLURE lors du transfert FTP

### Obligatoires :
- ✅ `vendor/` (tout le dossier - dépendances Composer)
- ✅ `composer.json` et `composer.lock`
- ✅ `dist/` (assets compilés CSS/JS)
- ✅ `inc/` (fonctionnalités PHP)
- ✅ `sections/` (blocs personnalisés)
- ✅ `templates/` (templates FSE)
- ✅ `parts/` (template parts)
- ✅ `patterns/` et `patterns-json/` (patterns de blocs)
- ✅ `woocommerce/` (templates WooCommerce)
- ✅ `js/` (scripts JS additionnels)
- ✅ `languages/` (traductions)
- ✅ Fichiers racine : `functions.php`, `style.css`, `theme.json`, `header.php`, `footer.php`, etc.

### À EXCLURE du transfert FTP :
- ❌ `src/` (sources SCSS/JS non compilées)
- ❌ `node_modules/` (dépendances npm)
- ❌ `package.json` et `package-lock.json`
- ❌ `vite.config.js`
- ❌ `.vscode/` et `*.code-workspace`
- ❌ Fichiers `.md` (README, CHANGELOG, etc.)

## 🔧 Après le transfert FTP

Si vous avez l'erreur "Failed opening required register-wp-cli-commands.php" :

1. **Vérifiez que le dossier `vendor/` est complet** sur le serveur
2. **Reconnectez-vous en SSH** et exécutez :
   ```bash
   cd wp-content/themes/taulignan-pp
   composer install --no-dev --optimize-autoloader
   ```

3. Ou **supprimez le dossier `vendor/` sur le serveur** et transférez-le à nouveau depuis votre local

## 🎯 Structure finale en production

```
taulignan-pp/
├── composer.json              ← REQUIS
├── composer.lock              ← REQUIS
├── dist/                      ← Assets compilés
├── vendor/                    ← Dépendances Composer
├── inc/                       ← Fonctionnalités
├── sections/                  ← Blocs
├── templates/                 ← Templates FSE
├── patterns/                  ← Patterns
├── woocommerce/              ← Templates WooCommerce
├── functions.php
├── style.css
└── theme.json
```

