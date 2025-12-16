# Website Batch Checker

Une application web PHP permettant de vérifier l'état de disponibilité de divers sites web regroupés en lots (batches).

## Fonctionnalités

- **Gestion des batches** : Créer, visualiser, et supprimer des lots de sites web
- **Gestion des sites web** : Ajouter, modifier, et supprimer des sites web dans les batches
- **Vérification de disponibilité** : Tester automatiquement la disponibilité des sites avec cURL
- **Journalisation** : Tous les événements sont enregistrés avec Monolog dans `logs/app.log`
- **Interface utilisateur** : Interface intuitive avec Bootstrap 5

## Technologies utilisées

- **PHP 7.4+** avec programmation orientée objet (POO)
- **Slim Framework 4** pour le routing et les contrôleurs
- **Monolog** pour la journalisation
- **cURL** pour tester la disponibilité des sites
- **Bootstrap 5** pour l'interface utilisateur
- **JSON** pour la persistance des données

## Installation

### Prérequis

- PHP 7.4 ou supérieur
- Composer
- Extension PHP cURL activée
- Extension PHP JSON activée

### Étapes d'installation

1. **Cloner le repository**
   ```bash
   git clone <url-du-repo>
   cd batchChecker
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Vérifier les permissions**
   ```bash
   # Assurez-vous que les répertoires logs/ et data/ sont accessibles en écriture
   chmod 777 logs/
   chmod 777 data/
   ```

## Configuration

### Serveur de développement PHP

Pour démarrer l'application avec le serveur de développement PHP :

```bash
cd public
php -S localhost:8000
```

Puis ouvrez votre navigateur à l'adresse : `http://localhost:8000`

### Apache

Si vous utilisez Apache, configurez le document root sur le dossier `public/` et activez `mod_rewrite`.

Exemple de configuration Apache :

```apache
<VirtualHost *:80>
    DocumentRoot "/chemin/vers/batchChecker/public"
    <Directory "/chemin/vers/batchChecker/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

Exemple de configuration Nginx :

```nginx
server {
    listen 80;
    server_name localhost;
    root /chemin/vers/batchChecker/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Utilisation

### 1. Créer un batch

- Cliquez sur le bouton "+ Nouveau batch" sur la page d'accueil
- Remplissez le nom et le type du batch
- Cliquez sur "Créer"

### 2. Ajouter des sites web

- Ouvrez un batch en cliquant sur "Ouvrir"
- Cliquez sur "+ Ajouter un site"
- Entrez l'URL du site web (doit inclure http:// ou https://)
- Cliquez sur "Enregistrer"

### 3. Vérifier les sites

- Dans la vue détaillée d'un batch, cliquez sur "Vérifier tous"
- L'application testera tous les sites et mettra à jour leur statut
- Les résultats sont enregistrés dans `logs/app.log`

### 4. Consulter les logs

Les logs sont disponibles dans le fichier `logs/app.log` avec les informations suivantes :

- **INFO** : Création/suppression de batch ou site, site UP
- **WARNING** : Site DOWN
- **ERROR** : URL invalide, erreur réseau ou timeout

## Structure du projet

```
batchChecker/
├── data/               # Stockage JSON des batches
├── logs/               # Fichiers de logs
├── public/             # Point d'entrée web
│   └── index.php       # Entry point de l'application
├── src/
│   ├── Controllers/    # Contrôleurs
│   ├── Models/         # Modèles de données
│   ├── Services/       # Services (Repository, Logger, Checker)
│   └── Views/          # Templates PHP
├── vendor/             # Dépendances Composer
├── composer.json       # Configuration Composer
└── README.md           # Ce fichier
```

## Vérification de disponibilité

L'application utilise cURL pour tester les sites web :

- **Codes HTTP 200-399** : Le site est considéré comme "UP" ✅
- **Autres codes ou timeout** : Le site est considéré comme "DOWN" ❌
- **Erreur réseau** : Le site est considéré comme "DOWN" et l'erreur est loggée

## Logs

Exemples de logs générés :

```
[2024-01-01 12:00:00] batch-checker.INFO: Batch created {"id":1,"name":"Sites API"}
[2024-01-01 12:01:00] batch-checker.INFO: Website added to batch {"batch_id":1,"website_id":1,"url":"https://example.com"}
[2024-01-01 12:02:00] batch-checker.INFO: Website is UP {"url":"https://example.com","http_code":200}
[2024-01-01 12:03:00] batch-checker.WARNING: Website is DOWN {"url":"https://down-site.com","http_code":404}
[2024-01-01 12:04:00] batch-checker.ERROR: Invalid URL {"url":"not-a-valid-url"}
```

## Licence

MIT

## Auteur

steve.ndmbm@eduge.ch
