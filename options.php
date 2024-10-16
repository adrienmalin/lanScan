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
    <a class="ui teal button item" href=".">
      lan<?php include 'logo.svg'; ?>can
    </a>
    <div class="right menu">
      <div class="item">
        <a class="ui teal icon button" href="https://nmap.org/man/fr/index.html" target="_blank">
          <i class="question circle icon"></i>
        </a>
      </div>
    </div>
  </nav>

  <main class="ui main container">

<?php if(isset($errorMessage)) { ?>
    <div class="ui negative message">
      <i class="close icon"></i>
      <div class="header">Erreur</div>
      <p><?=$errorMessage?></p>
    </div>
<?php } ?>

    <h1 class="header">Scanner un <?=$host? "hôte" : "réseau" ?></h1>

    <form id="newScanForm" class="ui form" method="get" action="scan.php">
      <div class="required field">
        <label for="targetsInput" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254">Cibles</label>
        <input id="targetsInput" type="text" name="targets" placeholder="Cibles" required
          pattern="[a-zA-Z0-9._\/ \-]+" value="<?= $targets; ?>" list="targetsList"
          title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
      </div>

      <div class="ui styled fluid accordion field">
        <div class="title">
          <i class="icon dropdown"></i>
          Spécification des cibles
        </div>
        <div class="content">
          <div class="field" title="--exclude">
            <label for="excludeInput">Exclure les hôtes ou réseaux</label>
            <input type="text" id="excludeInput" name="exclude" placeholder="Hôte/réseau" list="targetsList"
              pattern="[a-zA-Z0-9._\/,\-]*" value="<?= $inputs['exclude'] ?? "" ?>">
          </div>
          
          <div class="field" title="-iR">
            <label for="iRInput">Nombre de cibles au hasard</label>
            <input type="number" min="0" id="iRInput" name="iR" placeholder="Nombre de cibles"
              value="<?= $inputs['iR'] ?? "" ?>">
          </div>
        </div>

        <div class="title">
          <i class="icon dropdown"></i>
          Découverte des hôtes actifs
        </div>
        <div class="content">
          <div class="inline field" title="-Pn">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="PnCheckbox" name="Pn" <?= $inputs['Pn'] ?? false ? 'checked' : ''; ?> />
              <label for="PnCheckbox">Sauter cette étape (considérer tous les hôtes comme actifs)</label>
            </div>
          </div>

          <div class="field" title="-PS">
            <label for="PSInput">TCP SYN</label>
            <input type="text" id="PSInput" name="PS" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $inputs['PS'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field" title="-PA">
            <label for="PAInput">TCP ACK</label>
            <input type="text" id="PAInput" name="PA" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $inputs['PA'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field" title="-PU">
            <label for="PUInput">UDP</label>
            <input type="text" id="PUInput" name="PU" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $inputs['PU'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label>ICMP</label>
            <div class="inline fields">
              <div class="field" title="-PE">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PECheckbox" name="PE" <?= $inputs['PE'] ?? false ? 'checked' : ''; ?> />
                  <label for="PECheckbox">Echo request</label>
                </div>
              </div>
              <div class="field" title="-PP">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PPCheckbox" name="PP" <?= $inputs['PP'] ?? false ? 'checked' : ''; ?> />
                  <label for="PPCheckbox">Timestamp request</label>
                </div>
              </div>
              <div class="field" title="-PM">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PMCheckbox" name="PM" <?= $inputs['PM'] ?? false ? 'checked' : ''; ?> />
                  <label for="PMCheckbox">Mask request</label>
                </div>
              </div>
            </div>
          </div>

          <div class="field" title="-PO">
            <label for="POInput" title="PO">Protocole IP (par type)</label>
            <input type="text" id="POInput" name="PO" placeholder="Protocole"
              pattern="[0-9,\-]+" value="<?= $inputs['PO'] ?? "" ?>"
              title="[num de protocole]">
          </div>

          <div class="fields">
            <div class="inline field" title="-PR">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="PRCheckbox" name="PR" <?= $inputs['PR'] ?? false ? 'checked' : ''; ?> />
                <label for="PRCheckbox">Ping ARP</label>
              </div>
            </div>
            <div class="inline field" title="--send-ip">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="sendIPCheckbox" name="send-ip" <?= $inputs['send-ip'] ?? false ? 'checked' : ''; ?> />
                <label for="sendIPCheckbox">Pas de scan ARP</label>
              </div>
            </div>
          </div>

          <div class="fields">
            <div class="inline field" title="-n">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="nCheckbox" name="n" <?= $inputs['n'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox">Ne jamais résoudre les noms DNS</label>
              </div>
            </div>
            <div class="inline field" title="-R">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="RCheckbox" name="R" <?= $inputs['R'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox">Toujours résoudre les noms DNS<br />(par défault seuls les hôtes actifs sont résolus)</label>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="dnsServersInput" title="--dns-servers">Utiliser les serveurs DNS</label>
            <input type="text" id="dnsServersInput" name="dns-servers" placeholder="serveur"
              pattern="[a-zA-Z0-9._,\-]*" value="<?= $inputs['dns-servers'] ?? "" ?>"
              title="serv1[,serv2],...">
          </div>
        </div>

          <div class="title">
            <i class="icon dropdown"></i>
            Techniques de scan de ports
          </div>
          <div class="content">
            <div class="field">
              <div class="fields">
                <div class="field" title="-sS">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sSCheckbox" name="sS" <?= $inputs['sS'] ?? false ? 'checked' : ''; ?> />
                    <label for="sSCheckbox">TCP SYN</label>
                  </div>
                </div>
                <div class="field" title="-sT">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sTCheckbox" name="sT" <?= $inputs['sT'] ?? false ? 'checked' : ''; ?> />
                    <label for="sTCheckbox">TCP Connect()</label>
                  </div>
                </div>
                <div class="field" title="-sA">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sACheckbox" name="sA" <?= $inputs['sA'] ?? false ? 'checked' : ''; ?> />
                    <label for="sACheckbox">TCP ACK</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field" title="-sW">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sWCheckbox" name="sW" <?= $inputs['sW'] ?? false ? 'checked' : ''; ?> />
                    <label for="sWCheckbox">Fenêtre TCP</label>
                  </div>
                </div>
                <div class="field" title="-sM">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sMCheckbox" name="sM" <?= $inputs['sM'] ?? false ? 'checked' : ''; ?> />
                    <label for="sMCheckbox">Maimon</label>
                  </div>
                </div>
                <div class="field" title="-sN">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sNCheckbox" name="sN" <?= $inputs['sN'] ?? false ? 'checked' : ''; ?> />
                    <label for="sNCheckbox">TCP Null</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field" title="-sF">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sFCheckbox" name="sF" <?= $inputs['sF'] ?? false ? 'checked' : ''; ?> />
                    <label for="sFCheckbox">TCP FIN</label>
                  </div>
                </div>
                <div class="field" title="-sX">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sXCheckbox" name="sX" <?= $inputs['sX'] ?? false ? 'checked' : ''; ?> />
                    <label for="sXCheckbox">Sapin de Noël</label>
                  </div>
                </div>
                <div class="field" title="-sU">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sUCheckbox" name="sU" <?= $inputs['sU'] ?? false ? 'checked' : ''; ?> />
                    <label for="sUCheckbox">UDP</label>
                  </div>
                </div>
              </div>

              <div class="field" title="-scanflags">
                <label for="scanflagsInput">Scan TCP personnalisé</label>
                <input type="text" id="scanflagsInput" name="scanflags" placeholder="Drapeaux TCP" list="flagsList"
                  pattern="(URG|ACK|PSH|RST|SYN|FIN|,)+|[1-9]?[0-9]|[1-2][0-9][0-9]" value="<?= $inputs['scanflags'] ?? "" ?>"
                  title="Mélanger simplement les drapeaux URG, ACK, PSH, RST, SYN et FIN.">
              </div>

              <div class="field" title="-sI">
                <label for="sIInput">Hôte zombie</label>
                <input type="text" id="sIInput" name="p" placeholder="zombie host[:probeport]"
                  pattern="[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $inputs['sI'] ?? "" ?>"
                  title="zombie host[:probeport]">
              </div>

              <div class="field" title="-b">
                <label for="bInput">Rebond FTP</label>
                <input type="text" id="bInput" name="p" placeholder="[<username>[:<password>]@]<server>[:<port>]"
                  pattern="([a-zA-Z0-9._\-]+(:.+)?@)?[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $inputs['b'] ?? "" ?>"
                  title="[<username>[:<password>]@]<server>[:<port>]">
              </div>

              <div class="field" title="-sO">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="sUCheckbox" name="sU" <?= $inputs['sU'] ?? false ? 'checked' : ''; ?> />
                  <label for="sUCheckbox">Scan des protocoles supportés par la couche IP</label>
                </div>
              </div>
            </div>
          </div>

          <div class="title">
            <i class="icon dropdown"></i>
            Spécifications des ports et ordre du scan
          </div>
          <div class="content">
          <div class="inline field" title="-sP">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="sPCheckbox" name="sP" <?= $inputs['sP'] ?? false ? 'checked' : ''; ?> />
              <label for="sPCheckbox">Sauter cette étape</label>
            </div>
          </div>

          <div class="inline field" title="-F">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="FCheckbox" name="F" <?= $inputs['F'] ?? false ? 'checked' : ''; ?>
                onchange="pInput.disabled = FCheckbox.checked" />
              <label for="FCheckbox">Scanner les ports connus</label>
            </div>
          </div>

          <div class="field" title="-p">
            <label for="pInput">Scanner les ports</label>
            <input type="text" id="pInput" name="p" placeholder="Ports" list="servicesList" <?= $inputs['F'] ?? false ? 'disabled' : ''; ?>
              pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*" value="<?= $inputs['p'] ?? "" ?>"
              title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
          </div>

          <div class="inline field" title="-r">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="rCheckbox" name="r" <?= $inputs['r'] ?? false ? 'checked' : ''; ?> />
              <label for="rCheckbox">Ne pas mélanger les ports</label>
            </div>
          </div>
        </div>

        <div class="title">
          <i class="icon dropdown"></i>
          Divers
        </div>
        <div class="content">
          <div class="field" title="--stylesheet">
            <label for="stylesheetSelect">Feuille de style</label>
            <select class="ui dropdown" id="stylesheetSelect" name="stylesheet" value="<?= $inputs["stylesheet"] ?? ""?>">
<?php
foreach (scandir('.') as $filename) {
  if (substr($filename, -4) === '.xsl') {
    $name = substr($filename, 0, -4);
    $URL = htmlentities("$BASEDIR/$filename", ENT_QUOTES);
    if (isset($inputs["stylesheet"]) && $URL == $inputs["stylesheet"]) {
      echo "              <option value='$URL' selected>$name</option>\n";
    } else {
      echo "              <option value='$URL'>$name</option>\n";
    }
  }
}
?>
            </select>
          </div>

          <div class="field">
            <label for="compareWithSelect">Comparer avec un précédent scan</label>
            <select class="ui dropdown" id="compareWithSelect" name="compareWith" value="<?= $compareWith ?>">
              <option value="">Précédent scan</option>
<?php
if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
foreach (scandir($SCANSDIR) as $filename) {
  if (substr($filename, -4) === '.xml') {
    $name = substr($filename, 0, -4);
    $URL = htmlentities("$BASEDIR/$SCANSDIR/$filename", ENT_QUOTES);
    if ($URL == $compareWith) {
      echo "              <option value='$URL' selected>$name</option>\n";
    } else {
      echo "              <option value='$URL'>$name</option>\n";
    }
  }
}
?>
            </select>
          </div>
          
          <div class="field">
            <label for="refreshPeriodInput">Rafraîchir toutes les</label>
            <div class="ui right labeled input">
              <input type="number" min="0" id="refreshPeriodInput" name="refreshPeriod" placeholder="Période"
                value="<?= $refreshPeriod ?? "" ?>">
              <div class="ui label">secondes</div>
            </div>
          </div>
        </div>
      </div>

      <div class="field">
        <label for="saveAsInput">Enregistrer sous le nom</label>
        <input id="saveAsInput" type="text" name="saveAs" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
          title="Caractères interdits :  &lt;&gt;:&quot;\/|@?"
          value="<?= htmlentities($saveAs, ENT_QUOTES); ?>">
      </div>

      <button type="submit" class="ui teal submit button">Démarrer</button>
    </form>
  </main>
  <footer class="ui footer segment">
    lanScan est basé sur <a href="https://nmap.org/" target="_blank">Nmap</a>
  </footer>

  <datalist id='targetsList'>
    <option value="<?= $_SERVER['REMOTE_ADDR']; ?>/24"></option>
    <option value="<?= $_SERVER['SERVER_NAME']; ?>"></option>
  </datalist>
  <datalist id='servicesList'>
    <?php
$nmap_services = file("$DATADIR/nmap-services");
$services = [];
foreach ($nmap_services as $service) {
  if (0 !== strpos($service, '#')) {
    [$name, $port] = explode("\t", $service);
    $services[$name] = explode("/", $port);
  }
}
foreach ($services as $name => [$portid, $protocol]) {
  echo "       <option value='$name'>$portid</option>\n";
}
    ?>
  </datalist>
  <datalist id="flagsList">
    <option value="URG"></option>
    <option value="ACK"></option>
    <option value="PSH"></option>
    <option value="RST"></option>
    <option value="SYN"></option>
    <option value="FIN"></option>
  </datalist>
  
  <script>
    class TagsInput extends Tagify {
      constructor(input, delim = ",") {
        super(input, {
          delimiters: " |,",
          originalInputValueFormat: tags => tags.map(tag => tag.value).join(delim),
        })
        if (input.list) this.whitelist = Array.from(input.list.options).map(option => option.value)
      }
    }

    $(".ui.accordion").accordion()

    $("#stylesheetSelect").dropdown()
    $("#compareWithSelect").dropdown({
      clearable: true
    })

    new TagsInput(targetsInput, " ")
    new TagsInput(excludeInput)
    new TagsInput(PSInput)
    new TagsInput(PAInput)
    new TagsInput(PUInput)
    new TagsInput(POInput)
    var pTagsInput = new TagsInput(pInput)
    new TagsInput(dnsServersInput)
    FCheckbox.onchange = () => {
      pInput.disabled = FCheckbox.checked
      pTagsInput.setDisabled(FCheckbox.checked)
    }
    new TagsInput(scanflagsInput)

    newScanForm.onsubmit = function(event) {
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
