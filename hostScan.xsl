<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:output method="html" encoding="UTF-8"/>
    <xsl:output indent="yes"/>
    <xsl:strip-space elements='*'/>

    <xsl:param name="saveAs" select=""/>
    <xsl:param name="scansDir" select="scans"/>
    <xsl:param name="compareWith" select=""/>
    <xsl:variable name="nextCompareWith">
        <xsl:choose>
            <xsl:when test="$saveAs"><xsl:value-of select="$saveAs"/></xsl:when>
            <xsl:when test="$compareWith"><xsl:value-of select="$compareWith"/></xsl:when>
            <xsl:otherwise></xsl:otherwise>
        </xsl:choose>
    </xsl:variable>
    <xsl:variable name="current" select="./nmaprun"/>
    <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'),'&quot;')"/>
    <xsl:variable name="basedir" select="concat($stylesheetURL, '/..')"/>
    <xsl:variable name="init" select="document($compareWith)/nmaprun"/>

    <xsl:template match="nmaprun">
        <xsl:variable name="targets" select="substring-after(@args, '.xml ')"/>
        <xsl:variable name="refreshURL">
            <xsl:value-of select="$basedir"/>
            <xsl:text>/scan.php?targets=</xsl:text>
            <xsl:value-of select="$targets"/>
            <xsl:text>&amp;</xsl:text>
            <xsl:call-template name="optionsList">
                <xsl:with-param name="argList" select="substring-before(substring-after(@args, ' -'), ' -oX')"/>
                <xsl:with-param name="asURL" select="true()"/>
            </xsl:call-template>
            <xsl:text>compareWith=</xsl:text>
            <xsl:value-of select="$nextCompareWith"/>
        </xsl:variable>
        
        <html lang="fr">
            <head>
                <meta charset="utf-8"/>
                <meta http-equiv="refresh">
                    <xsl:attribute name="content">
                        <xsl:text>60;URL=</xsl:text>
                        <xsl:value-of select="$refreshURL"/>
                    </xsl:attribute>
                </meta>
                <title>
                    <xsl:text>lanScan - </xsl:text>
                    <xsl:value-of select="$targets"/>
                </title>
                <link rel="icon" href="{$basedir}/favicon.ico"/>
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
                    <a class="ui teal button item" href=".">
                        <xsl:text>lan</xsl:text>
                        <svg class="logo" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs206"/><g id="g998" transform="matrix(0,0.04687491,-0.04687491,0,24,2.2682373e-5)"><g id="g147"><g id="g145"><path d="m 322.065,92.046 c -46.24,0 -83.851,37.619 -83.851,83.857 v 168.712 c 0,25.224 -21.148,45.745 -46.372,45.745 -25.224,0 -46.372,-20.521 -46.372,-45.745 V 199.464 h -38.114 v 145.151 c 0,46.24 38.246,83.859 84.486,83.859 46.24,0 84.486,-37.619 84.486,-83.859 V 175.903 c 0,-25.223 20.514,-45.743 45.737,-45.743 25.223,0 45.737,20.521 45.737,45.743 v 134.092 h 38.114 V 175.903 c 0,-46.239 -37.611,-83.857 -83.851,-83.857 z" id="path143"/></g></g><g id="g153"><g id="g151"><path d="M 144.198,0 H 108.625 C 98.101,0 89.568,8.746 89.568,19.271 c 0,1.157 0.121,2.328 0.318,3.598 h 73.052 c 0.197,-1.27 0.318,-2.441 0.318,-3.598 C 163.256,8.746 154.723,0 144.198,0 Z" id="path149"/></g></g><g id="g159"><g id="g157"><path d="m 420.183,486.591 h -71.731 c -0.626,2.541 -0.978,4.077 -0.978,6.176 0,10.525 8.532,19.234 19.057,19.234 h 35.573 c 10.525,0 19.057,-8.709 19.057,-19.234 0,-2.098 -0.352,-3.635 -0.978,-6.176 z" id="path155"/></g></g><g id="g165"><g id="g163"><rect x="87.027" y="41.925999" width="80.040001" height="138.481" id="rect161"/></g></g><g id="g171"><g id="g169"><rect x="344.93301" y="329.052" width="80.040001" height="138.481" id="rect167"/></g></g><g id="g173"></g><g id="g175"></g><g id="g177"></g><g id="g179"></g><g id="g181"></g><g id="g183"></g><g id="g185"></g><g id="g187"></g><g id="g189"></g><g id="g191"></g><g id="g193"></g><g id="g195"></g><g id="g197"></g><g id="g199"></g><g id="g201"></g></g></svg>
                        <xsl:text>can</xsl:text>
                    </a>
                    <form class="right menu">
                        <div class="ui category search item">
                            <div id="lanInputDiv" class="ui icon input">
                            <input class="prompt" type="text" id="lanInput" name="lan" oninput="hiddenInput.value=this.value" required=""
                                pattern="[a-zA-Z0-9._\/ \-]+" value="{$targets}" placeholder="Scanner un réseau..."
                                title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: 192.168.1.0/24 scanme.nmap.org 10.0-255.0-255.1-254"/>
                                <i class="satellite dish icon"></i>
                            </div>
                            <input type="hidden" name="compareWith" value="{$nextCompareWith}"/>
                            <button style="display: none;" type="submit" formmethod="get" formaction="{$basedir}/scan.php" onsubmit="lanInputDiv.classList.add('loading')"></button>
                        </div>
                        <div class="item">
                            <button class="ui teal icon submit button" type="submit" formmethod="get" formaction="{$basedir}/options.php" onclick="targetsInput.required=false">
                                <i class="sliders horizontal icon"></i>
                            </button>
                        </div>
                        <div class="item">
                            <a class="ui teal icon button" href="{$refreshURL}">
                                <i class="sync icon"></i>
                            </a>
                        </div>
                        <div class="item">
                            <a class="ui teal icon button" href="https://nmap.org/man/fr/index.html" target="_blank">
                            <i class="question circle icon"></i>
                            </a>
                        </div>
                    </form>
                </nav>

                <main class="ui main container">
                    <h1 class="ui header"><xsl:value-of select="$targets"/></h1>

                    <table id="scanResultsTable" style="width:100%" role="grid" class="ui sortable small table">
                        <thead>
                            <tr>
                                <th>Etat</th>
                                <th>Adresse IP</th>
                                <th>Nom</th>
                                <th class="ten wide">Services</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:apply-templates select="host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]"/>
                        </tbody>
                    </table>
                </main>
                
                <footer class="ui footer segment">
                  lanScan est basé sur <a href="https://nmap.org/" target="_blank">Nmap</a>
                </footer>

                <script>
