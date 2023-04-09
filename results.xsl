<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">
<xsl:output method="html" encoding="UTF-8" indent="yes" />

<xsl:variable name="scan" select="document(string(lanScanConf/scan/@path))/nmaprun"/>

<xsl:template match="lanScanConf">
<html lang="fr">
    <head>
        <title>lanScan - <xsl:value-of select="@name"/></title>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css" />
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
        </style>
    </head>
    <body>
        <header class="ui fixed blue inverted menu">
            <a href="." class="header item">lan<img id="logo" src="../logo.svg" alt="S"/>can</a>
            <div class="item"><xsl:value-of select="@name"/></div>
        </header>
        <div class="ui main container">
            <p><xsl:value-of select="$scan/runstats/finished/@summary"/></p>
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
    <div class="ui doubling stackable three column compact grid">
        <xsl:apply-templates select="host"/>
    </div>
</xsl:template>

<xsl:template match="host">
    <xsl:variable name="address" select="@address"/>
    <xsl:variable name="scannedHost" select="$scan/host[hostnames/hostname/@name=$address or address/@addr=$address]"/>
    <xsl:variable name="scannedHostAddress">
        <xsl:choose>
            <xsl:when test="$scannedHost/hostnames/hostname/@name">
                <xsl:value-of select="$scannedHost/hostnames/hostname/@name" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$scannedHost/address/@addr" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:variable>
    <div class="column">
        <div>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="$scannedHost/status/@state='up'">ui fluid mini left icon action input info</xsl:when>
                    <xsl:otherwise>ui fluid mini left icon action input error</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <xsl:choose>
                <xsl:when test="$scannedHost/ports/port/service/@name='https'">
                    <i class="icon"><img class="ui image" src="https://{$scannedHostAddress}/favicon.ico" alt="" /></i>
                </xsl:when>
                <xsl:when test="$scannedHost/ports/port/service/@name='http'">
                    <i class="icon"><img class="ui image" src="http://{$scannedHostAddress}/favicon.ico" alt="" /></i>
                </xsl:when>
                <xsl:otherwise>
                    <i class="server icon"></i>
                </xsl:otherwise>
            </xsl:choose>
            <input type="text" readonly="">
                <xsl:attribute name="value">
                    <xsl:choose>
                        <xsl:when test="substring-before($scannedHost/hostnames/hostname/@name, '.')"><xsl:value-of select="substring-before($scannedHost/hostnames/hostname/@name, '.')" /></xsl:when>
                        <xsl:when test="$scannedHost/hostnames/hostname/@name"><xsl:value-of select="$scannedHost/hostnames/hostname/@name" /></xsl:when>
                        <xsl:when test="$scannedHost/address/@addr"><xsl:value-of select="$scannedHost/address/@addr" /></xsl:when>
                        <xsl:when test="contains('0123456789', substring(@address,1,1))"><xsl:value-of select="@address" /></xsl:when>
                        <xsl:when test="substring-before(@address, '.')"><xsl:value-of select="substring-before(@address, '.')" /></xsl:when>
                        <xsl:otherwise><xsl:value-of select="@address" /></xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:choose>
                        <xsl:when test="$scannedHost/hostnames/hostname/@name"><xsl:value-of select="$scannedHost/hostnames/hostname/@name" /> (<xsl:value-of select="$scannedHost/address/@addr" />)</xsl:when>
                        <xsl:when test="$scannedHost/address/@addr"><xsl:value-of select="$scannedHost/address/@addr" /></xsl:when>
                        <xsl:otherwise><xsl:value-of select="@address" /></xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
            </input>
            <xsl:apply-templates select="service">
                <xsl:with-param name="scannedHost" select="$scannedHost" />
                <xsl:with-param name="scannedHostAddress" select="$scannedHostAddress" />
            </xsl:apply-templates>
        </div>
    </div>
</xsl:template>

<xsl:template match="service">
    <xsl:param name="scannedHost" />
    <xsl:param name="scannedHostAddress" />
    <xsl:variable name="serviceName" select="@name"/>
    <xsl:variable name="scannedPort" select="$scannedHost/ports/port[service/@name=$serviceName or @portid=$serviceName]"/>
    <xsl:choose>
        <xsl:when test="$scannedPort/state/@state='open'">
            <xsl:choose>
                <xsl:when test="($scannedPort/service/@name='microsoft-ds' or $scannedPort/service/@name='netbios-ssn') and $scannedHost/hostscript/script[@id='smb-enum-shares']/table[not(contains(@key, '$'))]">
                    <div class="ui primary dropdown mini button">
                        <div class="text"><xsl:value-of select="@name"/></div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <xsl:apply-templates select="$scannedHost/hostscript/script[@id='smb-enum-shares']/table[not(contains(@key, '$'))]">
                                <xsl:with-param name="scannedHostAddress" select="$scannedHostAddress" />
                            </xsl:apply-templates>
                        </div>
                    </div>
                </xsl:when>
                <xsl:when test="$scannedPort/service/@name='ms-wbt-server'">
                    <a class="ui primary mini button" href="../rdp.php?v={$scannedHostAddress}:{$scannedPort/@portid}">
                        <xsl:value-of select="@name"/>
                    </a>
                </xsl:when>
                <xsl:when test="$scannedPort/service/@name='ftp' or $scannedPort/service/@name='ssh' or $scannedPort/service/@name='http' or $scannedPort/service/@name='https'">
                    <a class="ui primary mini button" href="{$scannedPort/service/@name}://{$scannedHostAddress}:{$scannedPort/@portid}">
                        <xsl:value-of select="@name"/>
                    </a>
                </xsl:when>
                <xsl:otherwise>
                    <a class="ui disabled primary mini button">
                        <xsl:value-of select="@name"/>
                    </a>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:when>
        <xsl:otherwise>
            <a class="ui red disabled mini button">
                <xsl:value-of select="@name"/>
            </a>
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>


<xsl:template match="table">
    <xsl:param name="scannedHostAddress" />
    <a class="item" href="file:///{@key}" target="_blank" rel="noopener noreferrer">
        <xsl:value-of select="@key" />
    </a>
</xsl:template>

</xsl:stylesheet>