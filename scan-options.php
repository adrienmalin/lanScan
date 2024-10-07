<?php
include_once 'common.php';

if (!$targets) {
    $targets = $_SERVER['SERVER_NAME'].' '.$_SERVER['REMOTE_ADDR'];
}
?>
<!DOCTYPE html>
<html lang="fr">

  <head>
    <meta charset="utf-8" />
    <title>lanScan</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <nav class="ui inverted teal fixed menu">
      <a class="header item" href=".">
        lan<?php include 'logo.svg'; ?>can
      </a>
    </nav>

    <main class="ui main container">
    <div class="ui segment">
        <form id="newScanForm" class="ui form" method="get" action="scan.php">
          <h1 class="header">Nouveau scan</h1>
            <div class="field">
              <label for="nameInput">Nom</label>
              <input id="nameInput" type="text" name="name" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
                title='Nom de fichier valide (ne contenant pas les caractères &lt;&gt;:&quot;\/|@?)'
                value="<?= htmlspecialchars($name); ?>">
            </div>
            <div class="field">
              <label for="targetsInput">Cibles</label>
              <input id="targetsInput" type="text" name="targets" placeholder="scanme.nmap.org 192.168.0.0/24" required=""
                title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
    Exemple: scanme.nmap.org microsoft.com/24 192.168.0.1 10.0-255.0-255.1-254"
                pattern="[a-zA-Z0-9._\/ \-]+" value="<?= htmlspecialchars($targets); ?>" />
            </div>
            <button id="newScanSubmitButton" type="submit" class="ui fluid large teal submit button">Démarrer</button>
        </form>
      </div>
    </main>
      <script>
        tagify = new Tagify(targetsInput, {
          pattern: /[a-zA-Z\d.-_/]+/,
          delimiters: " ",
          originalInputValueFormat: tags => tags.map(tag => tag.value).join(' ')
        })

        newScanForm.onsubmit = function(event) {
          if (this.checkValidity()) return true

          event.preventDefault()
          this.reportValidity()
          newScanSubmitButton.innerHTML = "<div class='ui active inline inverted loader'></div>"
        }
      </script>
  </body>

</html>