DataTable.ext.type.detect.unshift(function (d) {
    return /[\d]+\.[\d]+\.[\d]+\.[\d]+/.test(d)
        ? 'ipv4-address'
        : null;
});
    
DataTable.ext.type.order['ipv4-address-pre'] = function (ipAddress) {
    [a, b, c, d] = ipAddress.split(".").map(Number)
    return 16777216*a + 65536*b + 256*c + d;
};

var table = $('#scanResultsTable').DataTable({
    buttons: ['copy', 'excel', 'pdf'],
    fixedHeader: true,
    lengthMenu: [
        [256, 512, 1024, 2048, -1],
        [256, 512, 1024, 2048, "All"]
    ],
    responsive: true,
})
table.order([1, 'asc']).draw()

$('.ui.dropdown').dropdown()

                    <xsl:if test="runstats/finished/@summary">
$.toast({
    title: '<xsl:value-of select="runstats/finished/@exit"/>',
    message: '<xsl:value-of select="runstats/finished/@summary"/>',
    showIcon: 'satellite dish',
    displayTime: 'auto',
    closeIcon: true,
    position: 'bottom right',
})
                    </xsl:if>
                    <xsl:if test="runstats/finished/@errormsg">
$.toast({
    title: '<xsl:value-of select="runstats/finished/@exit"/>',
    message: '<xsl:value-of select="runstats/finished/@errormsg"/>',
    showIcon: 'exclamation triangle',
    class: 'error',
    displayTime: 'auto',
    closeIcon: true,
    position: 'bottom right',
})
                        </xsl:if>
                        <xsl:if test="$init">
