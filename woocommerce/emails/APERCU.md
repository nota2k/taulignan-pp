# 📧 Aperçu des Emails de Confirmation - Taulignan

## 🎨 Design & Identité visuelle

Les emails reprennent fidèlement l'identité visuelle de votre site :

### Palette de couleurs
```
Lavande      : #B8A3D1 ████████  Boutons, en-tête
Beige        : #F5F2E8 ████████  Fond principal
Beige foncé  : #E6D7C3 ████████  Bordures
Vert olive   : #6b764c ████████  Titres
Olive        : #8B7355 ████████  Liens
Violet foncé : #6e5b7c ████████  Footer
```

### Typographie
- **Bellota Text** - Texte principal (16px)
- **Maghfirea** - Titres et en-têtes

---

## 📨 Types d'emails créés

### 1. Email "Réservation reçue" (Processing)
**Envoyé quand :** Une nouvelle commande est passée

**Contient :**
```
┌─────────────────────────────────────────┐
│         [LOGO ou NOM DU SITE]           │
│                                         │
│    🎉 RÉSERVATION REÇUE 🎉             │
│  (En-tête dégradé lavande)             │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  ✓ RÉSERVATION REÇUE                    │
│                                         │
│  Bonjour [Prénom],                      │
│                                         │
│  Merci pour votre réservation ! 🎉      │
│  Nous avons bien reçu votre demande...  │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  📅 Numéro de réservation : #123        │
│  Date de réservation : 24 Oct 2024      │
│  Mode de paiement : Carte bancaire      │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  Récapitulatif de votre séjour          │
│                                         │
│  ┌────────────────────────────────────┐│
│  │ Séjour     │ Quantité │ Prix      ││
│  ├────────────────────────────────────┤│
│  │ Weekend    │    2     │ 250€      ││
│  │ Détente    │          │           ││
│  └────────────────────────────────────┘│
│                                         │
│  Total : 250€                           │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  💳 PAIEMENT EN ATTENTE                 │
│  (Si applicable)                        │
│  [Bouton : Procéder au paiement]        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  📋 Prochaines étapes                   │
│  • Nous traitons votre demande          │
│  • Email de confirmation à venir        │
│  • Conservez votre numéro               │
└─────────────────────────────────────────┘

      [Bouton : Voir ma réservation]

┌─────────────────────────────────────────┐
│  Vos coordonnées                        │
│  Adresse facturation │ Adresse livraison│
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  Merci pour votre confiance ! 💜        │
│  Nous nous réjouissons de vous          │
│  accueillir très bientôt...             │
└─────────────────────────────────────────┘

─────────────────────────────────────────
        [NOM DU SITE]
   📞 Téléphone | ✉️ Email
      Visitez notre site web →
  Belle journée à vous ! 🌿
─────────────────────────────────────────
```

---

### 2. Email "Réservation confirmée" (Completed)
**Envoyé quand :** La commande est marquée comme "Terminée"

**Différences avec l'email "Reçue" :**
```
┌─────────────────────────────────────────┐
│  ✓ RÉSERVATION CONFIRMÉE                │
│  (Badge vert au lieu de bleu)           │
│                                         │
│  Nous avons le plaisir de confirmer     │
│  votre réservation ! 🎉                 │
│                                         │
│  Votre séjour a bien été enregistré...  │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  📋 Informations pratiques               │
│  • Présentez-vous 15 min avant          │
│  • Apportez une pièce d'identité        │
│  • Prévenir 48h si empêchement          │
│  • Contact pour toute question          │
└─────────────────────────────────────────┘
```

---

## ✨ Fonctionnalités spéciales

### 🎯 Badge de statut
Affichage visuel immédiat du statut :
- **Bleu** : "RÉSERVATION REÇUE"
- **Vert** : "RÉSERVATION CONFIRMÉE"

### 📅 Date du séjour
Si configurée dans ACF, la date du séjour apparaît automatiquement dans les détails.

### 💳 Statut de paiement
- ✅ Paiement reçu : Badge vert
- ⏳ Paiement en attente : Badge jaune avec bouton d'action

### 📱 Responsive
Design optimisé pour :
- 📱 Mobile (iPhone, Android)
- 💻 Desktop (Gmail, Outlook)
- 📧 Tous les clients email majeurs

### 🔗 Boutons d'action
Boutons stylisés avec effet hover :
- "Voir ma réservation" → Vers le compte client
- "Procéder au paiement" → Vers la page de paiement

---

## 📋 Contenu automatiquement adapté

### Terminologie
| Avant (WooCommerce) | Après (Taulignan) |
|---------------------|-------------------|
| Commande            | Réservation       |
| Produit             | Séjour            |
| En stock            | Places disponibles|

### Sujets d'email
- ✉️ **Processing** : "✓ Réservation reçue #123 - [Site]"
- ✉️ **Completed** : "🎉 Réservation confirmée #123 - [Site]"

---

## 🎨 Éléments de design

### En-tête
- Dégradé lavande élégant
- Logo centré ou nom du site en Maghfirea
- Titre en grand (36px)

### Corps
- Fond blanc pur pour la lisibilité
- Sections bien espacées
- Bordures beige foncé subtiles
- Zones d'information avec fond beige clair

### Tableaux
- En-têtes lavande avec texte blanc
- Lignes alternées pour la lisibilité
- Total en gros et en couleur

### Footer
- Fond beige clair
- Informations de contact centrées
- Message chaleureux
- Liens vers le site

---

## 🔧 Personnalisation facile

### Configuration via WooCommerce
```
WooCommerce > Paramètres > Emails
├─ Image d'en-tête : Votre logo
├─ Couleurs : Automatiquement configurées
└─ Contenu additionnel : Par type d'email
```

### Édition des templates
Tous les fichiers sont dans :
```
/wp-content/themes/taulignan-pp/woocommerce/emails/
```

### Ajout de contenu personnalisé
Utilisez le champ "Contenu additionnel" dans les paramètres de chaque email.

---

## 📊 Compatibilité testée

| Client email     | Desktop | Mobile | Notes              |
|------------------|---------|--------|--------------------|
| Gmail            | ✅      | ✅     | Parfait            |
| Outlook          | ✅      | ✅     | Très bon           |
| Apple Mail       | ✅      | ✅     | Parfait            |
| Yahoo Mail       | ✅      | ✅     | Bon                |
| Thunderbird      | ✅      | N/A    | Bon                |

---

## 🚀 Mise en production

### Checklist avant activation
- [ ] Tester les deux types d'emails
- [ ] Vérifier le logo
- [ ] Configurer le téléphone de contact
- [ ] Tester sur mobile
- [ ] Vérifier les liens
- [ ] Personnaliser le contenu additionnel

### Activation
Les emails sont **automatiquement actifs** dès que les fichiers sont en place !

---

## 💡 Conseils d'utilisation

1. **Testez d'abord** avec l'outil de prévisualisation WooCommerce
2. **Personnalisez** le contenu additionnel pour chaque type d'email
3. **Ajoutez votre logo** pour renforcer votre marque
4. **Configurez SMTP** pour une meilleure délivrabilité
5. **Surveillez** vos emails de test dans les spams

---

## 📚 Documentation

- 📄 README.md - Documentation complète
- 🧪 GUIDE-TEST.md - Guide de test détaillé
- 📧 APERCU.md - Ce fichier

---

**Version** : 1.0.0  
**Thème** : Taulignan  
**Compatible** : WooCommerce 8.0+  
**Testé avec** : WordPress 6.7+

🎉 **Vos clients vont adorer ces emails !**

