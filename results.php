<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lanScan - <?=$site?></title>
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
        .ui.dropdown, .ui.dropdown .menu > .item {
          font-size: .85714286rem;
        }
        .ui.mini button > .detail {
            margin-left: .1em;
        }
        .content {
            display: flex;
            align-items: baseline;
            gap: .5em;
        }
    </style>
    <script>
        onload = function (event) {
            $('.ui.dropdown').dropdown()
        }
    </script>
  </head>
  <body>
    <header class="ui fixed blue inverted menu">
      <a href="." class="header item">lan<img id="logo" src="logo.svg" alt="S"/>can</a>
      <div class="item"><?=$site?></div>
    </header>
    <div class="ui main container">
        <p><?=$scan->runstats->finished["summary"]?></p>
<?php foreach($conf as $conf_groupname => $conf_hosts) { ?>
        <h1 class="ui header"><?=$conf_groupname?></h1>
        <div class="ui mini cards">
<?php
        if ($conf_hosts) foreach($conf_hosts as $conf_address => $conf_services) {
            echo "            <!-- $conf_address -->\n";
            $scan_host = $scan->xpath("host[hostnames/hostname/@name='$conf_address' or address/@addr='$conf_address']")[0];
            $address = count($scan_host->xpath("hostnames/hostname/@name")) ? $scan_host->xpath("hostnames/hostname/@name")[0] : $scan_host->xpath("address/@addr")[0];
            if ($scan_host->status["state"] =="up") {
?>
            <div class="ui card">
                <div class="content">
                    <div class="ui green empty circular label"></div>
                    <div class="meta"><?=$scan_host->address["addr"]?></div>
                    <div class="header" title="<?=strtok($scan_host->hostnames->hostname["name"], ".")?>"><?=strtok($scan_host->hostnames->hostname["name"], ".")?></div>
                </div>
                <div class="ui inverted primary centered wrapped wrapping bottom attached mini menu">
<?php
                if ($conf_services) foreach($conf_services as $conf_service) {
                    $scan_service = $scan_host->xpath("ports/port[service/@name='$conf_service' or @portid='$conf_service']")[0];
                    switch($scan_service->state["state"]) {
                        case "open": $state = "primary"; break;
                        case "closed": $state = "red disabled"; break;
                        default: $state = "yellow";
                    }
                    switch($scan_service->service['name']) {
                        case "microsoft-ds":
                        case "netbios-ssn":
                            $shares = $scan_host->xpath("hostscript/script[@id='smb-enum-shares']/table[not(contains(@key, '$'))]");
                            if (count($shares)) {
?>
                    <div class="ui dropdown <?=$state?> item">
                        <?=$scan_service->service['name']?><small>:<?=$scan_service['portid']?></small>
                        <i class="dropdown icon"></i>
                        <div class="menu">
<?php
                                foreach($shares as $share) {
?>
                            <a class='item' href='file:////$address/<?=$share['key']?>'><?=$share['key']?></a>
<?php
                                }
?>
                        </div>
                    </div>
<?php
                            } else {
?>
                    <div class="ui <?=$state?> disabled item" disabled><?=$scan_service->service['name']?><small>:<?=$scan_service['portid']?></small></div>
<?php
                            }
                        break;
                        case "telnet":
                        case "ftp":
                        case "ssh":
                        case "http":
                        case "https":
?>
                    <a href="<?=$scan_service->service['name']?>://<?=$address?>:<?=$scan_service['portid']?>" class="ui <?=$state?> item"><?=$scan_service->service['name']?><small>:<?=$scan_service['portid']?></small></a>
<?php
                        break;
                        case "ms-wbt-server":
?>
                    <a href="rdp.php?v=<?=$address?>:<?=$scan_service['portid']?>" class="ui <?=$state?> item">rdp<small>:<?=$scan_service['portid']?></small></a>
<?php
                        break;
                        default:
?>
                    <div class="ui <?=$state?> disabled item" disabled><?=$scan_service->service['name']?><small>:<?=$scan_service['portid']?></small></div>
<?php
                    }
                }
?>
                </div>
            </div>
<?php
        } else {
?>
            <div class="ui red card">
                <div class="content">
                    <div class="ui red empty circular label"></div>
                    <div class="meta"><?=$scan_host->address["addr"]?></div>
                    <div class="header" title="<?=$scan_host->hostnames->hostname["name"]?>"><?=strtok($scan_host->hostnames->hostname["name"], ".")?></div>
                </div>
                <div class="ui inverted red centered wrapped wrapping bottom attached mini menu"></div>
            </div>
<?php
        }
    }
?>
        </div>
<?php
}
?>
    </body>
</html>
