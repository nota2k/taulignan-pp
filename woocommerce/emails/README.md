# Templates d'Emails Personnalisés - Taulignan

## 📧 Vue d'ensemble

Ce dossier contient les templates d'emails WooCommerce personnalisés pour le thème Taulignan, avec un design moderne et élégant reprenant les couleurs et polices du site.

## 🎨 Design

### Couleurs utilisées
- **Lavande** (#B8A3D1) - Couleur principale, boutons
- **Beige clair** (#F5F2E8) - Fond
- **Beige foncé** (#E6D7C3) - Bordures
- **Vert olive** (#6b764c) - Titres
- **Olive** (#8B7355) - Liens
- **Violet foncé** (#6e5b7c) - Footer

### Polices
- **Bellota Text** - Corps de texte
- **Maghfirea** - Titres

## 📁 Fichiers créés

### Templates principaux
- `email-header.php` - En-tête des emails avec logo et titre
- `email-footer.php` - Pied de page avec informations de contact
- `email-styles.php` - Styles CSS personnalisés

### Templates de confirmation
- `customer-processing-order.php` - Email de réservation reçue (statut "En cours")
- `customer-completed-order.php` - Email de réservation confirmée (statut "Terminée")
- `email-order-details.php` - Tableau des détails de commande personnalisé

## 🚀 Activation

Les templates sont automatiquement activés dès qu'ils sont présents dans le dossier du thème. WooCommerce les utilisera à la place des templates par défaut.

### Vérification
1. Aller dans **WooCommerce > Paramètres > Emails**
2. Cliquer sur un type d'email (ex: "Commande terminée")
3. Cliquer sur "Prévisualiser l'email" pour voir le rendu

## ⚙️ Configuration

### Paramètres WooCommerce
Aller dans **WooCommerce > Paramètres > Emails** :

1. **Image d'en-tête** : Télécharger votre logo (recommandé : 180px de large)
2. **Couleur de base** : #B8A3D1 (déjà configuré automatiquement)
3. **Couleur d'arrière-plan** : #F5F2E8 (déjà configuré automatiquement)
4. **Couleur du corps** : #ffffff (déjà configuré automatiquement)

### Numéro de téléphone
Pour ajouter un numéro de téléphone dans le footer des emails :

1. Aller dans **Apparence > Personnaliser**
2. Ou ajouter cette option dans votre base de données :
   ```php
   update_option('taulignan_contact_phone', '+33 X XX XX XX XX');
   ```

## 📝 Personnalisation des textes

### Sujets des emails
Les sujets sont personnalisés automatiquement :
- **Réservation reçue** : "✓ Réservation reçue #[numéro] - [Nom du site]"
- **Réservation confirmée** : "🎉 Réservation confirmée #[numéro] - [Nom du site]"

### Contenu additionnel
Dans **WooCommerce > Paramètres > Emails**, vous pouvez ajouter du contenu personnalisé pour chaque type d'email dans le champ "Contenu additionnel".

## 🎯 Fonctionnalités

### ✓ Design responsive
Les emails s'adaptent automatiquement aux mobiles

### ✓ Badge de statut
Affichage d'un badge coloré pour indiquer le statut de la réservation

### ✓ Informations de séjour
Ajout automatique de la date du séjour (depuis le champ ACF "date_sejour")

### ✓ Boutons d'action
Boutons stylisés pour "Voir ma réservation" et "Procéder au paiement"

### ✓ Terminologie adaptée
- "Commande" → "Réservation"
- "Produit" → "Séjour"

### ✓ Messages personnalisés
- Message de bienvenue chaleureux
- Informations pratiques
- Message de remerciement

## 🧪 Tests

### Tester les emails sans passer de commande

1. **Avec WooCommerce** :
   - Aller dans **WooCommerce > Paramètres > Emails**
   - Cliquer sur un type d'email
   - Cliquer sur le bouton "Envoyer un email de test"

2. **Avec une extension** :
   - Installer le plugin "WP Mail SMTP" ou "Email Log"
   - Ces plugins permettent de visualiser et tester les emails

3. **En passant une vraie commande de test** :
   - Activer le mode "Paiement par chèque" dans WooCommerce
   - Passer une commande de test
   - Vérifier la réception de l'email

## 🔧 Personnalisation avancée

### Modifier les couleurs
Éditer le fichier `email-styles.php` et modifier les variables de couleur :
```php
$bg               = '#F5F2E8'; // Fond principal
$base             = '#B8A3D1'; // Couleur primaire
$link_color       = '#8B7355'; // Couleur des liens
```

### Ajouter du contenu
Éditer les fichiers `customer-processing-order.php` ou `customer-completed-order.php` pour ajouter des sections personnalisées.

### Modifier le footer
Éditer `email-footer.php` pour personnaliser les informations de contact.

## 📱 Compatibilité

Ces templates ont été testés et optimisés pour :
- ✅ Gmail (Desktop & Mobile)
- ✅ Outlook (Desktop & Mobile)
- ✅ Apple Mail (Mac, iPhone, iPad)
- ✅ Yahoo Mail
- ✅ Thunderbird
- ✅ Clients email mobiles (iOS, Android)

## 🐛 Dépannage

### Les emails ne sont pas personnalisés
1. Vider le cache WordPress si vous utilisez un plugin de cache
2. Vérifier que les fichiers sont bien dans `/wp-content/themes/taulignan-pp/woocommerce/emails/`
3. Vérifier les permissions des fichiers (644)

### Les couleurs ne s'affichent pas
1. Certains clients email ignorent les CSS externes
2. Les couleurs sont définies en inline dans les templates pour une meilleure compatibilité
3. Tester avec différents clients email

### Les images ne s'affichent pas
1. Vérifier que l'URL de l'image d'en-tête est correcte
2. Certains clients email bloquent les images par défaut
3. Utiliser des URLs absolues (https://...)

## 📚 Documentation

- [Documentation WooCommerce Emails](https://woocommerce.com/document/email-faq/)
- [Guide des templates WooCommerce](https://woocommerce.com/document/template-structure/)

## 🆘 Support

Pour toute question ou problème, contacter le développeur du thème.

---

**Version** : 1.0.0  
**Dernière mise à jour** : Octobre 2024  
**Thème** : Taulignan

