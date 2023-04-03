<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lanScan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <style>
        .navbar-brand img {
            margin: 0 -8px 0 0;
        }
        .card-body {
            padding: .4rem;
        }
    </style>
  </head>
  <body>
    <header>
        <nav class="navbar navbar-fixed-top navbar-nav navbar-dark bg-primary p-0 mb-3">
            <div class="navbar-brand">lan<img src="logo.svg" alt="S"/>can</div>
        </nav>
    </header>
    <div class="container">
      <div class="list-group">
<?php foreach (scandir("./scans") as $file) {
    if (strrpos($file, ".yaml")) {
        $site = str_replace(".yaml", "", $file);
        echo "          <a href='?site=$site' class='list-group-item list-group-item-action'>$site</a>\n";
    }
} ?>
      </div>
    </div>
  </body>
</html>