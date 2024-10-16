# lanScan

Scanne le réseau avec `nmap` et affiche les résultats dans une page web.

## Configuration

On peut personnaliser les options de `nmap` utilisées par défaut pour les scans de réseau ou d'hôte dans le fichier `config.php` :
```php
$LANSCAN_OPTIONS = [
    'PS'         => 'microsoft-ds',
    'F'          => true,
    'T5'         => true,
    'stylesheet' => "$BASEDIR/lanScan.xsl"
];

$HOSTSCAN_OPTIONS = [
    'Pn'         => true,
    'F'          => true,
    'sV'         => true,
    'stylesheet' => "$BASEDIR/hostScan.xsl"
];
```

## Accès root

Certaines options nécessitent l'accès root.
Pour donner les droits à lanScan sous Linux, installer `sudo` au besoin, puis créer le fichier `/etc/sudoers.d/lanScan` avec le contenu
(en remplaçant `www-data` par l'utilisateur du service web) :
```
www-data ALL = NOPASSWD: /usr/bin/nmap
````
