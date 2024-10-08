<?php include_once 'common.php'; ?>
<!DOCTYPE html>
<html lang="fr">

  <head>
    <meta charset="utf-8" />
    <title>lanScan</title>
    <link rel="icon" href="favicon.ico" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.js"></script>
    <link rel="stylesheet" type="text/css"
      href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <nav class="ui inverted teal fixed menu">
      <a class="header item" href=".">
        lan
        <?php include 'logo.svg'; ?>can
      </a>
    </nav>

    <main class="ui main container">
      <form id="newScanForm" class="ui form" method="get" action="scan.php">
        <h1 class="header">Nouveau scan</h1>
        <!--<div class="field">
              <label for="nameInput">Nom</label>
              <input id="nameInput" type="text" name="name" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
                title='Nom de fichier valide (ne contenant pas les caractères &lt;&gt;:&quot;\/|@?)'
                value="<?= htmlspecialchars($name); ?>">
            </div>-->
        <div class="required field">
          <label for="targetsInput">Cibles</label>
          <input id="targetsInput" type="text" name="targets" placeholder="Cibles" required=""
            pattern="[a-zA-Z0-9._\/ \-]+" value="<?= htmlspecialchars($targets); ?>" list="targetsList" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24 <?=$_SERVER['SERVER_NAME']; ?>" />
        </div>

        <fieldset class="ui segment">
          <h3 class="ui header">Découverte des hôtes</h3>
          <div class="inline field">
            <div class="ui checkbox">
              <input type="checkbox" id="PnInput" name="Pn"/>
              <label for="PnInput">Tous les hôtes</label>
            </div>
          </div>
          <div class="field">
            <label>Ping TCP SYN</label>
            <input type="text" id="PSInput" name="PS" placeholder="Ports" list="servicesList" pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*"
              title="Liste de ports ex: 22,23,25,80,113,1050,35000">
          </div>
        </fieldset>

        <fieldset class="ui segment">
          <h3 class="ui header">Techniques de scan</h3>
          <div class="field">
            <label>Ne scanner que les ports</label>
            <input type="text" id="pInput" name="p" placeholder="Ports" list="servicesList" pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*"
              title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
          </div>
        </fieldset>

        <button type="submit" class="ui fluid large teal submit button">Démarrer</button>
      </form>
      
      <datalist id='targetsList'>
        <option value="<?=$_SERVER['REMOTE_ADDR']; ?>"></option>
        <option value="<?=$_SERVER['REMOTE_ADDR']; ?>/24"></option>
        <option value="<?=$_SERVER['SERVER_NAME']; ?>"></option>
      </datalist>
      <datalist id='servicesList'>
<?php
$nmap_services = file("$NMAP_DATADIR/nmap-services");
$services = [];
foreach ($nmap_services as $service) {
    if (strpos($service, '#') !== 0) {
        [$name, $port] = explode("\t", $service);
        $services[$name] = $port;
    }
}
foreach ($services as $name => $port) {
    echo "       <option value='$name'></option>\n";
}
?>
      </datalist>
    </main>
    <script>
      const hostPattern = /^[a-zA-Z\d._\/\-]+$/
      const portPattern = /^([\d-]+|[a-z\-]+)$/
      const protocolePortPattern = /^(([TU]:)?[\d\-]+|[a-z\-]+)$/

      const targetsWhitelist = Array.from(targetsList.options).map(option => option.value)
      const servicesWhitelist = Array.from(servicesList.options).map(option => option.value)

      new Tagify(targetsInput, {
        validate: (data) => hostPattern.test(data.value),
        delimiters: " ",
        originalInputValueFormat: tags => tags.map(tag => tag.value).join(' '),
        whitelist: targetsWhitelist,
      })

      new Tagify(PSInput, {
        validate: (data) => portPattern.test(data.value),
        originalInputValueFormat: tags => tags.map(tag => tag.value).join(','),
        whitelist: servicesWhitelist,
      })

      new Tagify(pInput, {
        validate: (data) => protocolePortPattern.test(data.value),
        originalInputValueFormat: tags => tags.map(tag => tag.value).join(','),
        whitelist: servicesWhitelist,
      })

      newScanForm.onsubmit = function (event) {
        if (this.checkValidity()) {
          newScanForm.classList.add("loading")
          return true
        } else {
          event.preventDefault()
          this.reportValidity()
        }
      }
    </script>
  </body>

</html>