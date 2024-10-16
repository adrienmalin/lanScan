# lanScan

Scanne le réseau avec `nmap` et affiche les résultats dans une page web.

## Accès root

Certaines options nécessitent l'accès root.
Pour donner les droits à lanScan sous Linux, installer `sudo` au besoin, puis créer le fichier `/etc/sudoers.d/lanScan` avec le contenu
(en remplaçant `www-data` par l'utilisateur du service web) :
```
www-data ALL = NOPASSWD: /usr/bin/nmap
````
et modifier le fichier `config.php` avec :
```php
$use_sudo = true;
```
