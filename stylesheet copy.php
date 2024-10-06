<?php
$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[^<>:"\/|@?]+$/'],
]);

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[\da-zA-Z.\/_ -]+$/'],
]);

$basedir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['REQUEST_URI']);

$firstScan = "$basedir/scans/$name.xml";
?>
<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema" version="2.0">
    <xsl:output method="html" encoding="UTF-8"/>
    <xsl:output indent="yes"/>
    <xsl:strip-space elements='*'/>
    <xsl:variable name="name"><?=$name ?></xsl:variable>
    <xsl:variable name="basedir"><?=$basedir ?></xsl:variable>
    <xsl:variable name="firstScan" select="document('<?=$firstScan ?>')"/>
    <xsl:variable name="currentScan" select="nmaprun"/>

    <xsl:apply-templates select="$firstScan/nmaprun"/>

    <xsl:template match="nmaprun">
        <html lang="fr">
            <head>
                <meta charset="utf-8"/>
                <title><xsl:value-of select="$name"/> - lanScan</title>
                <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css"/>
                <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
                <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/fh-4.0.1/r-3.0.3/datatables.css" rel="stylesheet"/>
                <link href="{$basedir}/style.css" rel="stylesheet" type="text/css"/>
                <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
                <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
                <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/fh-4.0.1/r-3.0.3/datatables.js"></script>
            </head>

            <body>
                <nav class="ui inverted teal fixed menu">
                    <a class="header item" href="{$basedir}">
                    lan<?php include 'logo.svg'; ?>can
                    </a>
                    <div class="right menu">
                        <div class="item">
                            <button class="ui icon teal button" onclick="$('#newScanForm').modal('show')">
                                <i class="satellite dish icon"></i>Nouveau scan
                            </button>
                        </div>
                    </div>
                </nav>
                <form id="newScanForm" class="ui modal form" method="get" action="{$basedir}/scan.php">
                    <i class="close icon"></i>
                    <div class="header">Nouveau scan</div>
                    <div class="content">
                        <div class="field">
                            <label for="nameInput">Nom</label>
                            <input id="nameInput" type="text" name="name" placeholder="Réseau local" pattern='[^&lt;&gt;:&quot;\\\/\|?]+' required="" title='Nom de fichier valide (ne contenant pas les caractères &lt;&gt;:&quot;\/|?)' value="<?= htmlspecialchars($name); ?>"/>
                        </div>
                        <div class="field">
                            <label for="targetsInput">Cibles</label>
                            <input id="targetsInput" type="text" name="targets" placeholder="scanme.nmap.org 192.168.0.0/24" required="" title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemple: scanme.nmap.org microsoft.com/24 192.168.0.1 10.0-255.0-255.1-254" pattern="[a-zA-Z0-9._\/ \-]+" value="<?= htmlspecialchars($targets); ?>"/>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="submit" class="ui teal right button">Démarrer</button>
                    </div>
                </form>
                <main class="ui main container">
                    <xsl:choose>
                        <xsl:when test="$currentScan/runstats/finished/@errormsg">
                            <div class="ui negative icon message">
                                <i class="exclamation triangle icon"></i>
                                <div class="content">
                                    <div class="header" style="text-transform: capitalize"><xsl:value-of select="$currentScan/runstats/finished/@exit"/></div>
                                    <p><xsl:value-of select="$currentScan/runstats/finished/@errormsg"/></p>
                                </div>
                            </div>
                        </xsl:when>
                        <xsl:when test="$currentScan/runstats/finished/@summary">
                            <div class="ui icon message">
                                <i class="sitemap icon"></i>
                                <div class="content">
                                    <div class="header" style="text-transform: capitalize"><xsl:value-of select="$currentScan/runstats/finished/@exit"/></div>
                                    <p><xsl:value-of select="$currentScan/runstats/finished/@summary"/></p>
                                </div>
                            </div>
                        </xsl:when>
                    </xsl:choose>
                    <h1 class="ui header"><?=$name; ?></h1>
                    <table id="table-overview" style="width:100%" role="grid" class="ui celled sortable padded tiny table">
                        <thead>
                            <tr>
                                <th>Etat</th>
                                <th>Adresse IP</th>
                                <th>Nom</th>
                                <th class="ten wide">Services</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:apply-templates select="host"/>
                        </tbody>
                    </table>
                </main>
                <script>
                    tagify = new Tagify(targetsInput, {
                        pattern: /[a-zA-Z\d.-_/]+/,
                        delimiters: " ",
                        originalInputValueFormat: tags => tags.map(tag => tag.value).join(' ')
                    })
                    
                    newScanForm.onsubmit = function(event) {
                        if (this.checkValidity()) return true

                        event.preventDefault()
                        this.reportValidity()
                        newScanSubmitButton.innerHTML = "<div class='ui active inline inverted loader'></div>"
                    }
                    
                    DataTable.ext.type.detect.unshift(function (d) {
                        return /[\d]+\.[\d]+\.[\d]+\.[\d]+/.test(d)
                            ? 'ipv4-address'
                            : null;
                    });
                     
                    DataTable.ext.type.order['ipv4-address-pre'] = function (ipAddress) {
                        [a, b, c, d] = ipAddress.split(".").map(s => Number(s))
                        return 16777216*a + 65536*b + 256*c + d;
                    };

                    $('#table-overview').DataTable({
                        buttons: ['copy', 'excel', 'pdf'],
                        fixedHeader: true,
                        lengthMenu: [
                            [256, 512, 1024, 2048, -1],
                            [256, 512, 1024, 2048, "All"]
                        ],
                        responsive: true
                    })
                    
                    $('.ui.dropdown').dropdown()
                </script>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="host">
        <xsl:variable name="firstScanHost" select="."/>
        <xsl:variable name="currentScanHost" select="$currentScan/host[address/@addr=$firstScanHost/address/@addr]"/>
        <tr>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="$currentScanHost/status/@state='up'">positive</xsl:when>
                    <xsl:otherwise>negative</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <td>
                <xsl:value-of select="$currentScanHost/status/@state"/>
            </td>
            <td>
                <xsl:value-of select="$currentScanHost/address/@addr"/>
            </td>
            <td>
                <b><xsl:value-of select="$currentScanHost/hostnames/hostname/@name"/></b>
            </td>
            <td>
                <xsl:apply-templates select="$firstScanHost/ports/port">
                    <xsl:with-param name="hostAddress">
                        <xsl:choose>
                            <xsl:when test="$currentScanHost/hostnames/hostname/@name">
                                <xsl:value-of select="$currentScanHost/hostnames/hostname/@name"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="address/@addr"/>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:with-param>
                </xsl:apply-templates>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="port">
        <xsl:param name="hostAddress"/>
        
        <a class="ui label" target="_blank">
            <xsl:attribute name="class">
                <xsl:text>ui label </xsl:text>
                <xsl:choose>
                    <xsl:when test="script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                    <xsl:when test="script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
                    <xsl:when test="script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                    <xsl:when test="state/@state='open'">green</xsl:when>
                    <xsl:when test="state/@state='filtered'">orange disabled</xsl:when>
                    <xsl:otherwise>red disabled</xsl:otherwise>
                </xsl:choose>
                <xsl:choose>
                    <xsl:when test="(service/@name='microsoft-ds' or service/@name='netbios-ssn') and ../../hostscript/script[@id='smb-shares-size']/table"> mini dropdown button share-size</xsl:when>
                    <xsl:otherwise> small</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https'">
                <xsl:attribute name="href">
                    <xsl:value-of select="service/@name"/>://<xsl:value-of select="$hostAddress"/>:<xsl:value-of select="@portid"/>
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="service/@name='ms-wbt-server'">
                <xsl:attribute name="href">
                    <xsl:value-of select="$basedir"/>/rdp.php?v=<xsl:value-of select="$hostAddress"/>:<xsl:value-of select="@portid"/>
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="(service/@name='microsoft-ds' or service/@name='netbios-ssn') and ../../hostscript/script[@id='smb-shares-size']/table">
                <xsl:attribute name="style">
                    <xsl:for-each select="../../hostscript/script[@id='smb-shares-size']/table">
                        <xsl:sort select="elem[@key='FreeSize'] div elem[@key='TotalSize']" order="ascending"/>
                        <xsl:if test="position()=1">
                            <xsl:text>--free: </xsl:text>
                            <xsl:value-of select="elem[@key='FreeSize']"/>
                            <xsl:text>; --total: </xsl:text>
                            <xsl:value-of select="elem[@key='TotalSize']"/>
                        </xsl:if>
                    </xsl:for-each>
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="service/@name"/>
            <div class="detail">
                <xsl:choose>
                    <xsl:when test="@protocol='udp'">U:</xsl:when>
                    <xsl:otherwise>:</xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="@portid"/>
            </div>
            <xsl:if test="(service/@name='microsoft-ds' or service/@name='netbios-ssn') and ../../hostscript/script[@id='smb-shares-size']/table">
                <i class="dropdown icon"></i>
                <div class="menu">
                    <xsl:apply-templates select="../../hostscript/script[@id='smb-shares-size']/table">
                        <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    </xsl:apply-templates>
                </div>
            </xsl:if>
        </a>
    </xsl:template>

    <xsl:template match="table">
        <xsl:param name="hostAddress"/>
        <a class="item share-size" href="file://///{$hostAddress}/{@key}" target="_blank" rel="noopener noreferrer" style="--free: {elem[@key='FreeSize']}; --total: {elem[@key='TotalSize']}">
            <xsl:value-of select="@key"/>
        </a>
    </xsl:template>
</xsl:stylesheet>