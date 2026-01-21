Il s'agit d'une boutique en ligne de vente de burger faites avec Codeigniter4 et utilisant Shield

## Déploiement

Juste après avoir **clone** le projet, exécuter dans le terminal :

```bash
cd cheminDuProjet
cd images_php_sqlite
./scripts/create.sh
./scripts/push.sh
./scripts/terminal.sh
cd CI4
apt update && apt install -y libpng-dev libjpeg-dev libfreetype6-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install gd mbstring
service apache2 restart
composer require mpdf/mpdf
composer dump-autoload
composer update
service apache2 restart
```

pour le fichier .env :

```bash
CI_ENVIRONMENT = development #laisser sur development pour voir les erreurs si le déploiement échoue.
app.baseURL = 'http://localhost:8080/CI4/public/' #peut etre adapté selon vos besoin (parfois le port peut poser probleme et doit etre supprimer)
database.default.database = "ci4.db" #le nom de la DB
database.default.DBDriver = "SQLite3" #le modele de base de donnee
```

pour la base de donnée, faire apres terminal.sh, dans CI4:

```bash
php spark migrate --all
php spark migrate #uniquement si migrate --all ne crée pas les tables des fichiers de migration (ce qui arrive si l'on utilise laragon sous windows par exemple)
php spark key:generate #pour generer une cle d'encription
php spark db:seed DatabaseSeeder #pour remplir les tables
cd ..
chown -R www-data:www-data CI4/writable #nécessaire pour la generation de facture et l'acces à la BDD
```

si l'envoie de mail est possible, décommenter la ligne 192 de CI4/app/Controllers/CommandeController.php avant d'appeler push.sh

## Une fois sur le site :

Les logs admins sont :

mail : admin@example.com

mdp : ./admin