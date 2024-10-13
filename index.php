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
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
  <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <form>
    <nav class="ui inverted teal fixed menu">
      <button class="ui teal button item" type="submit" formmethod="get" formaction=".">
        lan<?php include 'logo.svg'; ?>can
      </button>
      <div class="right menu">
        <div class="ui category search item">
          <div id="targetsInputDiv" class="ui icon input">
            <input class="prompt" type="text" id="targetsInput" name="targets" oninput="hiddenInput.value=this.value" required
              pattern="[a-zA-Z0-9._\/ \-]+" value="<?= $targets; ?>" placeholder="Scanner un réseau..."
              title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
            <i class="satellite dish icon"></i>
          </div>
<?php
foreach($inputs as $name => $value) {
  echo "          <input type='hidden' name='$name' value='$value'/>\n";
}
?>
          <button style="display: none;" type="submit" formmethod="get" formaction="scan.php" onsubmit="targetsInputDiv.classList.add('loading')"></button>
          <button class="ui teal icon submit button" type="submit" formmethod="get" formaction="options.php" onclick="targetsInput.required=false">
            <i class="sliders horizontal icon"></i>
          </button>
        </div>
      </div>
    </nav>
  </form>

  <main class="ui main container">
    <h1 class="ui header">Précédents scans</h1>
    <div class="ui large relaxed card">
      <div class="content">
        <div class="ui divided link list">
<?php
if (!file_exists($SCANS_DIR)) {
  mkdir($SCANS_DIR);
}
foreach (scandir($SCANS_DIR) as $filename) {
  if (substr($filename, -4) == '.xml') {
    $name = str_replace('!', '/', substr_replace($filename, '', -4));
    echo "<a class='item' href='".htmlentities("$SCANS_DIR/$filename", ENT_QUOTES)."'>$name</a>\n";
  }
}
?>
        </div>
      </div>
    </div>
  </main>
</body>

</html>