<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lanScan</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
    <style>
        #logo {
          margin: 0 -.4rem 0 0;
        }
        .main.container {
          margin-top: 5em;
        }
    </style>
  </head>
  <body>
    <header class="ui fixed centered blue inverted menu">
      <div class="header item">lan<img id="logo" src="logo.svg" alt="S"/>can</div>
    </header>
    <div class="ui main text container">
      <div class="ui link selection list">
<?php foreach (scandir("./site") as $file) {
    if (strrpos($file, ".xml")) {
      $site = str_replace(".xml", "", $file);
      if (file_exists("scans/$site.xml")) {
        echo "          <a href='site/$site.xml' class='item'>$site</a>\n";
      }
    }
} ?>
      </div>
    </div>
  </body>
</html>