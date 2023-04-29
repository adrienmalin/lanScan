# lanScan

Scanne des hôtes spécifiées avec un fichier de configuration en YAML
et affiche le résultat dans une page web.

* Créer un fichier de configuration YAML dans un sous-dossier ./configs/ (voir l'exemple ci-dessous).
Il peut être généré en scannant un réseau (en notation CIDR) avec : `./discover XXX.XXX.XXX.XXX/XX`.
* Scanner avec le script `./scan_all` (utiliser une tâche cron !).
* Voir les résultats dans le navigateur web.

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