$.toast({
    message: 'Comparaison avec les résultats du <xsl:value-of select="$init/runstats/finished/@timestr"/>',
    class: 'info',
    showIcon: 'calendar',
    displayTime: 'auto',
    closeIcon: true,
    position: 'bottom right',
})
                        </xsl:if>
                </script>
            </body>
        </html>
    </xsl:template>

    <xsl:template name="optionsList">
        <xsl:param name="argList" select=""/>
        <xsl:param name="asURL" select="false()"/>
        <xsl:variable name="nextArgs" select="substring-after($argList, ' -')"/>
        <xsl:variable name="argAndValue">
            <xsl:choose>
                <xsl:when test="$nextArgs">
                    <xsl:value-of select="substring-before($argList, ' -')"/>
                </xsl:when>
                <xsl:otherwise><xsl:value-of select="$argList"/></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:choose>
            <xsl:when test="starts-with($argAndValue, '-')">
                <xsl:choose>
                    <xsl:when test="contains($argAndValue, ' ')">
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring(substring-before($argAndValue, ' '), 2)"/>
                            <xsl:with-param name="value" select="substring-after($argAndValue, ' ')"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring($argAndValue, 2)"/>
                            <xsl:with-param name="value" select="on"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="starts-with($argAndValue, 'P') or starts-with($argAndValue, 's') or starts-with($argAndValue, 'o')">
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring($argAndValue, 1, 2)"/>
                            <xsl:with-param name="value" select="substring($argAndValue, 3)"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring($argAndValue, 1, 1)"/>
                            <xsl:with-param name="value" select="substring($argAndValue, 2)"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>

        <xsl:if test="$nextArgs">
            <xsl:call-template name="optionsList">
                <xsl:with-param name="argList" select="$nextArgs"/>
                <xsl:with-param name="asURL" select="$asURL"/>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>

    <xsl:template name="input">
        <xsl:param name="name"/>
        <xsl:param name="value" select=""/>
        <xsl:param name="asURL" select="false()"/>
        <xsl:choose>
            <xsl:when test="$asURL">
                <xsl:value-of select="$name"/>
                <xsl:text>=</xsl:text>
                <xsl:choose>
                    <xsl:when test="$value"><xsl:value-of select="$value"/></xsl:when>
                    <xsl:otherwise>on</xsl:otherwise>
                </xsl:choose>
                <xsl:text>&amp;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <input type="hidden" name="{$name}">
                    <xsl:attribute name="value">
                        <xsl:choose>
                            <xsl:when test="$value"><xsl:value-of select="$value"/></xsl:when>
                            <xsl:otherwise>on</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                </input>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="host">
        <xsl:variable name="addr" select="address/@addr"/>
        <xsl:variable name="initHost" select="$init/host[address/@addr=$addr]"/>
        <xsl:variable name="currentHost" select="$current/host[address/@addr=$addr]"/>
        <xsl:variable name="hostAddress">
            <xsl:choose>
                <xsl:when test="hostnames/hostname/@name">
                    <xsl:value-of select="hostnames/hostname/@name"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="address/@addr"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <tr>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="$currentHost/status/@state='up'">positive</xsl:when>
                    <xsl:otherwise>negative</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <td>
                <xsl:choose>
                    <xsl:when test="$currentHost">
                        <div>
                            <xsl:attribute name="class">
                                <xsl:choose>
                                    <xsl:when test="$currentHost/status/@state='up'">ui green circular label</xsl:when>
                                    <xsl:otherwise>ui red circular label</xsl:otherwise>
                                </xsl:choose>
                            </xsl:attribute>
                            <xsl:value-of select="$currentHost/status/@state"/>
                        </div>
                    </xsl:when>
                    <xsl:otherwise><div class="ui red circular label">down</div></xsl:otherwise>
                </xsl:choose>
            </td>
            <td>
                <xsl:value-of select="address/@addr"/>
            </td>
            <td>
                <b><xsl:value-of select="hostnames/hostname/@name"/></b>
            </td>
            <td>
                <xsl:apply-templates select="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]">
                    <xsl:with-param name="initHost" select="$initHost"/>
                    <xsl:with-param name="currentHost" select="$currentHost"/>
                    <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    <xsl:sort select="@portid" order="ascending"/>
                </xsl:apply-templates>
            </td>
            <td>
                <a class="ui right labeled tiny teal icon button" onclick="console.log(this);this.getElementsByTagName('i').className = 'loading spinner icon'">
                    <xsl:attribute name="href">
                        <xsl:value-of select="$basedir"/>
                        <xsl:text>/scan.php?host=</xsl:text>
                        <xsl:value-of select="$hostAddress"/>
                    </xsl:attribute>
                    <i class="satellite dish icon"></i>
                    Scanner
                </a>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="port">
        <xsl:param name="hostAddress"/>
        <xsl:param name="initHost"/>
        <xsl:param name="currentHost"/>
        <xsl:variable name="portid" select="@portid"/>
        <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]"/>
        <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]"/>

        <a class="ui label" target="_blank">
            <xsl:attribute name="class">
                <xsl:text>ui label </xsl:text>
                <xsl:choose>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='filtered'">orange disabled</xsl:when>
                    <xsl:otherwise>red disabled</xsl:otherwise>
                </xsl:choose>
                <xsl:choose>
                    <xsl:when test="(service/@name='microsoft-ds' or service/@name='netbios-ssn') and ../../hostscript/script[@id='smb-shares-size']/table"> mini dropdown button share-size</xsl:when>
                    <xsl:otherwise> small</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https'">
                <xsl:attribute name="href">
                    <xsl:value-of select="service/@name"/>
                    <xsl:text>://</xsl:text>
                    <xsl:value-of select="$hostAddress"/>
                    <xsl:text>:</xsl:text>
                    <xsl:value-of select="@portid"/>
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="service/@name='ms-wbt-server'">
                <xsl:attribute name="href">
                    <xsl:text>rdp.php?v=</xsl:text>
                    <xsl:value-of select="$hostAddress"/>
                    <xsl:text>&amp;p=</xsl:text>
                    <xsl:value-of select="@portid"/>
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
                <xsl:attribute name="style">
                    <xsl:for-each select="$currentHost/hostscript/script[@id='smb-shares-size']/table">
                        <xsl:sort select="elem[@key='FreeSize'] div elem[@key='TotalSize']" order="ascending"/>
                        <xsl:if test="position()=1">
                            <xsl:text>--free: </xsl:text>
                            <xsl:value-of select="elem[@key='FreeSize']"/>
                            <xsl:text>; --total: </xsl:text>
                            <xsl:value-of select="elem[@key='TotalSize']"/>
                        </xsl:if>
                    </xsl:for-each>
                </xsl:attribute>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <xsl:apply-templates select="$currentHost/hostscript/script[@id='smb-shares-size']/table">
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