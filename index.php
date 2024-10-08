<?php
include_once 'config.php';
include_once 'filter_inputs.php';
?>
<!DOCTYPE html>
<html lang="fr">

  <head>
    <meta charset="utf-8" />
    <title>lanScan</title>
    <link rel="icon" href="favicon.ico"/>
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
      <div class="right menu">
        <form class="ui category search item" onsubmit="targetsInputDiv.classList.add('loading')">
          <div id="targetsInputDiv" class="ui icon input">
            <input class="prompt" type="text" id="targetsInput" name="targets" required="" oninput="hiddenInput.value=this.value"
            pattern="[a-zA-Z0-9._\/ \-]+" value="<?=$targets; ?>" placeholder="Scanner un réseau..."
            title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?=$_SERVER['REMOTE_ADDR']; ?>/24 <?=$_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254"/>
            <i class="satellite dish icon"></i>
            <button style="display:none" type="submit" formaction="scan.php" formmethod="get"></button>
          </div>
        </form>
        <form class="item" method="get" action="scan-options.php">
          <input id="hiddenInput" type="hidden" name="targets" value="<?=$targets; ?>"/>
          <button class="ui teal submit button" type="submit">Options</button>
        </form>
      </div>
    </nav>

    <main class="ui main container">
        <div class="ui large relaxed card">
          <div class="content">
            <div class="header">Précédents scans</div>
            <div class="ui divided link list">
<?php
if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}
foreach (scandir($SCANS_DIR) as $scan) {
    if (substr($scan, -4) == '.xml') {
        $targets = str_replace('!', '/', substr_replace($scan, '', -4));
        echo "<a class='item' href='scan.php?targets=".urlencode($targets)."'>$targets</a>\n";
    }
}
?>
          </div>
        </div>
      </div>
    </main>
  </body>

</html>