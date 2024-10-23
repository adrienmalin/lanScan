<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:import href="lib/head.xsl"/>
    <xsl:import href="lib/nav.xsl"/>
    <xsl:import href="lib/service.xsl"/>
    <xsl:import href="lib/toast.xsl"/> 

    <xsl:output method="html" encoding="UTF-8"/>
    <xsl:output indent="yes"/>
    <xsl:strip-space elements='*'/>

    <xsl:param name="thisURL" select=""/>
    <xsl:param name="originalURL" select=""/>
    <xsl:param name="refreshPeriod" select="0"/>
    <xsl:param name="sudo" select="false"/>

    <xsl:variable name="current" select="./nmaprun"/>
    <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'),'&quot;')"/>
    <xsl:variable name="basedir" select="concat($stylesheetURL, '/../..')"/>
    <xsl:variable name="init" select="document($originalURL)/nmaprun"/>
    <xsl:variable name="nextComparison">
        <xsl:choose>
            <xsl:when test="$thisURL"><xsl:value-of select="$thisURL"/></xsl:when>
            <xsl:when test="$originalURL"><xsl:value-of select="$originalURL"/></xsl:when>
            <xsl:otherwise></xsl:otherwise>
        </xsl:choose>
    </xsl:variable>

    <xsl:template match="nmaprun">
        <xsl:variable name="targets" select="substring-after(@args, '.xml ')"/>
        
        <html lang="fr">
            <xsl:apply-templates select="." mode="head">
                <xsl:with-param name="basedir" select="$basedir"/>
                <xsl:with-param name="targets" select="$targets"/>
                <xsl:with-param name="nextComparison" select="$nextComparison"/>
                <xsl:with-param name="refreshPeriod" select="$refreshPeriod"/>
                <xsl:with-param name="sudo" select="$sudo"/>
            </xsl:apply-templates>

            <body>
                <xsl:apply-templates select="." mode="nav">
                    <xsl:with-param name="basedir" select="$basedir"/>
                    <xsl:with-param name="targets" select="$targets"/>
                    <xsl:with-param name="nextComparison" select="$nextComparison"/>
                    <xsl:with-param name="refreshPeriod" select="$refreshPeriod"/>
                    <xsl:with-param name="sudo" select="$sudo"/>
                </xsl:apply-templates>

                <main class="ui container">
                    <h1 class="ui header"><xsl:value-of select="$targets"/></h1>

                    <div class="form">
                        <div class="ui doubling stackable four column compact grid">
                            <xsl:apply-templates select="host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]"/>
                        </div>
                    </div>
                </main>
                
                <footer class="ui footer segment">
                    lanScan est basé sur <a href="https://nmap.org/" target="_blank">Nmap</a>
                </footer>

                <script>
$('.ui.dropdown').dropdown()
                </script>

                <xsl:apply-templates select="runstats">
                    <xsl:with-param name="init" select="$init"/>
                </xsl:apply-templates>
            </body>
        </html>
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
        <div class="column">
            <div>
                <xsl:attribute name="class">
                    <xsl:text>ui fluid mini compact input </xsl:text>
                    <xsl:if test="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]">
                        <xsl:text>action buttons </xsl:text>
                    </xsl:if>
                    <xsl:choose>
                        <xsl:when test="$currentHost/status/@state='up'">success</xsl:when>
                        <xsl:otherwise>error</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <input type="text" readonly="" value="{substring-before(hostnames/hostname/@name, '.')}" placeholder="{address/@addr}"
                    title="{$currentHost/hostnames/hostname/@name} ({address/@addr})"
                    onfocus="this.value='{hostnames/hostname/@name}'; this.select()" onblur="this.value='{substring-before(hostnames/hostname/@name, '.')}'"
                />
                <xsl:apply-templates select="$initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')] | $currentHost/ports/port" mode="service">
                    <xsl:with-param name="initHost" select="$initHost"/>
                    <xsl:with-param name="currentHost" select="$currentHost"/>
                    <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    <xsl:with-param name="class" select="'ui mini button'"/>
                    <xsl:sort select="number(@portid)" order="ascending"/>
                </xsl:apply-templates>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="port">
        <xsl:param name="hostAddress"/>
        <xsl:param name="initHost"/>
        <xsl:param name="currentHost"/>
        <xsl:variable name="portid" select="@portid"/>
        <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]"/>
        <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]"/>
        <xsl:variable name="state">
            <xsl:choose>
                <xsl:when test="$currentHost/state/@state='open'">green</xsl:when>
                <xsl:when test="$currentHost/state/@state='filtered'">yellow</xsl:when>
                <xsl:otherwise>red</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <a target="_blank">
            <xsl:attribute name="class">
                <xsl:text>ui mini button </xsl:text>
                <xsl:choose>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
                    <xsl:otherwise>red</xsl:otherwise>
                </xsl:choose>
                <xsl:if test="(service/@name='microsoft-ds' or service/@name='netbios-ssn') and ../../hostscript/script[@id='smb-shares-size']/table"> dropdown share-size</xsl:if>
            </xsl:attribute>
            <xsl:if test="service/@name='ms-wbt-server'">
                <xsl:attribute name="href">
                    <xsl:text>rdp.php?v=</xsl:text>
                    <xsl:value-of select="$hostAddress"/>
                    <xsl:text>&amp;p=</xsl:text>
                    <xsl:value-of select="@portid"/>
                </xsl:attribute>
            </xsl:if>
            <xsl:attribute name="title">
                <xsl:value-of select="@portid"/>/<xsl:value-of select="@protocol"/>
            </xsl:attribute>
            <xsl:choose>
                <xsl:when test="service/@name='unknown'">
                    <xsl:choose>
                        <xsl:when test="@protocol='tcp'">:</xsl:when>
                        <xsl:otherwise><xsl:value-of select="substring(@protocol, 1, 1)"/>:</xsl:otherwise>
                    </xsl:choose>
                    <xsl:value-of select="@portid"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="service/@name"/>
                </xsl:otherwise>
            </xsl:choose>
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