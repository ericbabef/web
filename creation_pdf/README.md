# Script PHP pour créer simplement des PDF avec FPDF

Base de données : PostgreSql - Géodatabases SDE

L'objectif est d'interroger une couche SIG de référence, et de récupérer les couches qui l'intersectent et les pièces jointes (les images).

Nous avons un résultat avec des tableaux multilignes, une image correspondant à la pièce jointe de la couche de référence et une vue cartographique.

La configuration du PDF se fait très facilement : 

- un fichier XML : `public/config-layer/configLayer.xml`
- la longueur des cellules est exprimée en pourcentage
- l'url de votre wms (variable $path_wms) sans les paramètres width, height et bbox : `model/model.php`
- l'accès à votre base de données : `model/connect.php`

[Tester le PDF](http://ericbabef.alwaysdata.net/webmapping/pdf-dynamic/create_pdf.php?ref=1)