# lanScan

Scanne des hôtes avec `nmap`
et affiche le résultat dans une page web.

* Créer un fichier de configuration YAML dans un sous-dossier ./configs/ (voir l'exemple ci-dessous).
Il peut être généré en scannant un réseau avec : `./discover <CIDR>`.
* Scanner avec le script `./scan_all.sh` (utiliser une tâche cron !).
* Voir les résultats en ouvrant `.\index.php` dans le navigateur web.

## Exemple 
```yaml
---
site: Nom du site

hosts:
  - name: Nom du premier groupe
    host:
      - address: host1.local
        services: [ssh, http]
      - address: 192.168.1.100
        services: [ftp, https, 5432]
  - name: Nom du 2ème groupe
    host:
      - adress: host3.local
        services: [ssh, ftp, 8006]
```

