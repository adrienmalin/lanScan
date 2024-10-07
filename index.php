<?php
include_once 'config.php';

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[\da-zA-Z.:\/_ -]+$/'],
]);
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
      <div class="right menu">
          <iconsearch class="ui right aligned search category item">
              <div class="ui icon input">
                  <form id="newScanForm" class="ui form" method="get" action="scan.php">
                      <input class="prompt" type="text" name="targets" placeholder="Scanner un réseau..." required="" autocomplete="off" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemple: <?=$_SERVER['REMOTE_ADDR']; ?>/24 <?=$_SERVER['SERVER_NAME']; ?>" pattern="[a-zA-Z0-9._\/ \-]+" value="<?=$targets; ?>" />
                  </form>
                  <i class="satellite dish icon"></i>
              </div>
              <div class="results"></div>
          </iconsearch>
      </div>
    </nav>

    <main class="ui main container">
      <h1 class="ui header">Scans</h1>
        <ul class="ui large relaxed link list">
<?php
if (!file_exists($SCANS_DIR)) {
    mkdir($SCANS_DIR);
}
foreach (scandir($SCANS_DIR) as $scan) {
    if (substr($scan, -9) == '_init.xml') {
        $targets = str_replace('!', '/', substr_replace($scan, '', -9));
        echo "<li><a class='item' href='scan.php?targets=".urlencode($targets)."'>$targets</a></li>\n";
    }
}
?>
      </ul>
    </main>
  </body>

</html>