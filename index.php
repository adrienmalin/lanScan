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
    body > .grid {
      height: 100%;
    }

    .logo {
      margin-right: 0 !important;
    }
  </style>
</head>

<body>

  <div class="ui middle aligned center aligned grid inverted">
    <div class="column" style="max-width: 450px;">
      <h2 class="ui inverted teal fluid image header logo">
        lan<?php include 'logo.svg'; ?>can
      </h2>
      <form id="scanForm" class="ui large form initial inverted" action="scan.php" method="get">
        <div class="ui left aligned stacked segment inverted">
          <h4 class="ui header">Découvrir ou superviser un réseau</h4>
          <div class="inverted field">
            <select id="lanSelect" name="lan" class="search clearable selection dropdown">
              <option value=""><?= $_SERVER['REMOTE_ADDR']; ?>/24</option>
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
            </select>
          </div>
          <div class="ui error message"></div>
          <button type="submit" class="ui fluid large teal labeled icon submit button">
            <i class="satellite dish icon"></i>Scanner
          </button>
        </div>
      </form>

      <div class="ui inverted segment">
        <a href="options.php">Options avancées</a>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.js"></script>
  <script>
    $('#lanSelect').dropdown({allowAdditions: true, clearable: true})

    $('#scanForm').form({
      fields: {
        lan: {
          identifier: 'lanSelect',
          rules: [{
            type: 'regExp',
            value: /[a-zA-Z0-9._\/ \-]+/,
            prompt: "Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.<br/>Exemples : <?= $_SERVER['REMOTE_ADDR']; ?>/24 <?= $_SERVER['SERVER_NAME']; ?> 10.0-255.0-255.1-254"
          }]
        }
      }
    });

    scanForm.onsubmit = function(event) {
      if (this.checkValidity()) {
        scanForm.classList.add("loading")
        $.toast({
            title      : 'Scan en cours...',
            message    : 'Merci de patienter',
            class      : 'info',
            showIcon   : 'satellite dish',
            displayTime: 0,
            closeIcon  : true,
            position   : 'bottom right',
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