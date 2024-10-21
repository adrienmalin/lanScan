<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:import href="lib/head.xsl"/>
    <xsl:import href="lib/nav.xsl"/> 
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
            <xsl:when test="$thisURL"><xsl:value-of select="$saveAs"/></xsl:when>
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

                <main class="ui main container">
                    <xsl:apply-templates select="host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]"/>
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
        
        <h1 class="ui header">
            <xsl:choose>
                <xsl:when test="hostnames/hostname/@name">
                    <xsl:value-of select="hostnames/hostname/@name"/>
                    <div class="sub header"><xsl:value-of select="address/@addr"/></div>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="address/@addr"/>
                </xsl:otherwise>
            </xsl:choose>
        </h1>

        <table id="scanResultsTable" style="width:100%" role="grid" class="ui sortable small table">
            <thead>
                <tr>
                    <th>Etat</th>
                    <th>Protocole</th>
                    <th>Port</th>
                    <th>Service</th>
                    <th>Produit</th>
                    <th>Version</th>
                </tr>
            </thead>
            <tbody>
                <xsl:apply-templates select="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]">
                    <xsl:with-param name="initHost" select="$initHost"/>
                    <xsl:with-param name="currentHost" select="$currentHost"/>
                    <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    <xsl:sort select="number(@portid)" order="ascending"/>
                </xsl:apply-templates>
            </tbody>
        </table>
    </xsl:template>


    <xsl:template match="port">
        <xsl:param name="hostAddress"/>
        <xsl:param name="initHost"/>
        <xsl:param name="currentHost"/>
        <xsl:variable name="portid" select="@portid"/>
        <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]"/>
        <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]"/>

        <tr>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">negative</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">warning</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">positive</xsl:when>
                    <xsl:when test="$currentPort/state/@state='open'">positive</xsl:when>
                    <xsl:when test="$currentPort/state/@state='filtered'">warning</xsl:when>
                    <xsl:otherwise>negative</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <td>
                <div>
                    <xsl:attribute name="class">
                        <xsl:text>ui mini circular label </xsl:text>
                        <xsl:choose>
                            <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
                            <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
                            <xsl:otherwise>red</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="$currentPort/state/@state"/>
                </div>
            </td>
            <td style="text-transform: uppercase">
                <xsl:value-of select="@protocol"/>
            </td>
            <td>
                <xsl:value-of select="@portid"/>
            </td>
            <td>
                <a>
                    <xsl:attribute name="class">
                        <xsl:text>ui mini fluid button </xsl:text>
                        <xsl:choose>
                            <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                            <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
                            <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                            <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
                            <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
                            <xsl:otherwise>red</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https'">
                        <xsl:attribute name="href">
                            <xsl:choose>
                                <xsl:when test="service/@name='http' and service/@tunnel='ssl'">
                                    <xsl:text>https</xsl:text>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="service/@name"/>
                                </xsl:otherwise>
                            </xsl:choose>
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
                    <xsl:if test="script[@id='http-info']/elem[@key='title']">
                        <xsl:attribute name="title">
                            <xsl:value-of select="script[@id='http-info']/elem[@key='title']"/>
                        </xsl:attribute>
                    </xsl:if>
                    <xsl:value-of select="service/@name"/>
                </a>
            </td>
            <td>
                <xsl:value-of select="service/@product"/>
            </td>
            <td>
                <xsl:value-of select="service/@version"/>
            </td>
        </tr>

    </xsl:template>

</xsl:stylesheet>