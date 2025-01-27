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
    <xsl:param name="sudo" select="false()"/>

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
        <xsl:variable name="targets" select="substring-after(@args, '-oX - ')"/>
        
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

                <main class="ui main container">
                    <h1 class="ui header"><xsl:value-of select="$targets"/></h1>

                    <table id="scanResultsTable" style="width:100%" role="grid" class="ui sortable small table">
                        <thead>
                            <tr>
                                <th>Etat</th>
                                <th>Adresse IP</th>
                                <th>Nom</th>
                                <th>Fabricant</th>
                                <th class="six wide">Services</th>
                                <th>Scanner les services</th>
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
var table = $('#scanResultsTable').DataTable({
    buttons    : ['copy', 'excel', 'pdf'],
    fixedHeader: true,
    lengthMenu : [
        [256, 512, 1024, 2048, -1],
        [256, 512, 1024, 2048, "All"]
    ],
    responsive: true,
    colReorder: true,
    buttons   : ['copy', 'excel', 'pdf']
})
table.order([1, 'asc']).draw()

$('.ui.dropdown').dropdown()

function hostScanning(link) {
    link.getElementsByTagName('i')[0].className = 'loading spinner icon'
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
                                <xsl:text>ui mini circular label </xsl:text>
                                <xsl:choose>
                                    <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
                                    <xsl:otherwise>red</xsl:otherwise>
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
                <div><b><xsl:value-of select="substring-before(hostnames/hostname/@name, '.')"/></b></div>
                <xsl:if test="substring-after(hostnames/hostname/@name, '.')">
                    <div>.<xsl:value-of select="substring-after(hostnames/hostname/@name, '.')"/></div>
                </xsl:if>
            </td>
            <td>
                <xsl:value-of select="address[@addrtype='mac']/@vendor"/>
            </td>
            <td>
                <xsl:apply-templates select="$initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')] | $currentHost/ports/port" mode="service">
                    <xsl:with-param name="initHost" select="$initHost"/>
                    <xsl:with-param name="currentHost" select="$currentHost"/>
                    <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    <xsl:with-param name="class" select="'ui label'"/>
                    <xsl:sort select="number(@portid)" order="ascending"/>
                </xsl:apply-templates>
            </td>
            <td>
                <div class="ui mini right labeled button">
                    <a class="ui mini icon teal button" onclick="hostScanning(this)">
                        <xsl:attribute name="href">
                            <xsl:value-of select="$basedir"/>
                            <xsl:text>/scan.php?preset=host&amp;targets=</xsl:text>
                            <xsl:value-of select="address/@addr"/>
                        </xsl:attribute>
                        <i class="satellite dish icon"></i>
                        <xsl:text> Services</xsl:text>
                    </a>
                    <a class="ui mini icon teal label">
                        <xsl:attribute name="href">
                            <xsl:value-of select="$basedir"/>
                            <xsl:text>/?preset=host&amp;targets=</xsl:text>
                            <xsl:value-of select="address/@addr"/>
                        </xsl:attribute>
                        <i class="settings icon"></i>
                    </a>
                </div>
            </td>
        </tr>
    </xsl:template>

</xsl:stylesheet>