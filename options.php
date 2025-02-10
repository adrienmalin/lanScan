<?php include_once 'config.php'; ?>

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

  <body class="inverted">
    <nav class="ui inverted secondary menu">
      <a href="." class="ui header button item logo">lan<?php include 'logo.svg'; ?>can</a>
      <div class="right menu">
        <div class="item">
          <a class="ui icon button item" href="https://nmap.org/man/fr/index.html" target="_blank">
            <i class="question circle icon"></i>
          </a>
          <button id="toggleThemeButton" type="button" class="ui icon link item" title="Thème clair/sombre"
            onclick="toggleTheme()">
            <i class="sun icon"></i>
          </button>
        </div>
      </div>
    </nav>

    <main class="ui main container">

      <h1 class="ui inverted header">Scanner un réseau</h1>

      <form id="newScanForm" class="ui inverted form" method="get" action="scan.php">
        <div class="inverted field">
          <label for="targetInput" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254">Cibles</label>
          <input id="targetInput" type="text" name="target" placeholder="Cibles" spellcheck="false" required
            pattern="[a-zA-Z0-9._\/ \-]+" list="targetList" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
        </div>

        <div class="ui styled fluid accordion inverted field">
          <div class="title"><i class="icon dropdown"></i>Spécification des cibles</div>
          <div class="content">
            <div class="inverted field">
              <label for="excludeInput" title="--exclude">Exclure les hôtes ou réseaux</label>
              <input id="excludeInput" type="text" name="--exclude" placeholder="Hôte/réseau" list="targetList"
                pattern="[a-zA-Z0-9._\/,\-]*">
            </div>

            <div class="inverted field">
              <label for="iRInput" title="-iR">Nombre de cibles au hasard</label>
              <input id="iRInput" type="number" min="0" name="-iR" placeholder="Nombre">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Découverte des hôtes actifs</div>
          <div class="content">
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="PnCheckbox" type="checkbox" name="-Pn" />
                <label for="PnCheckbox" title="-Pn">Sauter cette étape (considérer tous les hôtes comme actifs)</label>
              </div>
            </div>

            <div class="inverted field">
              <label for="PSInput" title="-PS">TCP SYN</label>
              <input id="PSInput" type="text" name="-PS" placeholder="Ports" list="servicesList"
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*"
                title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
            </div>

            <div class="inverted field">
              <label for="PAInput" title="-PA">TCP ACK</label>
              <input id="PAInput" type="text" name="-PA" placeholder="Ports" list="servicesList"
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*"
                title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
            </div>

            <div class="inverted field">
              <label for="PUInput" title="-PU">UDP</label>
              <input id="PUInput" type="text" name="-PU" placeholder="Ports" list="servicesList"
                pattern="([0-9\-]+|[a-z\-]+)(,[0-9\-]+|,[a-z\-]+)*"
                title="Liste de ports ex: 22,23,25,80,200-1024,60000-">
            </div>

            <div class="inverted field">
              <label>ICMP</label>
              <div class="inline inverted fields">
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="PECheckbox" type="checkbox" name="-PE" />
                    <label for="PECheckbox" title="-PE">Echo request</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="PPCheckbox" type="checkbox" name="-PP" />
                    <label for="PPCheckbox" title="-PP">Timestamp request</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="PMCheckbox" type="checkbox" name="-PM" />
                    <label for="PMCheckbox" title="-PM">Mask request</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="inverted field">
              <label for="POInput" title="-PO">Protocole IP (par type)</label>
              <input id="POInput" type="text" name="-PO" placeholder="Protocole" pattern="[0-9,\-]+"
                title="[num de protocole]">
            </div>

            <div class="inline inverted fields">
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="PRCheckbox" type="checkbox" name="-PR" />
                  <label for="PRCheckbox" title="-PR">Ping ARP</label>
                </div>
              </div>
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="sendIPCheckbox" type="checkbox" name="--send-ip" />
                  <label for="sendIPCheckbox" title="--send-ip">Pas de scan ARP</label>
                </div>
              </div>
            </div>

            <div class="inline inverted fields">
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="nCheckbox" type="checkbox" name="-n" />
                  <label for="nCheckbox" title="-n">Ne jamais résoudre les noms DNS</label>
                </div>
              </div>
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="RCheckbox" type="checkbox" name="-R" />
                  <label for="nCheckbox" title="-R">Toujours résoudre les noms DNS<br />(par défault seuls les hôtes
                    actifs sont résolus)</label>
                </div>
              </div>
            </div>

            <div class="inverted field">
              <label for="dnsServersInput" title="--dns-servers">Utiliser les serveurs DNS</label>
              <input id="dnsServersInput" type="text" name="--dns-servers" placeholder="serveur"
                pattern="[a-zA-Z0-9._,\-]*" title="serv1[,serv2],...">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Techniques de scan de ports</div>
          <div class="content">
            <div class="inverted field">
              <div class="inverted fields">
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sSCheckbox" type="checkbox" name="-sS" />
                    <label for="sSCheckbox" title="-sS">TCP SYN</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sTCheckbox" type="checkbox" name="-sT" />
                    <label for="sTCheckbox" title="-sT">TCP Connect()</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sACheckbox" type="checkbox" name="-sA" />
                    <label for="sACheckbox" title="-sA">TCP ACK</label>
                  </div>
                </div>
              </div>

              <div class="inverted fields">
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sWCheckbox" type="checkbox" name="-sW" />
                    <label for="sWCheckbox" title="-sW">Fenêtre TCP</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sMCheckbox" type="checkbox" name="-sM" />
                    <label for="sMCheckbox" title="-sM">Maimon</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sNCheckbox" type="checkbox" name="-sN" />
                    <label for="sNCheckbox" title="-sN">TCP Null</label>
                  </div>
                </div>
              </div>

              <div class="inverted fields">
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sFCheckbox" type="checkbox" name="-sF" />
                    <label for="sFCheckbox" title="-sF">TCP FIN</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sXCheckbox" type="checkbox" name="-sX" />
                    <label for="sXCheckbox" title="-sX">Sapin de Noël</label>
                  </div>
                </div>
                <div class="inverted field">
                  <div class="ui toggle inverted checkbox">
                    <input id="sUCheckbox" type="checkbox" name="-sU" />
                    <label for="sUCheckbox" title="-sU">UDP</label>
                  </div>
                </div>
              </div>

              <div class="inverted field">
                <label for="scanflagsInput" title="--scanflags">Scan TCP personnalisé</label>
                <input id="scanflagsInput" type="text" name="--scanflags" placeholder="Drapeaux TCP" list="flagsList"
                  pattern="(URG|ACK|PSH|RST|SYN|FIN|,)+|[1-9]?[0-9]|[1-2][0-9][0-9]"
                  title="Mélanger simplement les drapeaux URG, ACK, PSH, RST, SYN et FIN.">
              </div>

              <div class="inverted field">
                <label for="sIInput" title="-sI">Hôte zombie</label>
                <input id="sIInput" type="text" name="-p" placeholder="zombie host[:probeport]"
                  pattern="[a-zA-Z0-9._\-]+(:[0-9]+)?" title="zombie host[:probeport]">
              </div>

              <div class="inverted field">
                <label for="bInput" title="-b">Rebond FTP</label>
                <input id="bInput" type="text" name="-p" placeholder="[<username>[:<password>]@]<server>[:<port>]"
                  pattern="([a-zA-Z0-9._\-]+(:.+)?@)?[a-zA-Z0-9._\-]+(:[0-9]+)?"
                  title="[<username>[:<password>]@]<server>[:<port>]">
              </div>

              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="sUCheckbox" type="checkbox" name="-sU" />
                  <label for="sUCheckbox" title="-sO">Scan des protocoles supportés par la couche IP</label>
                </div>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Spécifications des ports et ordre du scan</div>
          <div class="content">
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox" title="-sP">
                <input id="sPCheckbox" type="checkbox" name="-sP" />
                <label for="sPCheckbox">Sauter cette étape</label>
              </div>
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox" title="-F">
                <input id="FCheckbox" type="checkbox" name="-F" onchange="pInput.disabled = FCheckbox.checked" />
                <label for="FCheckbox">Scanner les ports connus</label>
              </div>
            </div>

            <div class="inverted field">
              <label for="pInput" title="-p">Scanner les ports</label>
              <input id="pInput" type="text" name="-p" placeholder="Ports" list="servicesList"
                pattern="(([TU]:)?[0-9\-]+|[a-z\-]+)(,([TU]:)?[0-9\-]+|,[a-z\-]+)*"
                title="Liste de ports ex: ssh,ftp,U:53,111,137,T:21-25,80,139,8080">
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox" title="-r">
                <input id="rCheckbox" type="checkbox" name="-r" />
                <label for="rCheckbox">Ne pas mélanger les ports</label>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Détection de services et de versions</div>
          <div class="content">
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox" title="-sV">
                <input id="sVCheckbox" type="checkbox" name="-sV" />
                <label for="sVCheckbox">Détection de version</label>
              </div>
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="allportsCheckbox" type="checkbox" name="--allports" />
                <label for="allportsCheckbox" title="--allports">N'exclure aucun port de la détection de version</label>
              </div>
            </div>

            <div class="inverted field">
              <label for="versionIntensityInput" title="--version-intensity">Intensité des tests de version</label>
              <input type="number" min="0" max="9" id="versionIntensityInput" name="--version-intensity"
                placeholder="0-9" title="2: léger, 9: tous, défaut: 7">
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="sRCheckbox" type="checkbox" name="-sR" />
                <label for="sRCheckbox" title="-sR">Scan RPC</label>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Scripts</div>
          <div class="content">
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="sCCheckbox" type="checkbox" name="-sC" />
                <label for="sCCheckbox" title="-sC">Scripts par défaut</label>
              </div>
            </div>

            <div class="inverted field">
              <label for="scriptInput">Scripts</label>
              <input id="scriptInput" type="text" name="--script" placeholder="Nom"
                title="<catégories|répertoire|nom|all>" list="scripts" pattern="[a-z][a-z0-9\-\.\/]*">
            </div>

            <div class="inverted field">
              <label for="scriptArgsInput" title="--script-args">Arguments des scripts</label>
              <input id="scriptArgsInput" type="text" name="--script-args" placeholder="arg=valeur"
                pattern='[a-zA-Z][a-zA-Z0-9\-_]*=[^"]+(,[a-zA-Z][a-zA-Z0-9\-_]*=[^"]+)?' title="<n1=v1,[n2=v2,...]>">
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="scriptTraceCheckbox" type="checkbox" name="--script-trace" />
                <label for="scriptTraceCheckbox" title="--script-trace">Montrer toutes les données envoyées ou
                  recues</label>
              </div>
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="scriptUpdateDBCheckbox" type="checkbox" name="--script-updatedb" />
                <label for="scriptUpdateDBCheckbox" title="--script-updatedb">Mettre à jour la base de données des
                  scripts</label>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Détection du système d'exploitation</div>
          <div class="content">
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="OCheckbox" type="checkbox" name="-O" />
                <label for="OCheckbox" title="-O">Détecter le système d'exploitation</label>
              </div>
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="osscanLimitCheckbox" type="checkbox" name="--osscan-limit" />
                <label for="osscanLimitCheckbox" title="--osscan-limit">Seulement les cibles prometteuses</label>
              </div>
            </div>

            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="osscanGuessCheckbox" type="checkbox" name="--osscan-guess" />
                <label for="osscanGuessCheckbox" title="--osscan-guess">Essayer de deviner</label>
              </div>
            </div>

            <div class="inverted field">
              <label for="maxOSTriesInput" title="--max-os-tries">Nombre d'essais maximum</label>
              <input type="number" min="0" id="maxOSTriesInput" name="--max-os-tries" placeholder="Nombre">
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Temporisation et performances</div>
          <div class="content">
            <div class="inverted field">
              <label for="TSelect" title="-T">Intensité des tests de version</label>
              <select id="TSelect" class="ui clearable dropdown" name="-T">
                <option>Paranoïaque</option>
                <option>Sournois</option>
                <option>Poli</option>
                <option selected>Normal</option>
                <option>Aggressif</option>
                <option>Dément</option>
              </select>
            </div>

            <div class="inverted field">
              <label>Tailles des groupes d'hôtes à scanner en parallèle</label>
              <div class="two inverted fields">
                <div class="inverted field">
                  <label for="minHostgroupInput" title="--min-hostgroup">Minimum</label>
                  <input id="minHostgroupInput" type="number" min="0" placeholder="Nombre"
                    oninput="maxHostgroupInput.min = minHostgroupInput.value">
                </div>
                <div class="inverted field">
                  <label for="maxHostgroupInput" title="--max-hostgroup">Maximum</label>
                  <input id="maxHostgroupInput" type="number" min="0" placeholder="Nombre"
                    oninput="minHostgroupInput.max = maxHostgroupInput.value">
                </div>
              </div>
            </div>

            <div class="inverted field">
              <label>Parallélisation des paquets de tests</label>
              <div class="two inverted fields">
                <div class="inverted field">
                  <label for="minParallelismInput" title="--min-parallelism">Minimum</label>
                  <input id="minParallelismInput" type="number" min="0" placeholder="Nombre"
                    oninput="maxParallelismInput.min = minParallelismInput.value">
                </div>
                <div class="inverted field">
                  <label for="maxParallelismInput" title="--max-parallelism">Maximum</label>
                  <input id="maxParallelismInput" type="number" min="0" placeholder="Nombre"
                    oninput="minParallelismInput.max = maxParallelismInput.value">
                </div>
              </div>
            </div>

            <div class="inverted field">
              <label>Temps d'aller-retour des paquets de tests</label>
              <div class="three inverted fields">
                <div class="inverted field">
                  <label for="initialRTTNumber" title="--initial-rtt-timeout">Initial</label>
                  <div class="ui right labeled input">
                    <input type="number" min="0" id="initialRTTNumber" placeholder="Durée"
                      oninput="initialRTTHidden.value = initialRTTNumber.value? initialRTTNumber.value+initialRTTUnit.value: ''; maxRTTHidden.initial=initialRTTHidden.value">
                    <select id="initialRTTUnit" class="ui clearable dropdown label"
                      oninput="initialRTTHidden.value = initialRTTNumber.value? initialRTTNumber.value+initialRTTUnit.value: ''">
                      <option value="">ms</option>
                      <option value="s">secondes</option>
                      <option value="m">minutes</option>
                      <option value="h">heures</option>
                    </select>
                  </div>
                  <input id="initialRTTHidden" type="hidden" name="--initial-rtt-timeout">
                </div>
                <div class="inverted field">
                  <label for="minRTTNumber" title="--min-rtt-timeout">Minimum</label>
                  <div class="ui right labeled input">
                    <input type="number" min="0" id="minRTTNumber" placeholder="Durée"
                      oninput="minRTTHidden.value = minRTTNumber.value? minRTTNumber.value+minRTTUnit.value: ''; maxRTTHidden.min=minRTTHidden.value">
                    <select id="minRTTUnit" class="ui clearable dropdown label"
                      oninput="minRTTHidden.value = minRTTNumber.value? minRTTNumber.value+minRTTUnit.value: ''">
                      <option value="">ms</option>
                      <option value="s">secondes</option>
                      <option value="m">minutes</option>
                      <option value="h">heures</option>
                    </select>
                  </div>
                  <input id="minRTTHidden" type="hidden" name="--min-rtt-timeout">
                </div>
                <div class="inverted field">
                  <label for="maxRTTNumber" title="--max-rtt-timeout">Maximum</label>
                  <div class="ui right labeled input">
                    <input type="number" min="0" id="maxRTTNumber" placeholder="Durée"
                      oninput="maxRTTHidden.value = maxRTTNumber.value? maxRTTNumber.value+maxRTTUnit.value: ''; minRTTHidden.max=maxRTTHidden.value">
                    <select id="maxRTTUnit" class="ui clearable dropdown label"
                      oninput="maxRTTHidden.value = maxRTTNumber.value? maxRTTNumber.value+maxRTTUnit.value: ''">
                      <option value="">ms</option>
                      <option value="s">secondes</option>
                      <option value="m">minutes</option>
                      <option value="h">heures</option>
                    </select>
                  </div>
                  <input id="maxRTTHidden" type="hidden" name="--max-rtt-timeout">
                </div>
              </div>
            </div>

            <div class="inverted field">
              <label for="maxRetriesInput" title="--max-retries">Nombre de retransmissions des paquets de tests des
                scans de ports</label>
              <input type="number" min="0" id="maxRetriesInput" name="--max-retries" placeholder="Nombre">
            </div>

            <div class="inverted field">
              <label for="hostTimoutInput" title="--host-timeout">Délai d'expiration du scan d'un hôte trop lent</label>
              <div class="ui right labeled input">
                <input type="number" min="0" id="hostTimoutNumber" placeholder="Durée"
                  oninput="hostTimoutHidden.value = hostTimoutNumber.value? hostTimoutNumber.value+hostTimoutUnit.value: ''">
                <select id="hostTimoutUnit" class="ui clearable dropdown label"
                  oninput="hostTimoutHidden.value = hostTimoutNumber.value? hostTimoutNumber.value+hostTimoutUnit.value: ''">
                  <option value="">ms</option>
                  <option value="s">secondes</option>
                  <option value="m">minutes</option>
                  <option value="h">heures</option>
                </select>
              </div>
              <input id="hostTimoutHidden" type="hidden" name="--host-timeout">
            </div>

            <div class="two inverted fields">
              <div class="inverted field">
                <label for="scanDelayNumber" title="--scan-delay">Délai entre les paquets de tests</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" id="scanDelayNumber" placeholder="Durée"
                    oninput="scanDelayHidden.value = scanDelayNumber.value? scanDelayNumber.value+scanDelayUnit.value: ''">
                  <select id="scanDelayUnit" class="ui clearable dropdown label"
                    oninput="scanDelayHidden.value = scanDelayNumber.value? scanDelayNumber.value+scanDelayUnit.value: ''">
                    <option value="">ms</option>
                    <option value="s">secondes</option>
                    <option value="m">minutes</option>
                    <option value="h">heures</option>
                  </select>
                </div>
                <input id="scanDelayHidden" type="hidden" name="--scan-delay">
              </div>
              <div class="inverted field">
                <label for="maxScanDelay" title="--max-scan-delay">Maximum</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" id="maxScanDelay" placeholder="Durée"
                    oninput="maxRTTHidden.value = maxScanDelay.value? maxScanDelay.value+maxRTTUnit.value: ''">
                  <select id="maxRTTUnit" class="ui clearable dropdown label"
                    oninput="maxRTTHidden.value = maxScanDelay.value? maxScanDelay.value+maxRTTUnit.value: ''">
                    <option value="">ms</option>
                    <option value="s">secondes</option>
                    <option value="m">minutes</option>
                    <option value="h">heures</option>
                  </select>
                </div>
                <input id="maxRTTHidden" type="hidden" name="--max-scan-delay">
              </div>
            </div>
            <div class="inline inverted field">
              <div class="ui toggle inverted checkbox">
                <input id="defeatRSTRateLimitCheckbox" type="checkbox" name="--defeat-rst-ratelimit" />
                <label for="defeatRSTRateLimitCheckbox" title="--defeat-rst-ratelimit">Ignorer les limitations de
                  paquets RST</label>
              </div>
            </div>
          </div>

          <div class="title"><i class="icon dropdown"></i>Évitement de pare-feux/IDS et mystification</div>
          <div class="content">

            <div class="two inverted fields">
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="fInput" type="checkbox" name="-f">
                  <label for="fInput" title="-f">Fragmentation des paquets</label>
                </div>
              </div>
              <div class="inverted field">
                <div class="ui toggle inverted checkbox">
                  <input id="badsumCheckbox" type="checkbox" name="--badsum">
                  <label for="badsumCheckbox" title="--badsum">Checksum incorrect</label>
                </div>
              </div>
            </div>

            <div class="two inverted fields">
              <div class="inverted field">
                <label for="mtuInput" title="--mtu">Taille des paquets</label>
                <input id="mtuInput" type="number" name="--mtu" min="0">
              </div>

              <div class="inverted field">
                <label for="dInput" title="-d">Délai entre les paquets</label>
                <input id="dInput" type="number" name="-d" min="0">
              </div>
            </div>

            <div class="inverted field">
              <label for="dataLengthInput" title="--data-length">Longueur des données</label>
              <input id="dataLengthInput" type="number" name="--data-length" min="0">
            </div>

            <div class="inverted field">
              <label for="DInput" title="-D">Leurre</label>
              <input id="DInput" type="text" name="-D" pattern="[a-zA-Z0-9._,\-]*"
                placeholder="decoy1[,decoy2][,ME],..." title="decoy1[,decoy2][,ME],...">
            </div>

            <div class="inverted field">
              <label for="SInput" title="-S">Usurpation d'adresse IP</label>
              <input id="SInput" type="text" name="-S" pattern="[0-9.]*">
            </div>

            <div class="inverted field">
              <label for="gInput" title="-g">Port source</label>
              <input id="gInput" type="number" name="-g" min="0" max="65535">
            </div>

            <div class="inverted field">
              <label for="ttlInput" title="--ttl">Valeur TTL</label>
              <input id="ttlInput" type="number" name="--ttl" min="0" max="255">
            </div>

            <div class="inverted field">
              <label for="scanDelayInput" title="--scan-delay">Délai entre les scans</label>
              <input id="scanDelayInput" type="number" name="--scan-delay" min="0">
            </div>
          </div>
        </div>

        <div class="field">
          <label for="stylesheetSelect" title="--stylesheet">Affichage des résultats</label>
          <select id="stylesheetSelect" class="ui dropdown" name="--stylesheet" required>
            <option value='lanTable.xsl' selected>Tableau du réseau</option>
            <option value='hostDetails.xsl'>Détails de l'hôte</option>
          </select>
        </div>

        <div class="field">
          <label for="nameInput">Enregistrer sous le nom (optionnel)</label>
          <div class="ui small input">
            <input id="nameInput" type="text" name="name" placeholder="Reseau local" pattern='[0-9a-zA-Z\-_\. ]+'
              title="Caractères autorisés: a-z A-Z 0-9 - _ ." />
          </div>
        </div>

        <button type="submit" class="ui teal submit button">Démarrer</button>
      </form>
    </main>

    <datalist id='targetList'>
      <option value="<?= $_SERVER['REMOTE_ADDR']; ?>/24"></option>
      <option value="<?= $_SERVER['SERVER_NAME']; ?>"></option>
      <?php
      if (file_exists($SCANSDIR)) {
        foreach (scandir($SCANSDIR) as $filename) {
          if (substr($filename, -4) === '.xml') {
            $name = substr($filename, 0, -4);
            $name = str_replace("!", "/", $name);
            echo "              <option value='$name'>$name</option>\n";
          }
        }
      }
      ?>
    </datalist>

    <datalist id='servicesList'>
      <?php
      $services = [];
      foreach ([$DATADIR, $NMAPDIR] as $dir) {
        echo "<!-- nmap_services -->\n";
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

    <script src="script.js"></script>
    <script>
      class TagsInput extends Tagify {
        constructor(input, options = {}, delim = ",") {
          if (!options.delimiters) options.delimiters = " |,"
          if (!options.originalInputValueFormat) options.originalInputValueFormat = tags => tags.map(tag => tag.value).join(delim)
          if (input.list) options.whitelist = Array.from(input.list.options).map(option => option.value)
          super(input, options)
        }
      }

      $(".ui.accordion").accordion()

      $(".ui.clearable.dropdown").dropdown({
        clearable: true
      })
      $(".ui:not(.clearable).dropdown").dropdown({
        clearable: false
      })

      new TagsInput(targetInput, {}, " ")
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
      new TagsInput(scriptInput, {
        enforceWhitelist: true
      })
      new TagsInput(scriptArgsInput, {
        delimiters: ','
      })
      new TagsInput(DInput)

      newScanForm.onsubmit = function (event) {
        if (this.checkValidity()) {
          newScanForm.classList.add("loading")
          $.toast({
            title: 'Scan en cours...',
            message: 'Merci de patienter',
            class: 'info',
            showIcon: 'satellite dish',
            displayTime: 0,
            closeIcon: true,
            position: 'bottom right',
          })
          return true
        } else {
          event.preventDefault()
          this.reportValidity()
        }
      }
    </script>
  </body>

</html>