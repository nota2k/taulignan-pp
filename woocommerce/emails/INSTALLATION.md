# 🚀 Installation et Configuration - Emails Taulignan

## ✅ Fichiers installés

### Templates d'emails
```
woocommerce/emails/
├── 📧 customer-processing-order.php     (Email "Réservation reçue")
├── 📧 customer-completed-order.php      (Email "Réservation confirmée")
├── 📄 email-header.php                  (En-tête personnalisé)
├── 📄 email-footer.php                  (Footer personnalisé)
├── 🎨 email-styles.php                  (Styles CSS)
├── 📦 email-order-details.php           (Tableau des produits)
├── 📍 email-addresses.php               (Affichage des adresses)
└── 📚 Documentation/
    ├── README.md                        (Documentation complète)
    ├── GUIDE-TEST.md                    (Guide de test)
    ├── APERCU.md                        (Aperçu visuel)
    └── INSTALLATION.md                  (Ce fichier)
```

### Modifications dans functions.php
✅ Fonctions ajoutées pour :
- Personnaliser les sujets d'emails
- Personnaliser les titres d'emails
- Ajouter les dates de séjour
- Changer la terminologie (Commande → Réservation)
- Configurer les couleurs par défaut
- Ajouter un message de footer personnalisé

---

## 🎯 Configuration automatique

Les emails sont **automatiquement activés** ! Aucune manipulation nécessaire.

### Ce qui est déjà configuré :

