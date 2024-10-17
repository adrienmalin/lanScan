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

    <h1 class="header">Scanner un <?=$preset == "host"? "hôte" : "réseau" ?></h1>

    <form id="newScanForm" class="ui form" method="get" action="scan.php">
      <div class="field">
        <label for="targetsInput" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254">Cibles</label>
        <input id="targetsInput" type="text" name="targets" placeholder="Cibles"
          pattern="[a-zA-Z0-9._\/ \-]+" value="<?= $targets; ?>" list="targetsList"
          title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
      </div>

      <div class="ui styled fluid accordion field">
        <div class="title"><i class="icon dropdown"></i>Spécification des cibles</div>
        <div class="content">
          <div class="field">
            <label for="excludeInput" title="--exclude">Exclure les hôtes ou réseaux</label>
            <input type="text" id="excludeInput" name="--exclude" placeholder="Hôte/réseau" list="targetsList"
              pattern="[a-zA-Z0-9._\/,\-]*" value="<?= $options['--exclude'] ?? "" ?>">
          </div>
          
          <div class="field">
            <label for="iRInput" title="-iR">Nombre de cibles au hasard</label>
            <input type="number" min="0" id="iRInput" name="-iR" placeholder="Nombre de cibles"
              value="<?= $options['-iR'] ?? "" ?>">
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Découverte des hôtes actifs</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="PnCheckbox" name="-Pn" <?= $options['-Pn'] ?? false ? 'checked' : ''; ?> />
              <label for="PnCheckbox" title="-Pn">Sauter cette étape (considérer tous les hôtes comme actifs)</label>
            </div>
          </div>

          <div class="field">
            <label for="PSInput" title="-PS">TCP SYN</label>
            <input type="text" id="PSInput" name="-PS" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PS'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label for="PAInput" title="-PA">TCP ACK</label>
            <input type="text" id="PAInput" name="-PA" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PA'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label for="PUInput" title="-PU">UDP</label>
            <input type="text" id="PUInput" name="-PU" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PU'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label>ICMP</label>
            <div class="inline fields">
              <div class="field">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PECheckbox" name="-PE" <?= $options['-PE'] ?? false ? 'checked' : ''; ?> />
                  <label for="PECheckbox" title="-PE">Echo request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PPCheckbox" name="-PP" <?= $options['-PP'] ?? false ? 'checked' : ''; ?> />
                  <label for="PPCheckbox" title="-PP">Timestamp request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="PMCheckbox" name="-PM" <?= $options['-PM'] ?? false ? 'checked' : ''; ?> />
                  <label for="PMCheckbox" title="-PM">Mask request</label>
                </div>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="POInput" title="-PO">Protocole IP (par type)</label>
            <input type="text" id="POInput" name="-PO" placeholder="Protocole"
              pattern="[0-9,\-]+" value="<?= $options['-PO'] ?? "" ?>"
              title="[num de protocole]">
          </div>

          <div class="fields">
            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="PRCheckbox" name="-PR" <?= $options['-PR'] ?? false ? 'checked' : ''; ?> />
                <label for="PRCheckbox" title="-PR">Ping ARP</label>
              </div>
            </div>
            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="sendIPCheckbox" name="--send-ip" <?= $options['--send-ip'] ?? false ? 'checked' : ''; ?> />
                <label for="sendIPCheckbox" title="--send-ip">Pas de scan ARP</label>
              </div>
            </div>
          </div>

          <div class="fields">
            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="nCheckbox" name="-n" <?= $options['-n'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox" title="-n">Ne jamais résoudre les noms DNS</label>
              </div>
            </div>
            <div class="inline field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="RCheckbox" name="-R" <?= $options['-R'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox" title="-R">Toujours résoudre les noms DNS<br />(par défault seuls les hôtes actifs sont résolus)</label>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="dnsServersInput" title="--dns-servers">Utiliser les serveurs DNS</label>
            <input type="text" id="dnsServersInput" name="--dns-servers" placeholder="serveur"
              pattern="[a-zA-Z0-9._,\-]*" value="<?= $options['--dns-servers'] ?? "" ?>"
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
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sSCheckbox" name="-sS" <?= $options['-sS'] ?? false ? 'checked' : ''; ?> />
                    <label for="sSCheckbox" title="-sS">TCP SYN</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sTCheckbox" name="-sT" <?= $options['-sT'] ?? false ? 'checked' : ''; ?> />
                    <label for="sTCheckbox" title="-sT">TCP Connect()</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sACheckbox" name="-sA" <?= $options['-sA'] ?? false ? 'checked' : ''; ?> />
                    <label for="sACheckbox" title="-sA">TCP ACK</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sWCheckbox" name="-sW" <?= $options['-sW'] ?? false ? 'checked' : ''; ?> />
                    <label for="sWCheckbox" title="-sW">Fenêtre TCP</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sMCheckbox" name="-sM" <?= $options['-sM'] ?? false ? 'checked' : ''; ?> />
                    <label for="sMCheckbox" title="-sM">Maimon</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sNCheckbox" name="-sN" <?= $options['-sN'] ?? false ? 'checked' : ''; ?> />
                    <label for="sNCheckbox" title="-sN">TCP Null</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sFCheckbox" name="-sF" <?= $options['-sF'] ?? false ? 'checked' : ''; ?> />
                    <label for="sFCheckbox" title="-sF">TCP FIN</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sXCheckbox" name="-sX" <?= $options['-sX'] ?? false ? 'checked' : ''; ?> />
                    <label for="sXCheckbox" title="-sX">Sapin de Noël</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input type="checkbox" id="sUCheckbox" name="-sU" <?= $options['-sU'] ?? false ? 'checked' : ''; ?> />
                    <label for="sUCheckbox" title="-sU">UDP</label>
                  </div>
                </div>
              </div>

              <div class="field">
                <label for="scanflagsInput" title="--scanflags">Scan TCP personnalisé</label>
                <input type="text" id="scanflagsInput" name="--scanflags" placeholder="Drapeaux TCP" list="flagsList"
                  pattern="(URG|ACK|PSH|RST|SYN|FIN|,)+|[1-9]?[0-9]|[1-2][0-9][0-9]" value="<?= $options['--scanflags'] ?? "" ?>"
                  title="Mélanger simplement les drapeaux URG, ACK, PSH, RST, SYN et FIN.">
              </div>

              <div class="field">
                <label for="sIInput" title="-sI">Hôte zombie</label>
                <input type="text" id="sIInput" name="-p" placeholder="zombie host[:probeport]"
                  pattern="[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $options['-sI'] ?? "" ?>"
                  title="zombie host[:probeport]">
              </div>

              <div class="field">
                <label for="bInput" title="-b">Rebond FTP</label>
                <input type="text" id="bInput" name="-p" placeholder="[<username>[:<password>]@]<server>[:<port>]"
                  pattern="([a-zA-Z0-9._\-]+(:.+)?@)?[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $options['-b'] ?? "" ?>"
                  title="[<username>[:<password>]@]<server>[:<port>]">
              </div>

              <div class="field">
                <div class="ui toggle checkbox">
                  <input type="checkbox" id="sUCheckbox" name="-sU" <?= $options['-sU'] ?? false ? 'checked' : ''; ?> />
                  <label for="sUCheckbox" title="-sO">Scan des protocoles supportés par la couche IP</label>
                </div>
              </div>
            </div>
          </div>

          <div class="title">
            <i class="icon dropdown"></i>
            Spécifications des ports et ordre du scan
          </div>
          <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox" title="-sP">
              <input type="checkbox" id="sPCheckbox" name="-sP" <?= $options['-sP'] ?? false ? 'checked' : ''; ?> />
              <label for="sPCheckbox">Sauter cette étape</label>
            </div>
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox" title="-F">
              <input type="checkbox" id="FCheckbox" name="-F" <?= $options['-F'] ?? false ? 'checked' : ''; ?>
                onchange="pInput.disabled = FCheckbox.checked" />
              <label for="FCheckbox">Scanner les ports connus</label>
            </div>
          </div>

          <div class="field">
            <label for="pInput" title="-p">Scanner les ports</label>
            <input type="text" id="pInput" name="-p" placeholder="Ports" list="servicesList" <?= $options['-F'] ?? false ? 'disabled' : ''; ?>
              pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-p'] ?? "" ?>"
              title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox" title="-r">
              <input type="checkbox" id="rCheckbox" name="-r" <?= $options['-r'] ?? false ? 'checked' : ''; ?> />
              <label for="rCheckbox">Ne pas mélanger les ports</label>
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Détection de services et de versions</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox" title="-sV">
              <input type="checkbox" id="sVCheckbox" name="-sV" <?= $options['-sV'] ?? false ? 'checked' : ''; ?> />
              <label for="sVCheckbox">Détection de version</label>
            </div>
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="allportsCheckbox" name="--allports" <?= $options['--allports'] ?? false ? 'checked' : ''; ?> />
              <label for="allportsCheckbox" title="--allports">N'exclure aucun port de la détection de version</label>
            </div>
          </div>

          <div class="field">
            <label for="versionIntensitySelect" title="--version-intensity">Intensité des tests de version</label>
            <select class="ui dropdown" id="versionIntensitySelect" name="--version-intensity" value="<?= $options["--version-intensity"] ?? ""?>">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">Léger</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">Défaut</option>
              <option value="8">8</option>
              <option value="9">Tous</option>
            </select>
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="sRCheckbox" name="-sR" <?= $options['-sR'] ?? false ? 'checked' : ''; ?> />
              <label for="sRCheckbox" title="-sR">Scan RPC</label>
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Détection du système d'exploitation</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="OCheckbox" name="-O" <?= $options['-O'] ?? false ? 'checked' : ''; ?> />
              <label for="OCheckbox" title="-O">Détecter le système d'exploitation</label>
            </div>
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="osscan-limitCheckbox" name="--osscan-limit" <?= $options['--osscan-limit'] ?? false ? 'checked' : ''; ?> />
              <label for="osscan-limitCheckbox" title="--osscan-limit">Seulement les cibles prometteuses</label>
            </div>
          </div>
          
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="osscan-guessCheckbox" name="--osscan-guess" <?= $options['--osscan-guess'] ?? false ? 'checked' : ''; ?> />
              <label for="osscan-guessCheckbox" title="--osscan-guess">Essayer de deviner</label>
            </div>
          </div>
          
          <div class="field">
            <label for="maxOSTriesInput">Nombre d'essais maximum</label>
            <input type="number" min="0" id="maxOSTriesInput" name="--max-os-tries" placeholder="Nombre d'essais"
              value="<?= $options["--max-os-tries"] ?? "" ?>">
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Divers</div>
        <div class="content">
          <div class="field">
            <label for="stylesheetSelect" title="--stylesheet">Feuille de style</label>
            <select class="ui dropdown" id="stylesheetSelect" name="--stylesheet" value="<?= $options["--stylesheet"] ?? ""?>">
<?php
foreach (scandir('xslt') as $filename) {
  if (substr($filename, -4) === '.xsl') {
    $name = substr($filename, 0, -4);
    $URL = htmlentities("$BASEDIR/xslt/$filename", ENT_QUOTES);
    if (isset($options["--stylesheet"]) && $URL == $options["--stylesheet"]) {
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
            <select class="ui dropdown" id="compareWithSelect" name="compareWith" value="<?= $options["compareWith"] ?? "" ?>">
              <option value="">Précédent scan</option>
<?php
if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
foreach (scandir($SCANSDIR) as $filename) {
  if (substr($filename, -4) === '.xml') {
    $name = substr($filename, 0, -4);
    $URL = htmlentities("$BASEDIR/$SCANSDIR/$filename", ENT_QUOTES);
    if (isset($options["compareWith"]) && $URL == $options["compareWith"]) {
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
                value="<?= $options["refreshPeriod"] ?? "" ?>">
              <div class="ui label">secondes</div>
            </div>
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox">
              <input type="checkbox" id="sudoCheckbox" name="sudo" <?= $options["sudo"] ?? false ? 'checked' : ''; ?>/>
              <label for="sudoCheckbox" title="sudo">Exécuter en tant qu'administrateur</label>
            </div>
          </div>
        </div>
      </div>

      <div class="field">
        <label for="saveAsInput">Enregistrer sous le nom</label>
        <input id="saveAsInput" type="text" name="saveAs" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
          title="Caractères interdits :  &lt;&gt;:&quot;\/|@?"
          value="<?= htmlentities($options["saveAs"] ?? "", ENT_QUOTES); ?>">
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
