# mailchimp
Plugin mailchimp for Magix CMS

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner mailchimp.
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.

### Ajouter dans layout.tpl la ligne suivante :

```javascript
<script type="text/javascript" async>
    $(function(){
        var iso = '{getlang}';
        if (typeof MC_plugins_mailchimp == "undefined")
        {
            console.log("MC_plugins_mailchimp is not defined");
        }else{
            MC_plugins_mailchimp.run(iso);
        }
    });
</script>
```