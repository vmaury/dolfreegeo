# Utilisation bulk geocoder avec https://adresse.data.gouv.fr

Procédure permettant de geocoder toutes les adresses d'une table en un coup

- installer et activer le module freegeo afin que tous les extrafields soient créés
- exporter les données de la table en CSV : exemple llx_socpeople.csv
- supprimer les colonnes comme dans l'exemple llx_socpeople.csv
- allez sur https://adresse.data.gouv.fr/csv#preview et importer le fichier
- sélectionner les colonnes address, zip et town pour l'encodage
- le site génère le fichier llx_socpeople.geocoded.csv
- l'ouvrir avec libreoffice (ou excel :-\) 
- rajouter la colonne I, et y coller le contenu (entête + formule) de la colonne présent dans llx_socpeople.geocoded.ods
- copier tout le contenu de la colonne I sauf l'entête et le coller dans votre client sql favori (adminer par ex)
- toutes les adresses qui ont pu être géocodées sont maintenant à jour dans Dolibarr

La même procédure s'applique aux tables llx_societe et llx_user