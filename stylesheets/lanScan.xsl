<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

  <xsl:import href="head.xsl" />
  <xsl:import href="nav.xsl" />
  <xsl:import href="services.xsl" />
  <xsl:import href="toast.xsl" />

  <xsl:output method="html" encoding="UTF-8" indent="yes" escape-uri-attributes="no" />
  <xsl:strip-space elements='*' />

  <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'), '?')" />
  <xsl:variable name="base" select="concat($stylesheetURL, '/../../')" />
  <xsl:variable name="name" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'name='), '&quot;')" disable-output-escaping="no"/>

  <xsl:template match="nmaprun">
    <xsl:variable name="targets" select="substring-after(@args, '-oX - ')" />
    <xsl:variable name="current" select="." />
    <xsl:variable name="init" select="document(concat($base, 'scans/', $name, '.xml'))/nmaprun" />

    <html lang="fr">
      <xsl:apply-templates select="." mode="head">
        <xsl:with-param name="base" select="$base" />
        <xsl:with-param name="name" select="$name" />
        <xsl:with-param name="targets" select="$targets" />
      </xsl:apply-templates>

      <body>
        <xsl:apply-templates select="." mode="nav">
        </xsl:apply-templates>

        <main class="ui main container inverted segment">
          <h1 class="ui header">
            <xsl:choose>
              <xsl:when test="$name">
                <xsl:value-of select="$name" />
                <div class="sub header">
                  <xsl:value-of select="$targets" />
                </div>
              </xsl:when>
              <xsl:otherwise>
                <xsl:value-of select="$targets" />
              </xsl:otherwise>
            </xsl:choose>
          </h1>

          <table id="scanResultsTable" style="width:100%" role="grid" class="ui sortable small compact stuck striped table">
            <thead>
              <tr>
                <th style="width: min-width">Etat</th>
                <th>Adresse IP</th>
                <th>Nom</th>
                <th>Constructeur</th>
                <th class="six wide">Services</th>
                <th style="width: min-width" title="Scan intensif">
                  <i class="search plus icon"></i>
                </th>
              </tr>
            </thead>
            <tbody>
              <xsl:apply-templates select="host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]">
                <xsl:with-param name="init" select="$init" />
                <xsl:with-param name="current" select="$current" />
              </xsl:apply-templates>
            </tbody>
          </table>
        </main>

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
          <xsl:with-param name="init" select="$init" />
        </xsl:apply-templates>

      </body>

    </html>
  </xsl:template>

  <xsl:template match="host">
    <xsl:param name="init" />
    <xsl:param name="current" />
    <xsl:variable name="addr" select="address/@addr" />
    <xsl:variable name="initHost" select="$init/host[address/@addr=$addr]" />
    <xsl:variable name="currentHost" select="$current/host[address/@addr=$addr]" />
    <xsl:variable name="hostAddress">
      <xsl:choose>
        <xsl:when test="hostnames/hostname/@name">
          <xsl:value-of select="hostnames/hostname/@name" />
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="address/@addr" />
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
              <xsl:value-of select="$currentHost/status/@state" />
            </div>
          </xsl:when>
          <xsl:otherwise>
            <div class="ui mini circular label red">down</div>
          </xsl:otherwise>
        </xsl:choose>
      </td>
      <td>
        <xsl:value-of select="address/@addr" />
      </td>
      <td>
        <b>
          <xsl:value-of select="substring-before(hostnames/hostname/@name, '.')" />
        </b>
        <xsl:if test="substring-after(hostnames/hostname/@name, '.')">
          <wbr />
          <xsl:text>.</xsl:text>
          <xsl:value-of select="substring-after(hostnames/hostname/@name, '.')" />
        </xsl:if>
      </td>
      <td>
        <xsl:value-of select="address[@addrtype='mac']/@vendor" />
      </td>
      <td>
        <xsl:apply-templates select="ports/port | $initHost/ports/port[not(state/@state='closed')][not(@portid=$currentHost/ports/port/@portid)]" mode="service">
          <xsl:with-param name="initHost" select="$initHost" />
          <xsl:with-param name="currentHost" select="$currentHost" />
          <xsl:with-param name="hostAddress" select="$hostAddress" />
          <xsl:with-param name="class" select="'ui mini label'" />
          <xsl:sort select="number(@portid)" order="ascending" />
        </xsl:apply-templates>
      </td>
      <td>
        <a class="ui mini icon teal icon button" target="_blank" title="Scan intensif">
          <xsl:attribute name="href">scan.php?host=<xsl:value-of select="$hostAddress" />
          </xsl:attribute>
          <i class="search plus icon"></i>
        </a>
      </td>
    </tr>
  </xsl:template>

</xsl:stylesheet>