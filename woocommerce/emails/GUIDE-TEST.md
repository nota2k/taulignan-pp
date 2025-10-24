# 🧪 Guide de Test des Emails

## Méthode 1 : Via l'interface WooCommerce (Recommandé)

### Étape 1 : Accéder aux paramètres
1. Connectez-vous à l'administration WordPress
2. Allez dans **WooCommerce > Paramètres**
3. Cliquez sur l'onglet **Emails**

### Étape 2 : Prévisualiser un email
1. Cliquez sur un type d'email (ex: "Commande terminée")
2. En haut de la page, cliquez sur le bouton violet **"Prévisualiser l'email"**
3. L'email s'affichera dans votre navigateur avec les styles appliqués

### Étape 3 : Envoyer un email de test
1. Toujours dans les paramètres d'un email
2. Activez l'email si nécessaire
3. Cliquez sur **"Envoyer un email de test"**
4. L'email sera envoyé à l'adresse de l'administrateur du site

## Méthode 2 : Passer une commande de test

### Préparation
1. Allez dans **WooCommerce > Paramètres > Paiements**
2. Activez le mode **"Paiement à la livraison"** ou **"Paiement par chèque"**
3. Assurez-vous d'avoir un produit (séjour) disponible

### Passer la commande
1. Ouvrez votre site en navigation privée (ou déconnectez-vous)
2. Ajoutez un séjour au panier
3. Passez la commande avec une vraie adresse email que vous contrôlez
4. Complétez la commande

### Vérifier les emails reçus
Vous devriez recevoir :
- ✉️ Email de **"Réservation reçue"** (immédiat)
- ✉️ Email de **"Réservation confirmée"** (après changement de statut)

## Méthode 3 : Avec WP CLI (Avancé)

Si vous avez accès au terminal :

```bash
# Envoyer l'email de commande terminée
wp wc tool run regenerate_thumbnails

# Tester l'envoi d'email
wp eval "WC()->mailer()->emails['WC_Email_Customer_Completed_Order']->trigger(123);"
```

Remplacez `123` par l'ID d'une vraie commande.

## 📋 Checklist de vérification

Lors de la réception d'un email de test, vérifiez :

### Design général
- [ ] Les couleurs correspondent au thème (lavande, beige, vert olive)
- [ ] Le logo ou nom du site s'affiche correctement
- [ ] Les polices sont lisibles et cohérentes
- [ ] L'email est responsive (testez sur mobile)

### Contenu
- [ ] Le badge "RÉSERVATION CONFIRMÉE" ou "RÉSERVATION REÇUE" s'affiche
- [ ] Le numéro de réservation est correct
- [ ] Les détails du séjour sont complets
- [ ] La date du séjour s'affiche (si configurée via ACF)
- [ ] Le prix total est correct
- [ ] Les adresses de facturation sont complètes

### Boutons et liens
- [ ] Le bouton "Voir ma réservation" fonctionne
- [ ] Les liens dans le footer fonctionnent
- [ ] Le lien email de contact est cliquable

### Footer
- [ ] Le nom du site s'affiche
- [ ] L'email de contact est correct
- [ ] Le numéro de téléphone s'affiche (si configuré)
- [ ] Le message "Belle journée à vous ! 🌿" apparaît

## 🎨 Tester sur différents clients email

### Clients de bureau
- [ ] Gmail (Web)
- [ ] Outlook (Web)
- [ ] Apple Mail (Mac)

### Clients mobiles
- [ ] Gmail (iPhone/Android)
- [ ] Apple Mail (iPhone)
- [ ] Outlook (mobile)

## 🐛 Problèmes courants et solutions

### ❌ L'email n'arrive pas
**Solutions :**
1. Vérifier les paramètres SMTP dans WooCommerce
2. Installer le plugin "WP Mail SMTP" pour configurer l'envoi
3. Vérifier le dossier spam/courrier indésirable
4. Tester avec une autre adresse email

### ❌ Les couleurs ne s'affichent pas
**Solutions :**
1. Les couleurs sont définies en inline dans les templates
2. Certains clients email (Outlook ancien) peuvent modifier les couleurs
3. Tester avec Gmail qui a une meilleure compatibilité

### ❌ Les images ne s'affichent pas
**Solutions :**
1. Vérifier que l'URL de l'image d'en-tête est absolue (https://...)
2. Vérifier que l'image est accessible publiquement
3. Certains clients bloquent les images par défaut (bouton "Afficher les images")

### ❌ Le design est cassé
**Solutions :**
1. Vider le cache WordPress
2. Vérifier que tous les fichiers sont bien uploadés
3. Tester avec un autre client email

## 📊 Outils de test en ligne

Pour tester l'affichage sur plusieurs clients email :

1. **Litmus** - https://www.litmus.com/
   - Service payant mais très complet
   - Teste sur 90+ clients email

2. **Email on Acid** - https://www.emailonacid.com/
   - Tests approfondis et rapports détaillés

3. **Mail Tester** - https://www.mail-tester.com/
   - Gratuit, vérifie le score spam

## 📝 Modifier les textes

### Changer le message de bienvenue
Éditez : `customer-processing-order.php` ou `customer-completed-order.php`

Cherchez la section :
```php
<p style="font-size: 16px; margin: 0 0 12px;">
    Merci pour votre réservation ! 🎉
</p>
```

### Changer les informations pratiques
Cherchez la section "Informations pratiques" et modifiez les points de la liste `<ul>`.

### Ajouter un logo
1. Allez dans **WooCommerce > Paramètres > Emails**
2. Section "Image d'en-tête"
3. Téléchargez votre logo (recommandé : 180px de large)

## 💡 Conseils

1. **Testez toujours** après chaque modification
2. **Gardez une copie** des templates avant modification
3. **Testez sur mobile** - 50%+ des emails sont lus sur mobile
4. **Évitez les images lourdes** - privilégiez les couleurs de fond
5. **Utilisez du texte** plutôt que des images pour le contenu important

## 🆘 Besoin d'aide ?

Si vous rencontrez des problèmes :

1. Consultez le fichier `README.md` dans ce même dossier
2. Vérifiez la documentation WooCommerce
3. Contactez le support du thème

---

**Bonne chance avec vos tests ! 🚀**

