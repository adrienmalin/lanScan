# lanScan

Scanne le réseau avec `nmap` et affiche les résultats dans une page web.

## Configuration

On peut personnaliser les options prédéfinies pour les scans de réseau ou d'hôte dans le fichier `config.php` :
```php
$presets = [
    "lan" => [
        '-PS'          => 'microsoft-ds',
        '-F'           => true,
        '-T5'          => true,
        '--stylesheet' => "$BASEDIR/lanScan.xsl",
        'refreshPeriod' => 60,
        'sudo'          => false,
    ],
    "host" => [
        '-Pn'           => true,
        '-F'            => true,
        '-sV'           => true,
        '-T5'           => true,
        '--stylesheet'  => "$BASEDIR/servicesDetails.xsl",
        'refreshPeriod' => 60,
        'sudo'          => false,
    ],
];
```

## Accès root

Certaines options nécessitent l'accès root.
Pour donner les droits à lanScan sous Linux, installer `sudo` au besoin, puis créer le fichier `/etc/sudoers.d/lanScan` avec le contenu
(en remplaçant `www-data` par l'utilisateur du service web) :
```
www-data ALL = NOPASSWD: /usr/bin/nmap
````
