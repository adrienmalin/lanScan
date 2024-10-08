<?php include_once 'filter_inputs.php'; ?>
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
            pattern="[a-zA-Z0-9._\/ \-]+" value="<?= htmlspecialchars($targets); ?>" list="targetsList"
            title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24 <?=$_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
        </div>

        <div class="ui styled fluid accordion field">
          <div class="title"><i class="icon dropdown"></i>Spécification des cibles</div>
          <div class="content">
            <div class="field">
              <label class="inline field">
                <div class="ui checkbox">
                  <input type="checkbox" id="excludeCheckbox" onchange="excludeInput.disabled = !this.checked"/>
                  <label for="excludeCheckbox">Exclure les hôtes ou réseaux</label>
                </div>
              </label>
              <input type="text" id="excludeInput" name="--exclude" placeholder="Hôte/réseau" list="targetsList" disabled
                pattern="[a-zA-Z0-9._\/,\-]*" value=""
                title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24,<?=$_SERVER['SERVER_NAME']; ?>,10.0-255.0-255.1-254">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Découverte des hôtes</div>
          <div class="content">
            <div class="inline field">
              <div class="ui checkbox">
                <input type="checkbox" id="sPCheckbox" name="-sP"/>
                <label for="sPCheckbox">N'effectuer que la découverte des hôtes actifs</label>
              </div>
            </div>
            <div class="inline field">
              <div class="ui checkbox">
                <input type="checkbox" id="PECheckbox" name="-PE"/>
                <label for="PECheckbox">Considérer tous les hôtes comme actifs</label>
              </div>
            </div>
            <div class="field">
              <label class="inline field">
                <div class="ui checkbox">
                  <input type="checkbox" id="PSCheckbox" onchange="PSInput.disabled = !this.checked"/>
                  <label for="PSCheckbox">Ping TCP SYN</label>
                </div>
              </label>
              <input type="text" id="PSInput" name="-PS" placeholder="Ports" list="servicesList" disabled
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="80"
                title="Liste de ports ex: 22,23,25,80,113,1050,35000">
            </div>
            <div class="field">
              <label>
                <div class="ui checkbox">
                  <input type="checkbox" id="PACheckbox" onchange="PAInput.disabled = !this.checked"/>
                  <label for="PACheckbox">Ping TCP ACK</label>
                </div>
              </label>
              <input type="text" id="PAInput" name="-PA" placeholder="Ports" list="servicesList" disabled
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="80"
                title="Liste de ports ex: 22,23,25,80,113,1050,35000">
            </div>
            <div class="field">
              <label>
                <div class="ui checkbox">
                  <input type="checkbox" id="PUCheckbox" onchange="PUInput.disabled = !this.checked"/>
                  <label for="PUCheckbox">Ping UDP</label>
                </div>
              </label>
              <input type="text" id="PUInput" name="-PU" placeholder="Ports" list="servicesList" disabled
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="31338"
                title="Liste de ports ex: 22,23,25,80,113,1050,35000">
            </div>

            <div class="inline fields">
              <label>Ping ICMP</label>
              <div class="field">
                <div class="ui checkbox">
                  <input type="checkbox" id="PECheckbox" name="-PE"/>
                  <label for="PECheckbox">Echo request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui checkbox">
                  <input type="checkbox" id="PPCheckbox" name="-PP"/>
                  <label for="PPCheckbox">Timestamp request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui checkbox">
                  <input type="checkbox" id="PMCheckbox" name="-PM"/>
                  <label for="PMCheckbox">Mask request</label>
                </div>
              </div>
            </div>

            <div class="field">
              <div class="ui checkbox">
                <input type="checkbox" id="PRCheckbox" name="-PR"/>
                <label for="PRCheckbox">Ping ARP</label>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Techniques de scan</div>
          <div class="content">
            <div class="field">
              <label>
                <div class="ui checkbox">
                  <input type="checkbox" id="pCheckbox" onchange="pInput.disabled = !this.checked"/>
                  <label for="pCheckbox">Ne scanner que les ports</label>
                </div>
              </label>
              <input type="text" id="pInput" name="-p" placeholder="Ports" list="servicesList" disabled
                pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*"
                title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
            </div>
          </div>

        </div>

        <button type="submit" class="ui fluid teal submit button">Démarrer</button>
      </form>
      
      <datalist id='targetsList'>
        <option value="<?=$_SERVER['REMOTE_ADDR']; ?>"></option>
        <option value="192.168.1.0/24"></option>
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
const targetsWhitelist = Array.from(targetsList.options).map(option => option.value)
const servicesWhitelist = Array.from(servicesList.options).map(option => option.value)
const joinWithSpaces = tags => tags.map(tag => tag.value).join(' ')
const joinWithCommas = tags => tags.map(tag => tag.value).join(',')

$('.ui.accordion').accordion()

var targetsTagify = new Tagify(targetsInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithSpaces,
  whitelist: targetsWhitelist,
})

var excludeTagify = new Tagify(excludeInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: targetsWhitelist,
})
excludeCheckbox.onchange = (event) => {
  excludeInput.disabled = !excludeCheckbox.checked
  excludeTagify.setDisabled(!excludeCheckbox.checked)
}

var PSTagify = new Tagify(PSInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})
PSCheckbox.onchange = () => {
  PSInput.disabled = !PSCheckbox.checked
  PSTagify.setDisabled(!PSCheckbox.checked)
}

var PATagify = new Tagify(PAInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})
PACheckbox.onchange = () => {
  PAInput.disabled = !PACheckbox.checked
  PATagify.setDisabled(!PACheckbox.checked)
}

var PUTagify = new Tagify(PUInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})
PUCheckbox.onchange = () => {
  PUInput.disabled = !PUCheckbox.checked
  PUTagify.setDisabled(!PUCheckbox.checked)
}

var pTagify = new Tagify(pInput, {
  delimiters: " |,",
  originalInputValueFormat: joinWithCommas,
  whitelist: servicesWhitelist,
})
pCheckbox.onchange = () => {
  pInput.disabled = !pCheckbox.checked
  pTagify.setDisabled(!pCheckbox.checked)
}

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
