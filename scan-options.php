<?php
include_once 'config.php';
include_once 'filter_inputs.php';
?>
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
        lan<?php include 'logo.svg'; ?>can
      </a>
    </nav>

    <main class="ui main container">
      <h1 class="header">Scanner un réseau avec Nmap</h1>

      <form id="newScanForm" class="ui form" method="get" action="scan.php">

        <!--<div class="field">
              <label for="nameInput">Nom</label>
              <input id="nameInput" type="text" name="name" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
                title='Nom de fichier valide (ne contenant pas les caractères &lt;&gt;:&quot;\/|@?)'
                value="<?= htmlspecialchars($name); ?>">
            </div>-->
        <div class="required field">
          <label for="targetsInput">Cibles</label>
          <input id="targetsInput" type="text" name="targets" placeholder="Cibles" required
            pattern="[a-zA-Z0-9._\/ \-]+" value="<?= $targets; ?>" list="targetsList"
            title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24 <?=$_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
        </div>

        <div class="ui styled fluid accordion field">
          <div class="title"><i class="icon dropdown"></i>Spécification des cibles</div>
          <div class="content">
            <div class="field">
              <label for="excludeInput">Exclure les hôtes ou réseaux</label>
              <input type="text" id="excludeInput" name="-exclude" placeholder="Hôte/réseau" list="targetsList"
                pattern="[a-zA-Z0-9._\/,\-]*" value="<?=$options['-exclude']?? "" ?>"
                title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24,<?=$_SERVER['SERVER_NAME']; ?>,10.0-255.0-255.1-254">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Découverte des hôtes actifs</div>
          <div class="content">
            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="sPCheckbox" name="sP" <?=$options['sP']?? false? 'checked' : ''; ?>/>
                <label for="sPCheckbox">N'effectuer que l'étape de découverte des hôtes actifs</label>
              </div>
            </div>

            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="PnCheckbox" name="Pn" <?=$options['Pn']?? false? 'checked' : ''; ?>/>
                <label for="PnCheckbox">Considérer tous les hôtes comme actifs (saute la découverte des hôtes)</label>
              </div>
            </div>

            <div class="fields">
              <div class="field">
                <label for="PSInput">Ping TCP SYN</label>
                <input type="text" id="PSInput" name="PS" placeholder="Ports" list="servicesList"
                  pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?=$options['PS']?? "" ?>"
                  title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
              </div>
              <div class="field">
                <label for="PAInput">Ping TCP ACK</label>
                <input type="text" id="PAInput" name="PA" placeholder="Ports" list="servicesList"
                  pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?=$options['PA']?? "" ?>"
                  title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
              </div>
              <div class="field">
                <label for="PUInput">Ping UDP</label>
                <input type="text" id="PUInput" name="PU" placeholder="Ports" list="servicesList"
                  pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?=$options['PU']?? "" ?>"
                  title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
              </div>
            </div>

            <div class="field">
              <label>Ping ICMP</label>
              <div class="inline fields">
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="PECheckbox" name="PE" <?=$options['PE']?? false? 'checked' : ''; ?>/>
                    <label for="PECheckbox">Echo request</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="PPCheckbox" name="PP" <?=$options['PP']?? false? 'checked' : ''; ?>/>
                    <label for="PPCheckbox">Timestamp request</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="PMCheckbox" name="PM" <?=$options['PM']?? false? 'checked' : ''; ?>/>
                    <label for="PMCheckbox">Mask request</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="PRCheckbox" name="PR" <?=$options['PR']?? false? 'checked' : ''; ?>/>
                <label for="PRCheckbox">Ping ARP</label>
              </div>
            </div>

            <div class="field">
              <label for="P0Input">Ping IP Protocol</label>
              <input type="text" id="P0Input" name="P0" placeholder="Ports"
                pattern="[0-9\-]+" value="<?=$options['P0']?? "" ?>"
                title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Techniques de scan</div>
          <div class="content">
            <div class="field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="FCheckbox" name="F" <?=$options['F']?? false? 'checked' : ''; ?>/>
                <label for="FCheckbox">Scanner que les ports connus</label>
              </div>
            </div>
            
            <div class="field">
              <label for="pInput">Scanner que les ports</label>
              <input type="text" id="pInput" name="p" placeholder="Ports" list="servicesList"
                pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*" value="<?=$options['p']?? "" ?>"
                title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
            </div>
            
            <div class="field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="rCheckbox" name="r" <?=$options['r']?? false? 'checked' : ''; ?>/>
                <label for="rCheckbox">Ne pas mélanger les ports</label>
              </div>
            </div>
          </div>

        </div>

        <button type="submit" class="ui teal submit button">Démarrer</button>
      </form>
      
      <datalist id='targetsList'>
        <option value="<?=$_SERVER['REMOTE_ADDR']; ?>"></option>
        <option value="192.168.1.0/24"></option>
        <option value="<?=$_SERVER['SERVER_NAME']; ?>"></option>
<?php
if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}
foreach (scandir($SCANS_DIR) as $scan) {
    if ('.xml' == substr($scan, -4)) {
        $targets = str_replace('!', '/', substr_replace($scan, '', -4));
        echo "        <option value='$targets'></option>\n";
    }
}
?>
      </datalist>
      <datalist id='servicesList'>
<?php
$nmap_services = file("$NMAP_DATADIR/nmap-services");
$services = [];
foreach ($nmap_services as $service) {
    if (0 !== strpos($service, '#')) {
        [$name, $port] = explode("\t", $service);
        $services[$name] = explode("/", $port);
    }
}
foreach ($services as $name => [$portid, $protocol]) {
    echo "       <option value='$name'></option>\n";
}
?>
      </datalist>
    </main>
    <script>
const targetsWhitelist = Array.from(targetsList.options).map(option => option.value)
const servicesWhitelist = Array.from(servicesList.options).map(option => option.value)
const joinWithSpaces = tags => tags.map(tag => tag.value).join(' ')
const joinWithCommas = tags => tags.map(tag => tag.value).join(',')

$('.ui.accordion').accordion()

new Tagify(targetsInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithSpaces,
  whitelist: targetsWhitelist,
})

new Tagify(excludeInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: targetsWhitelist,
})

new Tagify(PSInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})

new Tagify(PAInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})

new Tagify(PUInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})

new Tagify(P0Input, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas
})

new Tagify(pInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
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
