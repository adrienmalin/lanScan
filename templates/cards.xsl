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
    <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'), '?')"/>
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

                <main class="ui wide container">
                    <div class="ui header container">
                        <h1 class="ui header"><xsl:value-of select="$targets"/></h1>
                    </div>

                    <div class="ui doubling stackable five column compact grid">
                        <div class="ui centered link cards">
                            <xsl:apply-templates select="host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]"/>
                        </div>
                    </div>
                </main>
                
                <footer class="ui footer segment">
                    lanScan est basé sur <a href="https://nmap.org/" target="_blank">Nmap</a>
                </footer>

                <script>
$('.ui.dropdown').dropdown()

function hostScanning(link) {
    link.parentElement.parentElement.classList.add("loading")
    $.toast({
        title      : 'Scan en cours...',
        message    : 'Merci de patienter',
        class      : 'info',
        showIcon   : 'satellite dish',
        displayTime: 0,
        closeIcon  : true,
        position   : 'bottom right',
    })
}
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
        <div>
            <xsl:attribute name="class">
                <xsl:text>ui card </xsl:text>
                <xsl:choose>
                    <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
                    <xsl:otherwise>red</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <div class="content">
                <div class="header">
                    <xsl:choose>
                        <xsl:when test="$currentHost">
                            <div>
                                <xsl:attribute name="class">
                                    <xsl:text>ui empty circular label </xsl:text>
                                    <xsl:choose>
                                        <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
                                        <xsl:otherwise>red</xsl:otherwise>
                                    </xsl:choose>
                                </xsl:attribute>
                            </div>
                        </xsl:when>
                        <xsl:otherwise><div class="ui empty circular label red"></div></xsl:otherwise>
                    </xsl:choose>
                    <xsl:text> </xsl:text>
                    <xsl:choose>
                        <xsl:when test="hostnames/hostname/@name">
                            <xsl:value-of select="substring-before(hostnames/hostname/@name, '.')"/>
                        </xsl:when>
                        <xsl:otherwise>
                        <xsl:value-of select="address/@addr"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
                <div class="meta">
                    <xsl:if test="substring-after(hostnames/hostname/@name, '.')">
                        <div>
                            <xsl:text>.</xsl:text>
                            <xsl:value-of select="substring-after(hostnames/hostname/@name, '.')"/>
                        </div>
                    </xsl:if>
                    <div><xsl:value-of select="address/@addr"/></div>
                    <xsl:if test="address[@addrtype='mac']/@vendor">
                        <div><xsl:value-of select="address[@addrtype='mac']/@vendor"/></div>
                    </xsl:if>
                </div>
                <div class="description">
                    <xsl:apply-templates select="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]" mode="service">
                        <xsl:with-param name="initHost" select="$initHost"/>
                        <xsl:with-param name="currentHost" select="$currentHost"/>
                        <xsl:with-param name="hostAddress" select="$hostAddress"/>
                        <xsl:with-param name="class" select="'ui label'"/>
                        <xsl:sort select="number(@portid)" order="ascending"/>
                    </xsl:apply-templates>
                </div>
            </div>
            <div class="ui buttons">
                <a class="ui icon labeled teal button" onclick="hostScanning(this)">
                    <xsl:attribute name="href">
                        <xsl:value-of select="$basedir"/>
                        <xsl:text>/scan.php?preset=host&amp;targets=</xsl:text>
                        <xsl:value-of select="$hostAddress"/>
                    </xsl:attribute>
                    <i class="satellite dish icon"></i>
                    <xsl:text> Services</xsl:text>
                </a>
                <a class="ui icon teal button ">
                    <xsl:attribute name="href">
                        <xsl:value-of select="$basedir"/>
                        <xsl:text>/?preset=host&amp;targets=</xsl:text>
                        <xsl:value-of select="$hostAddress"/>
                    </xsl:attribute>
                    <i class="settings icon"></i>
                </a>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>