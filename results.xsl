<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>

<xsl:variable name="scan" select="document(string(lanScan/@scanpath))/nmaprun"/>

<xsl:template match="lanScan">
<html lang="fr">
    <head>
        <title><xsl:value-of select="@site"/> - lanScan</title>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
        <style>
#logo {
    margin: 0 -.4rem 0 0;
}
.main.container {
    margin-top: 5em;
}
.ui.mini.button {
    padding: 1em;
}
.icon {
    display: flex !important;
    align-items: center;
}
.icon > img {
    width: 16px;
    height: 16px;
    margin: auto;
}

.share-size {
    --free-ratio: calc(var(--free) / var(--total));
    --used-percent: calc(100% - 100%*var(--free-ratio));
    --color: hsl(calc(120*var(--free-ratio)) 100% 50%);
    background-image: linear-gradient(to right, var(--color) var(--used-percent), transparent var(--used-percent), transparent) !important;
}
        </style>
        <meta http-equiv="refresh" content="60"/>
    </head>
    <body>
        <header class="ui fixed blue inverted menu">
            <a href=".." class="header item">lan<img id="logo" src="../logo.svg" alt="S"/>can</a>
            <div class="header center item"><xsl:value-of select="@site"/></div>
        </header>
        <div class="ui main container">
            <xsl:choose>
                <xsl:when test="$scan/runstats/finished/@errormsg">
                    <div class="ui negative icon message">
                        <i class="exclamation triangle icon"></i>
                        <div class="content">
                            <div class="header" style="text-transform: capitalize"><xsl:value-of select="$scan/runstats/finished/@exit"/></div>
                            <p><xsl:value-of select="$scan/runstats/finished/@errormsg"/></p>
                        </div>
                    </div>
                </xsl:when>
                <xsl:when test="$scan/runstats/finished/@summary">
                    <div class="ui icon message">
                        <i class="sitemap icon"></i>
                        <div class="content">
                            <div class="header" style="text-transform: capitalize"><xsl:value-of select="$scan/runstats/finished/@exit"/></div>
                            <p><xsl:value-of select="$scan/runstats/finished/@summary"/></p>
                        </div>
                    </div>
                </xsl:when>
            </xsl:choose>
            <xsl:apply-templates select="group"/>
        </div>
        <script>
            $('.ui.dropdown').dropdown()
        </script>
    </body>
</html>
</xsl:template>

<xsl:template match="group">
    <h1 class="ui header"><xsl:value-of select="@name"/></h1>
    <div class="ui doubling stackable four column compact grid">
        <xsl:apply-templates select="host"/>
    </div>
</xsl:template>

<xsl:template match="host">
    <xsl:variable name="address" select="@address"/>
    <xsl:variable name="scannedHost" select="$scan/host[hostnames/hostname/@name=$address or address/@addr=$address]"/>
    <xsl:variable name="scannedHostAddress">
        <xsl:choose>
            <xsl:when test="$scannedHost/hostnames/hostname/@name">
                <xsl:value-of select="$scannedHost/hostnames/hostname/@name"/>
            </xsl:when>
            <xsl:when test="$scannedHost/address/@addr">
                <xsl:value-of select="$scannedHost/address/@addr"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$address"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:variable>
    <xsl:variable name="name">
        <xsl:choose>
            <xsl:when test="@name"><xsl:value-of select="@name"/></xsl:when>
            <xsl:when test="$scannedHost/hostnames/hostname/@name"><xsl:value-of select="substring-before($scannedHost/hostnames/hostname/@name, '.')"/></xsl:when>
        </xsl:choose>
    </xsl:variable>
    <div class="column">
        <xsl:variable name="status">
            <xsl:choose>
                <xsl:when test="$scannedHost/status/@state='up'">success</xsl:when>
                <xsl:otherwise>error</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <div class="ui fluid mini left icon action input {$status}">
            <xsl:choose>
                <xsl:when test="$scannedHost/ports/port/script[@id='http-info']/elem[@key='favicon']">
                    <i class="icon"><img class="ui image" src="{$scannedHost/ports/port/script[@id='http-info']/elem[@key='favicon']}" alt=""/></i>
                </xsl:when>
                <xsl:otherwise>
                    <i class="server icon"></i>
                </xsl:otherwise>
            </xsl:choose>
            <input type="text" readonly="" value="{$name}" placeholder="{$scannedHost/address/@addr}"
            title="{@comment} {$scannedHost/hostnames/hostname/@name} ({$scannedHost/address/@addr}) "
                onfocus="this.value='{$scannedHostAddress}'; this.select()" onblur="this.value='{$name}'"
            />
            <xsl:apply-templates select="service">
                <xsl:with-param name="scannedHost" select="$scannedHost"/>
                <xsl:with-param name="scannedHostAddress" select="$scannedHostAddress"/>
            </xsl:apply-templates>
        </div>
    </div>
