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
            <input id="excludeInput" type="text" name="--exclude" placeholder="Hôte/réseau" list="targetsList"
              pattern="[a-zA-Z0-9._\/,\-]*" value="<?= $options['--exclude'] ?? "" ?>">
          </div>
          
          <div class="field">
            <label for="iRInput" title="-iR">Nombre de cibles au hasard</label>
            <input id="iRInput" type="number" min="0" name="-iR" placeholder="Nombre"
              value="<?= $options['-iR'] ?? "" ?>">
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Découverte des hôtes actifs</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="PnCheckbox" type="checkbox" name="-Pn" <?= $options['-Pn'] ?? false ? 'checked' : ''; ?> />
              <label for="PnCheckbox" title="-Pn">Sauter cette étape (considérer tous les hôtes comme actifs)</label>
            </div>
          </div>

          <div class="field">
            <label for="PSInput" title="-PS">TCP SYN</label>
            <input id="PSInput" type="text" name="-PS" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PS'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label for="PAInput" title="-PA">TCP ACK</label>
            <input id="PAInput" type="text" name="-PA" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PA'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label for="PUInput" title="-PU">UDP</label>
            <input id="PUInput" type="text" name="-PU" placeholder="Ports" list="servicesList"
              pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-PU'] ?? "" ?>"
              title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
          </div>

          <div class="field">
            <label>ICMP</label>
            <div class="inline fields">
              <div class="field">
                <div class="ui toggle checkbox">
                  <input id="PECheckbox" type="checkbox" name="-PE" <?= $options['-PE'] ?? false ? 'checked' : ''; ?> />
                  <label for="PECheckbox" title="-PE">Echo request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui toggle checkbox">
                  <input id="PPCheckbox" type="checkbox" name="-PP" <?= $options['-PP'] ?? false ? 'checked' : ''; ?> />
                  <label for="PPCheckbox" title="-PP">Timestamp request</label>
                </div>
              </div>
              <div class="field">
                <div class="ui toggle checkbox">
                  <input id="PMCheckbox" type="checkbox" name="-PM" <?= $options['-PM'] ?? false ? 'checked' : ''; ?> />
                  <label for="PMCheckbox" title="-PM">Mask request</label>
                </div>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="POInput" title="-PO">Protocole IP (par type)</label>
            <input id="POInput" type="text" name="-PO" placeholder="Protocole"
              pattern="[0-9,\-]+" value="<?= $options['-PO'] ?? "" ?>"
              title="[num de protocole]">
          </div>

          <div class="inline fields">
            <div class="field">
              <div class="ui toggle checkbox">
                <input id="PRCheckbox" type="checkbox" name="-PR" <?= $options['-PR'] ?? false ? 'checked' : ''; ?> />
                <label for="PRCheckbox" title="-PR">Ping ARP</label>
              </div>
            </div>
            <div class="field">
              <div class="ui toggle checkbox">
                <input id="sendIPCheckbox" type="checkbox" name="--send-ip" <?= $options['--send-ip'] ?? false ? 'checked' : ''; ?> />
                <label for="sendIPCheckbox" title="--send-ip">Pas de scan ARP</label>
              </div>
            </div>
          </div>

          <div class="inline fields">
            <div class="field">
              <div class="ui toggle checkbox">
                <input id="nCheckbox" type="checkbox" name="-n" <?= $options['-n'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox" title="-n">Ne jamais résoudre les noms DNS</label>
              </div>
            </div>
            <div class="field">
              <div class="ui toggle checkbox">
                <input id="RCheckbox" type="checkbox" name="-R" <?= $options['-R'] ?? false ? 'checked' : ''; ?> />
                <label for="nCheckbox" title="-R">Toujours résoudre les noms DNS<br />(par défault seuls les hôtes actifs sont résolus)</label>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="dnsServersInput" title="--dns-servers">Utiliser les serveurs DNS</label>
            <input id="dnsServersInput" type="text" name="--dns-servers" placeholder="serveur"
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
                    <input id="sSCheckbox" type="checkbox" name="-sS" <?= $options['-sS'] ?? false ? 'checked' : ''; ?> />
                    <label for="sSCheckbox" title="-sS">TCP SYN</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sTCheckbox" type="checkbox" name="-sT" <?= $options['-sT'] ?? false ? 'checked' : ''; ?> />
                    <label for="sTCheckbox" title="-sT">TCP Connect()</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sACheckbox" type="checkbox" name="-sA" <?= $options['-sA'] ?? false ? 'checked' : ''; ?> />
                    <label for="sACheckbox" title="-sA">TCP ACK</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sWCheckbox" type="checkbox" name="-sW" <?= $options['-sW'] ?? false ? 'checked' : ''; ?> />
                    <label for="sWCheckbox" title="-sW">Fenêtre TCP</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sMCheckbox" type="checkbox" name="-sM" <?= $options['-sM'] ?? false ? 'checked' : ''; ?> />
                    <label for="sMCheckbox" title="-sM">Maimon</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sNCheckbox" type="checkbox" name="-sN" <?= $options['-sN'] ?? false ? 'checked' : ''; ?> />
                    <label for="sNCheckbox" title="-sN">TCP Null</label>
                  </div>
                </div>
              </div>

              <div class="fields">
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sFCheckbox" type="checkbox" name="-sF" <?= $options['-sF'] ?? false ? 'checked' : ''; ?> />
                    <label for="sFCheckbox" title="-sF">TCP FIN</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sXCheckbox" type="checkbox" name="-sX" <?= $options['-sX'] ?? false ? 'checked' : ''; ?> />
                    <label for="sXCheckbox" title="-sX">Sapin de Noël</label>
                  </div>
                </div>
                <div class="field">
                  <div class="ui toggle checkbox">
                    <input id="sUCheckbox" type="checkbox" name="-sU" <?= $options['-sU'] ?? false ? 'checked' : ''; ?> />
                    <label for="sUCheckbox" title="-sU">UDP</label>
                  </div>
                </div>
              </div>

              <div class="field">
                <label for="scanflagsInput" title="--scanflags">Scan TCP personnalisé</label>
                <input id="scanflagsInput" type="text" name="--scanflags" placeholder="Drapeaux TCP" list="flagsList"
                  pattern="(URG|ACK|PSH|RST|SYN|FIN|,)+|[1-9]?[0-9]|[1-2][0-9][0-9]" value="<?= $options['--scanflags'] ?? "" ?>"
                  title="Mélanger simplement les drapeaux URG, ACK, PSH, RST, SYN et FIN.">
              </div>

              <div class="field">
                <label for="sIInput" title="-sI">Hôte zombie</label>
                <input id="sIInput" type="text" name="-p" placeholder="zombie host[:probeport]"
                  pattern="[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $options['-sI'] ?? "" ?>"
                  title="zombie host[:probeport]">
              </div>

              <div class="field">
                <label for="bInput" title="-b">Rebond FTP</label>
                <input id="bInput" type="text" name="-p" placeholder="[<username>[:<password>]@]<server>[:<port>]"
                  pattern="([a-zA-Z0-9._\-]+(:.+)?@)?[a-zA-Z0-9._\-]+(:[0-9]+)?" value="<?= $options['-b'] ?? "" ?>"
                  title="[<username>[:<password>]@]<server>[:<port>]">
              </div>

              <div class="field">
                <div class="ui toggle checkbox">
                  <input id="sUCheckbox" type="checkbox" name="-sU" <?= $options['-sU'] ?? false ? 'checked' : ''; ?> />
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
              <input id="sPCheckbox" type="checkbox" name="-sP" <?= $options['-sP'] ?? false ? 'checked' : ''; ?> />
              <label for="sPCheckbox">Sauter cette étape</label>
            </div>
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox" title="-F">
              <input id="FCheckbox" type="checkbox" name="-F" <?= $options['-F'] ?? false ? 'checked' : ''; ?>
                onchange="pInput.disabled = FCheckbox.checked" />
              <label for="FCheckbox">Scanner les ports connus</label>
            </div>
          </div>

          <div class="field">
            <label for="pInput" title="-p">Scanner les ports</label>
            <input id="pInput" type="text" name="-p" placeholder="Ports" list="servicesList" <?= $options['-F'] ?? false ? 'disabled' : ''; ?>
              pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*" value="<?= $options['-p'] ?? "" ?>"
              title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox" title="-r">
              <input id="rCheckbox" type="checkbox" name="-r" <?= $options['-r'] ?? false ? 'checked' : ''; ?> />
              <label for="rCheckbox">Ne pas mélanger les ports</label>
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Détection de services et de versions</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox" title="-sV">
              <input id="sVCheckbox" type="checkbox" name="-sV" <?= $options['-sV'] ?? false ? 'checked' : ''; ?> />
              <label for="sVCheckbox">Détection de version</label>
            </div>
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="allportsCheckbox" type="checkbox" name="--allports" <?= $options['--allports'] ?? false ? 'checked' : ''; ?> />
              <label for="allportsCheckbox" title="--allports">N'exclure aucun port de la détection de version</label>
            </div>
          </div>
          
          <div class="field">
            <label for="versionIntensityInput" title="--version-intensity">Intensité des tests de version</label>
            <input type="number" min="0" max="9" id="versionIntensityInput" name="--version-intensity" placeholder="0-9"
              value="<?= $options["--version-intensity"] ?? "" ?>" title="2: léger, 9: tous, défaut: 7">
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="sRCheckbox" type="checkbox" name="-sR" <?= $options['-sR'] ?? false ? 'checked' : ''; ?> />
              <label for="sRCheckbox" title="-sR">Scan RPC</label>
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Scripts</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="sCCheckbox" type="checkbox" name="-sC" <?= $options['-sC'] ?? false ? 'checked' : ''; ?> />
              <label for="sCCheckbox" title="-sC">Scripts par défaut</label>
            </div>
          </div>

          <div class="field">
            <label for="scriptInput">Scripts</label>
            <input id="scriptInput" type="text" name="--script" placeholder="Nom"
              title="<catégories|répertoire|nom|all>" list="scripts" pattern="[a-z][a-z0-9\-\.\/]*"
              value="<?= $options["--script"] ?? ""; ?>">
          </div>

          <div class="field">
            <label for="scriptArgsInput" title="--script-args">Arguments des scripts</label>
            <input id="scriptArgsInput" type="text" name="--script-args" placeholder="arg=valeur"
              pattern='[a-zA-Z][a-zA-Z0-9\-_]*=[^"]+(,[a-zA-Z][a-zA-Z0-9\-_]*=[^"]+)?' value="<?= $options['--script-args'] ?? "" ?>"
              title="<n1=v1,[n2=v2,...]>">
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="scriptTraceCheckbox" type="checkbox" name="--script-trace" <?= $options['--script-trace'] ?? false ? 'checked' : ''; ?> />
              <label for="scriptTraceCheckbox" title="--script-trace">Montrer toutes les données envoyées ou recues</label>
            </div>
          </div>
          
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="scriptUpdateDBCheckbox" type="checkbox" name="--script-updatedb" <?= $options['--script-updatedb'] ?? false ? 'checked' : ''; ?> />
              <label for="scriptUpdateDBCheckbox" title="--script-updatedb">Mettre à jour la base de données des scripts</label>
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Détection du système d'exploitation</div>
        <div class="content">
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="OCheckbox" type="checkbox" name="-O" <?= $options['-O'] ?? false ? 'checked' : ''; ?> />
              <label for="OCheckbox" title="-O">Détecter le système d'exploitation</label>
            </div>
          </div>
        
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="osscanLimitCheckbox" type="checkbox" name="--osscan-limit" <?= $options['--osscan-limit'] ?? false ? 'checked' : ''; ?> />
              <label for="osscanLimitCheckbox" title="--osscan-limit">Seulement les cibles prometteuses</label>
            </div>
          </div>
          
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="osscanGuessCheckbox" type="checkbox" name="--osscan-guess" <?= $options['--osscan-guess'] ?? false ? 'checked' : ''; ?> />
              <label for="osscanGuessCheckbox" title="--osscan-guess">Essayer de deviner</label>
            </div>
          </div>
          
          <div class="field">
            <label for="maxOSTriesInput" title="--max-os-tries">Nombre d'essais maximum</label>
            <input type="number" min="0" id="maxOSTriesInput" name="--max-os-tries" placeholder="Nombre"
              value="<?= $options["--max-os-tries"] ?? "" ?>">
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Temporisation et performances</div>
        <div class="content">
          <div class="field">
            <label for="TSelect" title="--T">Intensité des tests de version</label>
            <select id="TSelect" class="ui dropdown" name="-T" value="<?= $options["-T"] ?? ""?>">
              <option value="0"<?=($options["-T"]??"")==0?" selected":""?>>Paranoïaque</option>
              <option value="1"<?=($options["-T"]??"")==1?" selected":""?>>Sournois</option>
              <option value="2"<?=($options["-T"]??"")==2?" selected":""?>>Poli</option>
              <option value="3"<?=($options["-T"]??"")==3?" selected":""?>>Normal</option>
              <option value="4"<?=($options["-T"]??"")==4?" selected":""?>>Aggressif</option>
              <option value="5"<?=($options["-T"]??"")==5?" selected":""?>>Dément</option>
            </select>
          </div>
          
          <div class="field">
            <label>Tailles des groupes d'hôtes à scanner en parallèle</label>
            <div class="two fields">
              <div class="field">
                <label for="minHostgroupInput" title="--min-hostgroup">Minimum</label>
                <input id="minHostgroupInput" type="number" min="0" placeholder="Nombre"
                value="<?= $options["--min-hostgroup"] ?? "" ?>"
                oninput="maxHostgroupInput.min = minHostgroupInput.value">
              </div>
              <div class="field">
                <label for="maxHostgroupInput" title="--max-hostgroup">Maximum</label>
                <input id="maxHostgroupInput" type="number" min="0" placeholder="Nombre"
                value="<?= $options["--max-hostgroup"] ?? "" ?>"
                oninput="minHostgroupInput.max = maxHostgroupInput.value">
              </div>
            </div>
          </div>
          
          <div class="field">
            <label>Parallélisation des paquets de tests</label>
            <div class="two fields">
              <div class="field">
                <label for="minParallelismInput" title="--min-parallelism">Minimum</label>
                <input id="minParallelismInput" type="number" min="0" placeholder="Nombre"
                value="<?= $options["--min-parallelism"] ?? "" ?>"
                oninput="maxParallelismInput.min = minParallelismInput.value">
              </div>
              <div class="field">
                <label for="maxParallelismInput" title="--max-parallelism">Maximum</label>
                <input id="maxParallelismInput" type="number" min="0" placeholder="Nombre"
                value="<?= $options["--max-parallelism"] ?? "" ?>"
                oninput="minParallelismInput.max = maxParallelismInput.value">
              </div>
            </div>
          </div>

          <div class="field">
            <label>Temps d'aller-retour des paquets de tests</label>
            <div class="three fields">
              <div class="field">
                <label for="initialRTTNumber" title="--initial-rtt-timeout">Initial</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" id="initialRTTNumber" placeholder="Durée"
                    oninput="initialRTTHidden.value = initialRTTNumber.value? initialRTTNumber.value+initialRTTUnit.value: ''; maxRTTHidden.initial=initialRTTHidden.value"
                    <?= preg_match("/^\d+/", $options["--initial-rtt-timeout"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
                    <select id="initialRTTUnit" class="ui dropdown label"
                      oninput="initialRTTHidden.value = initialRTTNumber.value? initialRTTNumber.value+initialRTTUnit.value: ''">
                      <option value="">ms</option>
                      <option value="s" <?=substr($options["--initial-rtt-timeout"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                      <option value="m" <?=substr($options["--initial-rtt-timeout"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                      <option value="h" <?=substr($options["--initial-rtt-timeout"]??"", -1)=="h"?"selected":"" ?>>heures</option>
                    </select>
                </div>
                <input id="initialRTTHidden" type="hidden" name="--initial-rtt-timeout"
                  value="<?= $options["--initial-rtt-timeout"] ?? "" ?>">
              </div>
              <div class="field">
                <label for="minRTTNumber" title="--min-rtt-timeout">Minimum</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" id="minRTTNumber" placeholder="Durée"
                    oninput="minRTTHidden.value = minRTTNumber.value? minRTTNumber.value+minRTTUnit.value: ''; maxRTTHidden.min=minRTTHidden.value"
                    <?= preg_match("/^\d+/", $options["--min-rtt-timeout"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
                    <select id="minRTTUnit" class="ui dropdown label"
                      oninput="minRTTHidden.value = minRTTNumber.value? minRTTNumber.value+minRTTUnit.value: ''">
                      <option value="">ms</option>
                      <option value="s" <?=substr($options["--min-rtt-timeout"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                      <option value="m" <?=substr($options["--min-rtt-timeout"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                      <option value="h" <?=substr($options["--min-rtt-timeout"]??"", -1)=="h"?"selected":"" ?>>heures</option>
                    </select>
                </div>
                <input id="minRTTHidden" type="hidden" name="--min-rtt-timeout"
                  value="<?= $options["--min-rtt-timeout"] ?? "" ?>">
              </div>
              <div class="field">
                <label for="maxRTTNumber" title="--max-rtt-timeout">Maximum</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" id="maxRTTNumber" placeholder="Durée"
                    oninput="maxRTTHidden.value = maxRTTNumber.value? maxRTTNumber.value+maxRTTUnit.value: ''; minRTTHidden.max=maxRTTHidden.value"
                    <?= preg_match("/^\d+/", $options["--max-rtt-timeout"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
                  <select id="maxRTTUnit" class="ui dropdown label"
                    oninput="maxRTTHidden.value = maxRTTNumber.value? maxRTTNumber.value+maxRTTUnit.value: ''">
                    <option value="">ms</option>
                    <option value="s" <?=substr($options["--max-rtt-timeout"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                    <option value="m" <?=substr($options["--max-rtt-timeout"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                    <option value="h" <?=substr($options["--max-rtt-timeout"]??"", -1)=="h"?"selected":"" ?>>heures</option>
                  </select>
                </div>
                <input id="maxRTTHidden" type="hidden" name="--max-rtt-timeout"
                  value="<?= $options["--max-rtt-timeout"] ?? "" ?>">
              </div>
            </div>
          </div>
          
          <div class="field">
            <label for="maxRetriesInput" title="--max-retries">Nombre de retransmissions des paquets de tests des scans de ports</label>
            <input type="number" min="0" id="maxRetriesInput" name="--max-retries" placeholder="Nombre"
              value="<?= $options["--max-retries"] ?? "" ?>">
          </div>

          <div class="field">
            <label for="hostTimoutInput" title="--host-timeout">Délai d'expiration du scan d'un hôte trop lent</label>
            <div class="ui right labeled input">
              <input type="number" min="0" id="hostTimoutNumber" placeholder="Durée"
                oninput="hostTimoutHidden.value = hostTimoutNumber.value? hostTimoutNumber.value+hostTimoutUnit.value: ''"
                <?= preg_match("/^\d+/", $options["--host-timeout"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
              <select id="hostTimoutUnit" class="ui dropdown label"
                oninput="hostTimoutHidden.value = hostTimoutNumber.value? hostTimoutNumber.value+hostTimoutUnit.value: ''">
                <option value="">ms</option>
                <option value="s" <?=substr($options["--host-timeout"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                <option value="m" <?=substr($options["--host-timeout"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                <option value="h" <?=substr($options["--host-timeout"]??"", -1)=="h"?"selected":"" ?>>heures</option>
              </select>
            </div>
            <input id="hostTimoutHidden" type="hidden" name="--host-timeout"
              value="<?= $options["--host-timeout"] ?? "" ?>">
          </div>

          <div class="two fields">
            <div class="field">
              <label for="scanDelayNumber" title="--scan-delay">Délai entre les paquets de tests</label>
              <div class="ui right labeled input">
                <input type="number" min="0" id="scanDelayNumber" placeholder="Durée"
                  oninput="scanDelayHidden.value = scanDelayNumber.value? scanDelayNumber.value+scanDelayUnit.value: ''"
                  <?= preg_match("/^\d+/", $options["--scan-delay"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
                  <select id="scanDelayUnit" class="ui dropdown label"
                    oninput="scanDelayHidden.value = scanDelayNumber.value? scanDelayNumber.value+scanDelayUnit.value: ''">
                    <option value="">ms</option>
                    <option value="s" <?=substr($options["--scan-delay"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                    <option value="m" <?=substr($options["--scan-delay"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                    <option value="h" <?=substr($options["--scan-delay"]??"", -1)=="h"?"selected":"" ?>>heures</option>
                  </select>
              </div>
              <input id="scanDelayHidden" type="hidden" name="--scan-delay"
                value="<?= $options["--scan-delay"] ?? "" ?>">
            </div>
            <div class="field">
              <label for="maxScanDelay" title="--max-scan-delay">Maximum</label>
              <div class="ui right labeled input">
                <input type="number" min="0" id="maxScanDelay" placeholder="Durée"
                  oninput="maxRTTHidden.value = maxScanDelay.value? maxScanDelay.value+maxRTTUnit.value: ''"
                  <?= preg_match("/^\d+/", $options["--max-scan-delay"] ?? "", $matches) ? "value='{$matches[0]}'" : "" ?>>
                <select id="maxRTTUnit" class="ui dropdown label"
                  oninput="maxRTTHidden.value = maxScanDelay.value? maxScanDelay.value+maxRTTUnit.value: ''">
                  <option value="">ms</option>
                  <option value="s" <?=substr($options["--max-scan-delay"]??"", -1)=="s"?"selected":"" ?>>secondes</option>
                  <option value="m" <?=substr($options["--max-scan-delay"]??"", -1)=="m"?"selected":"" ?>>minutes</option>
                  <option value="h" <?=substr($options["--max-scan-delay"]??"", -1)=="h"?"selected":"" ?>>heures</option>
                </select>
              </div>
              <input id="maxRTTHidden" type="hidden" name="--max-scan-delay"
                value="<?= $options["--max-scan-delay"] ?? "" ?>">
            </div>
          </div>
        </div>

        <div class="title"><i class="icon dropdown"></i>Divers</div>
        <div class="content">
          <div class="field">
            <label for="stylesheetSelect" title="--stylesheet">Feuille de style</label>
            <select id="stylesheetSelect" class="ui dropdown" name="--stylesheet" value="<?= $options["--stylesheet"] ?? ""?>">
<?php
foreach (scandir('templates') as $filename) {
  if (substr($filename, -4) === '.xsl') {
    $name = substr($filename, 0, -4);
    $URL = "$BASEDIR/templates/".rawurlencode($filename);
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
            <label for="originalURLSelect">Comparer avec un précédent scan</label>
            <select id="originalURLSelect" class="ui dropdown" name="originalURL" value="<?= $options["originalURL"] ?? "" ?>">
              <option value="">Précédent scan</option>
<?php
if (!file_exists($SCANSDIR)) mkdir($SCANSDIR);
foreach (scandir($SCANSDIR) as $filename) {
  if (substr($filename, -4) === '.xml') {
    $name = substr($filename, 0, -4);
    $URL = "$BASEDIR/$SCANSDIR/".rawurlencode($filename);
    if (isset($options["originalURL"]) && $URL == $options["originalURL"]) {
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
              <input id="refreshPeriodInput" type="number" min="0" name="refreshPeriod" placeholder="Période"
                value="<?= $options["refreshPeriod"] ?? "" ?>">
              <div class="ui label">secondes</div>
            </div>
          </div>

          <div class="inline field">
            <div class="ui toggle checkbox">
              <input id="sudoCheckbox" type="checkbox" name="sudo" <?= $options["sudo"] ?? false ? 'checked' : ''; ?>/>
              <label for="sudoCheckbox" title="sudo">Exécuter en tant qu'administrateur</label>
            </div>
          </div>
        </div>
      </div>

      <div class="field">
        <label for="saveAsInput">Enregistrer sous le nom</label>
        <input id="saveAsInput" type="text" name="saveAs" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
          title="Caractères interdits :  &lt;&gt;:&quot;\/|@?"
          value="<?= $options["saveAs"] ?? ""; ?>">
      </div>

      <button type="submit" class="ui teal submit button">Démarrer</button>
    </form>

    <h2 class="ui header">Scans enregistrés</h1>
    <div class="ui large relaxed card">
      <div class="content">
        <div class="ui divided link list">
<?php
if (!file_exists($SCANSDIR)) {
  mkdir($SCANSDIR);
}
foreach (scandir($SCANSDIR) as $filename) {
  if (substr($filename, -4) == '.xml') {
    $name = str_replace('!', '/', substr_replace($filename, '', -4));
    echo "<a class='item' href='$SCANSDIR/".rawurlencode($filename)."'>$name</a>\n";
  }
}
?>
        </div>
      </div>
    </div>
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
$services = [];
foreach ([$DATADIR, $NMAPDIR] as $dir) {
  echo "<!-- $nmap_services -->\n";
  if (file_exists("$dir/nmap-services")) {
    $nmap_services = file("$dir/nmap-services");
    foreach ($nmap_services as $service) {
      if (0 !== strpos($service, '#')) {
        [$name, $port] = explode("\t", $service);
        $services[$name] = explode("/", $port);
      }
    }
  }
}
foreach ($services as $name => [$portid, $protocol]) {
  echo "    <option value='$name'></option>\n";
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

  <datalist id="scripts">
    <!-- categories -->
    <option value="auth"></option>
    <option value="broadcast"></option>
    <option value="brute"></option>
    <option value="default"></option>
    <option value="ddiscovery"></option>
    <option value="dos"></option>
    <option value="exploit"></option>
    <option value="external"></option>
    <option value="fuzzer"></option>
    <option value="intrusive"></option>
    <option value="malware"></option>
    <option value="safe"></option>
    <option value="version"></option>
    <option value="vuln"></option>
    <!-- names -->
<?php
foreach ([$DATADIR, $NMAPDIR] as $dir) {
  foreach (scandir("$dir/scripts") as $filename) {
    if (substr($filename, -4) === '.nse') {
      $name = substr($filename, 0, -4);
      echo "    <option value='$name'></option>\n";
    }
  }
}
?>
  </datalist>
  
  <script>
    class TagsInput extends Tagify {
      constructor(input, options={}, delim = ",") {
        if (!options.delimiters) options.delimiters = " |,"
        if (!options.originalURLInputValueFormat) options.originalURLInputValueFormat = tags => tags.map(tag => tag.value).join(delim)
        if (input.list) options.whitelist = Array.from(input.list.options).map(option => option.value)
        super(input, options)
      }
    }

    $(".ui.accordion").accordion()

    $(".ui.dropdown").dropdown({
      clearable: true
    })

    new TagsInput(targetsInput, {}, " ")
    new TagsInput(excludeInput)
    new TagsInput(PSInput)
    new TagsInput(PAInput)
    new TagsInput(PUInput)
    new TagsInput(POInput)
    var pTagsInput = new TagsInput(pInput)
    FCheckbox.onchange = () => {
      pInput.disabled = FCheckbox.checked
      pTagsInput.setDisabled(FCheckbox.checked)
    }
    new TagsInput(dnsServersInput)
    new TagsInput(scanflagsInput)
    new TagsInput(scriptInput, {enforceWhitelist: true})
    new TagsInput(scriptArgsInput, {delimiters: ','})

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
