# mailchimp
Plugin mailchimp for [magixcms](https://www.magix-cms.com)

Mailchimp est un outil d’email marketing qui vous permettra de gérer vos listes de diffusion, de concevoir des newsletters professionnels.
Le plugin intègre le système d'inscription a la newsletter de mailchimp avec un block formulaire ainsi que le visuel des inscriptions dans chaque langue.

![Plugin Mailchimp Magix CMS](https://cloud.githubusercontent.com/assets/356674/12259485/746a1bc2-b916-11e5-80d5-c039443e2c65.jpg "Plugin Mailchimp pour Magix CMS")

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner mailchimp.
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.

### Ajouter dans layout.tpl la ligne suivante :

```smarty
{block name="main:after"}
    {include file="mailchimp/form/mailchimp.tpl"}
{/block}
````