</xsl:template>

<xsl:template match="service">
    <xsl:param name="scannedHost"/>
    <xsl:param name="scannedHostAddress"/>
    <xsl:variable name="serviceName" select="."/>
    <xsl:variable name="scannedPort" select="$scannedHost/ports/port[service/@name=$serviceName or @portid=$serviceName][1]"/>
    <xsl:variable name="state">
        <xsl:choose>
            <xsl:when test="$scannedPort/state/@state='open'">green</xsl:when>
            <xsl:when test="$scannedPort/state/@state='filtered'">yellow</xsl:when>
            <xsl:otherwise>red</xsl:otherwise>
        </xsl:choose>
    </xsl:variable>
    <xsl:variable name="title">
        <xsl:value-of select="$scannedPort/@portid"/>
        <xsl:text>/</xsl:text>
        <xsl:value-of select="$scannedPort/@protocol"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="$scannedPort/state/@state"/>
        <xsl:text> </xsl:text>
        <xsl:value-of select="$scannedPort/service/@name"/>
    </xsl:variable>
    <xsl:choose>
        <xsl:when test="$scannedPort/script[@id='smb-shares-size']/table">
            <div class="ui {$state} dropdown mini button share-size" title="{$title}">
                <xsl:attribute name="style">
                    <xsl:for-each select="$scannedPort/script[@id='smb-shares-size']/table">
                        <xsl:sort select="elem[@key='FreeSize'] div elem[@key='TotalSize']" order="ascending"/>
                        <xsl:if test="position()=1">
                            <xsl:text>--free: </xsl:text>
                            <xsl:value-of select="elem[@key='FreeSize']"/>
                            <xsl:text>; --total: </xsl:text>
                            <xsl:value-of select="elem[@key='TotalSize']"/>
                        </xsl:if>
                    </xsl:for-each>
                </xsl:attribute>
                <xsl:value-of select="$serviceName"/>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <xsl:apply-templates select="$scannedPort/script[@id='smb-shares-size']/table">
                        <xsl:with-param name="scannedHostAddress" select="$scannedHostAddress"/>
                    </xsl:apply-templates>
                </div>
            </div>
        </xsl:when>
        <xsl:when test="$scannedPort/service/@name='ms-wbt-server' or $scannedPort/service/@name='rdp'">
            <a class="ui {$state} mini button" href="../rdp.php?v={$scannedHostAddress}:{$scannedPort/@portid}" title="{$title}">
                <xsl:value-of select="$serviceName"/>
            </a>
        </xsl:when>
        <xsl:when test="$scannedPort/script[@id='http-info']">
            <xsl:variable name="status">
                <xsl:choose>
                    <xsl:when test="$scannedPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                    <xsl:when test="$scannedPort/script[@id='http-info']/elem[@key='status']>=400">yellow</xsl:when>
                    <xsl:when test="$scannedPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                    <xsl:otherwise>red</xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            <a class="ui {$status} mini button" href="{$scannedPort/service/@name}://{$scannedHostAddress}:{$scannedPort/@portid}"  target="_blank"
            title="{$scannedPort/script[@id='http-info']/elem[@key='title' or @key='status-line']}">
                <xsl:value-of select="$serviceName"/>
            </a>
        </xsl:when>
        <xsl:when test="$scannedPort/service/@name='ftp' or $scannedPort/service/@name='ssh' or $scannedPort/service/@name='http' or $scannedPort/service/@name='https'">
            <a class="ui {$state} mini button" href="{$scannedPort/service/@name}://{$scannedHostAddress}:{$scannedPort/@portid}"  target="_blank" title="{$title}">
                <xsl:value-of select="$serviceName"/>
            </a>
        </xsl:when>
        <xsl:otherwise>
            <a class="ui disabled {$state} mini button" title="{$title}">
                <xsl:value-of select="$serviceName"/>
            </a>
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>


<xsl:template match="table">
    <xsl:param name="scannedHostAddress"/>
    <a class="item share-size" href="file://///{$scannedHostAddress}/{@key}" target="_blank" rel="noopener noreferrer" style="--free: {elem[@key='FreeSize']}; --total: {elem[@key='TotalSize']}">
        <xsl:value-of select="@key"/>
    </a>
</xsl:template>

</xsl:stylesheet>