✅ **Couleurs du thème**
- Fond : Beige (#F5F2E8)
- Primaire : Lavande (#B8A3D1)
- Liens : Olive (#8B7355)
- Texte : Noir (#000000)

✅ **Polices**
- Corps : Bellota Text
- Titres : Maghfirea

✅ **Traductions**
- "Commande" → "Réservation"
- "Produit" → "Séjour"
- "En stock" → "Places disponibles"

✅ **Design responsive**
- Optimisé mobile
- Compatible tous clients email

---

## ⚙️ Configuration recommandée

### 1. Logo du site (Recommandé)

**Étape par étape :**
1. Préparez votre logo (PNG transparent recommandé)
2. Taille idéale : 180px de large
3. Allez dans **WooCommerce > Paramètres > Emails**
4. Section "Options"
5. Champ "Image d'en-tête d'email"
6. Cliquez sur "Télécharger" et sélectionnez votre logo
7. Sauvegardez

### 2. Numéro de téléphone (Optionnel)

**Méthode 1 : Via PHP**
```php
// Dans votre fichier functions.php ou via phpMyAdmin
update_option('taulignan_contact_phone', '+33 X XX XX XX XX');
```

**Méthode 2 : Via base de données**
1. Accédez à phpMyAdmin
2. Table `wp_options`
3. Cherchez ou créez l'option `taulignan_contact_phone`
4. Ajoutez votre numéro

### 3. Contenu additionnel (Optionnel)

Pour chaque type d'email :

1. Allez dans **WooCommerce > Paramètres > Emails**
2. Cliquez sur un email (ex: "Commande terminée")
3. Descendez jusqu'à "Contenu additionnel de l'email"
4. Ajoutez votre texte personnalisé
5. Exemples :
   ```
   N'oubliez pas votre pièce d'identité !
   
   Rendez-vous 15 minutes avant le début.
   
   En cas de question : contact@example.com
   ```

### 4. Configuration SMTP (Recommandé)

Pour une meilleure délivrabilité :

**Option 1 : Plugin WP Mail SMTP**
1. Installez "WP Mail SMTP" depuis le répertoire WordPress
2. Configurez avec vos identifiants SMTP
3. Testez l'envoi

**Option 2 : Plugin Post SMTP**
1. Installez "Post SMTP Mailer"
2. Suivez l'assistant de configuration
3. Testez avec l'outil intégré

---

## 🧪 Tester les emails

### Test rapide (2 minutes)

1. Allez dans **WooCommerce > Paramètres > Emails**
2. Cliquez sur "Commande terminée"
3. En haut, cliquez sur **"Prévisualiser l'email"**
4. L'email s'affiche dans le navigateur
5. Vérifiez le design et les couleurs

### Test complet (5 minutes)

1. Activez "Paiement à la livraison" dans **WooCommerce > Paiements**
2. Ouvrez votre site en navigation privée
3. Ajoutez un séjour au panier
4. Complétez une commande avec votre email
5. Vérifiez la réception de l'email

### Test sur mobile

Transférez l'email reçu sur votre smartphone et vérifiez :
- ✅ Design responsive
- ✅ Boutons cliquables
- ✅ Images visibles
- ✅ Texte lisible

---

## 🎨 Personnalisation

### Modifier les couleurs

Éditez : `email-styles.php`

```php
// Ligne 13-19
$bg               = '#F5F2E8'; // Fond
$base             = '#B8A3D1'; // Couleur primaire
$link_color       = '#8B7355'; // Liens
$heading_color    = '#6b764c'; // Titres
```

### Modifier les textes

**Email "Réservation reçue"** : `customer-processing-order.php`  
**Email "Réservation confirmée"** : `customer-completed-order.php`

Cherchez les sections entre les balises `<p>` et modifiez le texte.

### Ajouter des sections

Exemple pour ajouter une section "Conditions" :

```php
// Dans customer-completed-order.php, avant le footer

<div style="background-color: #FDFCFA; padding: 24px; border-radius: 8px; margin: 32px 0;">
    <h3 style="color: #6b764c; font-size: 18px; margin: 0 0 12px;">
        📋 Conditions
    </h3>
    <p style="margin: 0; line-height: 1.6;">
        Votre texte ici...
    </p>
</div>
```

---

## 🔍 Vérification de l'installation

### Checklist complète

- [ ] Tous les fichiers sont présents dans `/woocommerce/emails/`
- [ ] Les templates s'affichent en prévisualisation
- [ ] Le logo est configuré (si souhaité)
- [ ] Les couleurs correspondent au thème
- [ ] Les emails arrivent bien en boîte de réception
- [ ] Le design est correct sur mobile
- [ ] Les liens fonctionnent
- [ ] Les boutons sont cliquables
- [ ] Le numéro de téléphone s'affiche (si configuré)

### Commandes de vérification

**Via WordPress :**
```
WooCommerce > Paramètres > Emails > [Prévisualiser]
```

**Via fichiers :**
```
/wp-content/themes/taulignan-pp/woocommerce/emails/
```

---

## 🐛 Dépannage

### Les emails ne sont pas personnalisés

**Problème :** Les emails utilisent toujours le design par défaut

**Solutions :**
1. Vérifier que les fichiers sont dans le bon dossier
2. Vider le cache (si plugin de cache actif)
3. Vérifier les permissions des fichiers (644)
4. Désactiver/réactiver le thème

### Les couleurs ne correspondent pas

**Problème :** Les couleurs sont différentes du thème

**Solutions :**
1. Vérifier `email-styles.php` lignes 13-19
2. Certains clients email modifient les couleurs (normal)
3. Tester avec Gmail qui respecte mieux les styles

### Le logo ne s'affiche pas

**Problème :** Le logo n'apparaît pas dans l'email

**Solutions :**
1. Vérifier que l'URL est absolue (https://...)
2. Vérifier que l'image est publiquement accessible
3. Tester avec une autre image
4. Certains clients bloquent les images par défaut

### Les emails n'arrivent pas

**Problème :** Aucun email reçu

**Solutions :**
1. Vérifier le dossier spam
2. Installer un plugin SMTP (WP Mail SMTP)
3. Vérifier les logs d'erreur PHP
4. Contacter l'hébergeur

---

## 📞 Support

### Ressources

📄 **Documentation**
- README.md - Documentation complète
- GUIDE-TEST.md - Guide de test
- APERCU.md - Aperçu visuel

🔗 **Liens utiles**
- [Documentation WooCommerce](https://woocommerce.com/document/email-faq/)
- [WordPress Email Support](https://wordpress.org/support/article/settings-email/)

### Besoin d'aide ?

1. Consultez d'abord les fichiers de documentation
2. Vérifiez la checklist de dépannage
3. Contactez le développeur du thème

---

## 🎉 C'est terminé !

Vos emails de confirmation de réservation sont maintenant :
- ✅ Installés
- ✅ Configurés
- ✅ Prêts à être utilisés

**Prochaines étapes :**
1. Testez avec une commande de test
2. Vérifiez sur mobile
3. Personnalisez si nécessaire
4. Profitez !

---

**Version** : 1.0.0  
**Date** : Octobre 2024  
**Auteur** : Thème Taulignan  
**Licence** : Utilisation dans le cadre du thème

💜 **Merci d'utiliser les emails personnalisés Taulignan !**

