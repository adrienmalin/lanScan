<?php include_once "config.php"; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <title>lanScan</title>
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
  <link rel="stylesheet" type="text/css" href="style.css" />
  <style>
    body {
      background-image: url(bg.jpg);
      background-size: cover;
    }

    body>.grid {
      height: 100%;
    }

    .logo {
      margin-right: 0 !important;
    }
  </style>
</head>

<body>

  <div class="ui center aligned inverted grid">
    <div class="column" style="max-width: 450px;">
      <h2 class="ui inverted teal fluid image header logo" style="margin-top: 15vh">
        lan<?php include 'logo.svg'; ?>can
      </h2>

      <?php if (isset($errorMessage)) { ?>
        <div class="ui negative message">
          <i class="close icon"></i>
          <div class="header">Erreur</div>
          <p><?= $errorMessage ?></p>
        </div>
      <?php } ?>

      <form id="scanForm" class="ui large form initial inverted" action="scan.php" method="get">
        <div class="ui left aligned stacked segment inverted">
          <h4 class="ui header">Découvrir ou superviser un réseau</h4>
          <div class="inverted field">
            <div class="ui large input">
              <input id="nameInput" type="text" name="lan" placeholder="<?= $_SERVER['REMOTE_ADDR']; ?>" list="targetsList" pattern="[a-zA-Z0-9._\/ \-]+" required
                title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254" />
            </div>
          </div>
          <div class="field">
            <label for="nameInput">Enregistrer sous le nom (optionnel)</label>
            <div class="ui small input">
              <input id="nameInput" type="text" name="name" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|@?]+'
                title="Caractères interdits :  &lt;&gt;:&quot;\/|@?">
            </div>
          </div>
          <div class="ui error message"></div>
          <button type="submit" class="ui fluid large teal labeled icon submit button">
            <i class="satellite dish icon"></i>Scanner
          </button>
          <div class="ui divider"></div>
          <a href="options.php">Options avancées</a>
        </div>
      </form>

      <?php if (file_exists($SCANSDIR)) { ?>
        <div class="ui inverted fluid card" style="margin-top: 10vh">
          <div class="content">
            <div class="header">Scans enregistrés</div>
          </div>
          <table class="ui very basic inverted table">
            <tbody>
            <?php
            foreach (scandir($SCANSDIR) as $filename) {
              if (substr($filename, -4) == '.xml') {
                $name = str_replace('!', '/', substr_replace($filename, '', -4));
                echo "<tr><td class='selectable'><a href='$SCANSDIR/" . rawurlencode($filename) . "'>$name</a></td><td><a href='rescan.php?name=$name' class='ui mini labelled button'><i class='sync icon'></i>Rescanner</a></td></tr>\n";
              }
            }
          }
            ?>
            </tbody>
          </table>
        </div>
    </div>
  </div>

  <datalist id='targetsList'>
    <option value="<?= $_SERVER['REMOTE_ADDR']; ?>/24"></option>
    <option value="<?= $_SERVER['SERVER_NAME']; ?>"></option>
  </datalist>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
  <script>
    scanForm.onsubmit = function(event) {
      if (this.checkValidity()) {
        scanForm.classList.add("loading")
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

</html