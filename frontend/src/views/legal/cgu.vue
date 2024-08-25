<template>
  <div class="p-4 dark-glass glass-border">
    <h1 class="text-center">Conditions Générales d'Utilisation</h1>
    <p>
      Bienvenue sur Enigmatic Journey. En utilisant notre site, vous acceptez
      les présentes Conditions Générales d'Utilisation (CGU).
    </p>

    <span :class="subTitle">1. Objet</span>
    <p>
      Les présentes CGU ont pour objet de définir les conditions d'utilisation
      de notre site et des services proposés.
    </p>

    <span :class="subTitle">2. Accès au site</span>
    <p>
      Notre site est accessible gratuitement à tout utilisateur disposant d'un
      accès à Internet. Tous les coûts relatifs à l'accès au site, que ce soit
      les frais matériels, logiciels ou d'accès à Internet, sont exclusivement à
      la charge de l'utilisateur.
    </p>

    <span :class="subTitle">3. Collecte des données</span>
    <p>
      Nous collectons des données personnelles conformément à la réglementation
      en vigueur, notamment le RGPD. Les données collectées sont nécessaires
      pour le bon fonctionnement de nos services.
    </p>

    <span :class="subTitle">4. Utilisation des données</span>
    <p>Les données collectées sont utilisées pour :</p>
    <ul>
      <li>Fournir et améliorer nos services</li>
      <li>Gérer les inscriptions et les connexions des utilisateurs</li>
      <li>Envoyer des informations et des notifications importantes</li>
    </ul>

    <span :class="subTitle">5. Droits des utilisateurs</span>
    <p>Conformément au RGPD, vous disposez des droits suivants :</p>
    <ul>
      <li>
        Droit d'accès : Vous pouvez demander à accéder à vos données
        personnelles.
      </li>
      <li>
        Droit de rectification : Vous pouvez demander la correction de vos
        données personnelles si elles sont inexactes.
      </li>
      <li>
        Droit à l'effacement : Vous pouvez demander la suppression de vos
        données personnelles.
      </li>
      <li>
        Droit à la limitation du traitement : Vous pouvez demander la limitation
        du traitement de vos données personnelles.
      </li>
      <li>
        Droit à la portabilité : Vous pouvez demander à recevoir vos données
        personnelles dans un format structuré et couramment utilisé.
      </li>
      <li>
        Droit d'opposition : Vous pouvez vous opposer au traitement de vos
        données personnelles.
      </li>
    </ul>

    <span :class="subTitle">6. Sécurité des données</span>
    <p>
      Nous mettons en œuvre toutes les mesures techniques et organisationnelles
      nécessaires pour protéger vos données personnelles contre toute
      destruction, perte, altération, divulgation ou accès non autorisé.
    </p>

    <span :class="subTitle">7. Cookies</span>
    <p>
      Notre site utilise des cookies pour améliorer l'expérience utilisateur.
      Vous pouvez configurer votre navigateur pour refuser les cookies ou être
      informé lorsqu'un cookie est envoyé.
    </p>

    <span :class="subTitle">8. Modification des CGU</span>
    <p>
      Nous nous réservons le droit de modifier les présentes CGU à tout moment.
      Les modifications prendront effet dès leur publication sur le site.
    </p>

    <span :class="subTitle">9. Contact</span>
    <p>
      Pour toute question concernant les présentes CGU ou vos données
      personnelles, vous pouvez nous contacter à l'adresse suivante : [votre
      email de contact].
    </p>
  </div>
</template>
<script setup>
const subTitle = "block text-2xl font-bold mt-4";
</script>
