<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <title>lanScan</title>
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
  <style>
    body {
      background-color: #1b1c1d;
    }

    body > .grid {
      height: 100%;
    }

    .logo { 
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo svg {
      width: 2.5em;
      height: 2.5em;
      fill: currentColor;
      margin: -.4em;
    }
  </style>
</head>

<body>

  <div class="ui middle aligned center aligned grid inverted">
    <div class="column" style="max-width: 450px;">
      <h2 class="ui inverted teal header logo">
        lan<?php include 'logo.svg'; ?>can
      </h2>
      <form id="scanForm" class="ui large form initial inverted">
        <div class="ui left aligned stacked segment inverted">
          <h4 class=""ui header">Découvrir ou superviser un réseau</h4>
          <div class="inverted field">
            <select id="lanSelect" name="lan" class="search clearable selection dropdown">
              <option value="">Nouveau réseau</option>
              <option value="10.92.8.0/24">10.92.8.0/24</option>
              <option value="10.93.8.0/24">10.93.8.0/24</option>
              <option value="10.94.8.0/24">10.94.8.0/24</option>
            </select>
          </div>
          <div class="ui error message"></div>
          <button type="submit" class="ui fluid large teal labeled icon submit button">
            <i class="satellite dish icon"></i>Scanner
          </button>
        </div>
      </form>

      <div class="ui inverted segment">
        <a href="#">Options avancées</a>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.js"></script>
  <script>
    $('#lanSelect').dropdown({allowAdditions: true, clearable: true})

    lanSelect.checkValidity = () => /[a-zA-Z0-9._\/ \-]+/.test(lanSelect.value)

    scanForm.onsubmit = function(event) {
      if (!scanForm.checkValidity()) {
        event.preventDefault()
        this.reportValidity()
        }
      }

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
    
  </script>

</body>

</html