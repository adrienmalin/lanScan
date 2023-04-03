<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300">
    <title>lanScan - <?=$site?></title>
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
            <a href="." class="navbar-brand">lan<img src="logo.svg" alt="S"/>can</a>
        </nav>
    </header>
    <div class="container">
        <div class="mb-3">
            <h1><?=$site?></h1>
            <?=$scan->runstats->finished["summary"]?>
        </div>
<?php foreach($conf as $conf_groupname => $conf_hosts) { ?>
            <h2><?=$conf_groupname?></h2>
            <div class="row row-cols-1 g-2">
<?php
        foreach($conf_hosts as $conf_address => $conf_services) {
            echo "                <!-- $conf_address -->\n";
            $scan_host = $scan->xpath("host[hostnames/hostname/@name='$conf_address' or address/@addr='$conf_address']")[0];
            $short_name = preg_match("/^[\d\.]+$/", $conf_address) ? $conf_address : strtok($conf_address, ".")." <small>(".$scan_host->address["addr"].")</small>";
            $address = count($scan_host->xpath("hostnames/hostname/@name")) ? $scan_host->xpath("hostnames/hostname/@name")[0] : $scan_host->xpath("address/@addr")[0];
            if ($scan_host->status["state"] =="up") {
?>
                    <div class="col col-sm-6 col-md-4 col-lg-3 mb-2">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-text" title="<?=$scan_host->hostnames->hostname["name"]?>"><?=$short_name?></div>
<?php
               foreach($conf_services as $conf_service) {
                    $scan_service = $scan_host->xpath("ports/port[service/@name='$conf_service' or @portid='$conf_service']")[0];
                    $state = $scan_service->state["state"] == "open" ? "text-bg-primary" : "text-bg-danger";
                    switch($scan_service->service['name']) {
                        case "microsoft-ds":
                        case "netbios-ssn":
                            $shares = $scan_host->xpath("hostscript/script[@id='smb-enum-shares']/table[not(contains(@key, '$'))]");
                            if (count($shares)) {
?>
                                    <div class="dropdown">
                                        <button class="badge rounded-pill dropdown-toggle <?=$state?>" dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?=$scan_service->service['name']?></button>
                                        <ul class="dropdown-menu">
<?php
                                foreach($shares as $share) {
?>
                                            <li><a class='dropdown-item' href='file:////$address/<?=$share['key']?>'><?=$share['key']?></a></li>
<?php
                                }
?>
                                        </ul>
                                    </div>
<?php
                            } else {
?>
                                    <span title=":<?=$scan_service['portid']?>" class="badge rounded-pill <?=$state?>"><?=$scan_service->service['name']?></span>
<?php
                            }
                        break;
                        case "telnet":
                        case "ftp":
                        case "ssh":
                        case "http":
?>
                                    <a href="<?=$scan_service->service['name']?>://<?=$address?>:<?=$scan_service['portid']?>" class="badge rounded-pill <?=$state?>"><?=$scan_service->service['name']?></a>
<?php
                        break;
                        case "https":
                        case "pve":
                        case "arkeia":
?>
                                    <a href="https://<?=$address?>:<?=$scan_service['portid']?>" class="badge rounded-pill <?=$state?>"><?=$scan_service->service['name']?></a>
<?php
                        break;
                        case "ms-wbt-server":
?>
                                    <a href="rdp.php?v=<?=$address?>:<?=$scan_service['portid']?>" class="badge rounded-pill <?=$state?>"><?=$scan_service->service['name']?></a>
<?php
                        break;
                        default:
?>
                                    <span title=":<?=$scan_service['portid']?>" class="badge rounded-pill <?=$state?>"><?=$scan_service->service['name']?></span>
<?php
                    }
                }
?>
                                </div>
                            </div>
                        </div>
<?php
        } else {
?>
                <div class="col col-sm-6 col-md-4 col-lg-3 mb-2">
                    <div class="card h-100 text-bg-danger">
                        <div class="card-body">
                            <div class="card-text" title="<?=$scan_host->hostnames->hostname["name"]?>"><?=$short_name?></div>
                        </div>
                    </div>
                </div>
<?php
        }
    }
?>
            </div>
<?php
}
?>
        </div>
    </body>
</html>
