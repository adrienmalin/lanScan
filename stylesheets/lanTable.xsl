<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

  <xsl:import href="lib/head.xsl" />
  <xsl:import href="lib/nav.xsl" />
  <xsl:import href="lib/toast.xsl" />

  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements='*' />

  <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'), '?')" />
  <xsl:variable name="base" select="concat($stylesheetURL, '/../../')" />
  <xsl:variable name="name" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'name='), '&quot;')" />

  <xsl:template match="nmaprun">
    <xsl:variable name="target" select="substring-after(@args, '-oX - ')" />
    <xsl:variable name="current" select="." />
    <xsl:variable name="init" select="document(concat($base, 'scans/', $name, '.xml'))/nmaprun" />

    <html lang="fr">
      <xsl:apply-templates select="." mode="head">
        <xsl:with-param name="base" select="$base" />
        <xsl:with-param name="name" select="$name" />
        <xsl:with-param name="target" select="$target" />
      </xsl:apply-templates>

      <body class="inverted">
        <xsl:apply-templates select="." mode="nav">
          <xsl:with-param name="target" select="$target" />
          <xsl:with-param name="name" select="$name" />
        </xsl:apply-templates>

        <main class="ui main container inverted vertical segment">

          <h1 class="ui header">
            <xsl:choose>
              <xsl:when test="$name">
                <xsl:value-of select="$name" disable-output-escaping="yes" />
                <div class="sub header">
                  <xsl:value-of select="$target" />
                </div>
              </xsl:when>
              <xsl:otherwise>
                <xsl:value-of select="$target" />
              </xsl:otherwise>
            </xsl:choose>
          </h1>

          <table id="scanResultsTable" style="width:100%" role="grid" class="ui sortable small compact stuck striped table">
            <thead>
              <tr>
                <th style="width: min-content">État</th>
                <th>Adresse IP</th>
                <th>Nom</th>
                <xsl:if test="host/address[@addrtype='mac']/@vendor">
                  <th>Constructeur</th>
                </xsl:if>
                <th class="six wide">Services</th>
                <th style="width: min-content" title="Scan intensif">
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

        <footer class="ui footer inverted segment">Résultat de la commande :<br />
        <code>
          <xsl:value-of select="@args" />
        </code>
      </footer>

      <script src="script.js"></script>
      <script>
var table = $('#scanResultsTable').DataTable({
    responsive: true,
    colReorder: true,
    fixedHeader: true,
    lengthMenu : [256, 512, 1024, 2048, { label: 'Tout', value: -1 }],
    language: {
        lengthMenu: 'Afficher _MENU_ résultats'
    },
    layout: {
        topStart: { search: {text: 'Filtrer', placeholder: 'Filtre'} },
        topEnd: {
            buttons: [
                'copy',
                'print',
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: ['csv', 'excel', 'pdf']
                },
            ],
        },
        bottomStart: 'pageLength',
        bottomEnd: 'paging',
        bottom2Start: 'info',
    },
    order: [[0, 'asc'], [1, 'asc']]
})

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
    <xsl:if test="../host/address[@addrtype='mac']/@vendor">
      <td>
        <xsl:value-of select="address[@addrtype='mac']/@vendor" />
      </td>
    </xsl:if>
    <td>
      <xsl:apply-templates select="$currentHost/ports/port[not(state/@state='closed')] | $initHost/ports/port[not(state/@state='closed')][not(@portid=$currentHost/ports/port/@portid)]">
        <xsl:with-param name="initHost" select="$initHost" />
        <xsl:with-param name="currentHost" select="$currentHost" />
        <xsl:with-param name="hostAddress" select="$hostAddress" />
        <xsl:sort select="number(@portid)" order="ascending" />
      </xsl:apply-templates>
    </td>
    <td style="width: min-content">
      <a class="ui mini icon teal icon button" target="_blank" title="Scan intensif">
        <xsl:attribute name="href">
          <xsl:text>scan.php?target=</xsl:text>
          <xsl:value-of select="$hostAddress" />
          <xsl:text>&amp;preset=host</xsl:text>
        </xsl:attribute>
        <i class="search plus icon"></i>
      </a>
    </td>
  </tr>
</xsl:template>

<xsl:template match="port">
  <xsl:param name="hostAddress" />
  <xsl:param name="initHost" />
  <xsl:param name="currentHost" />
  <xsl:variable name="portid" select="@portid" />
  <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]" />
  <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]" />

  <a target="_blank">
    <xsl:attribute name="class">
      <xsl:text>ui mini label </xsl:text>
      <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">dropdown button share-size </xsl:if>
      <xsl:choose>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500"> red</xsl:when>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400"> orange</xsl:when>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200"> green</xsl:when>
        <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
        <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
        <xsl:otherwise>red</xsl:otherwise>
      </xsl:choose>
    </xsl:attribute>
    <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">
      <xsl:attribute name="style">
        <xsl:for-each select="$currentPort/script[@id='smb-shares-size']/table">
          <xsl:sort select="elem[@key='FreeSize'] div elem[@key='TotalSize']" order="ascending" />
          <xsl:if test="position()=1">
            <xsl:text>--free: </xsl:text>
            <xsl:value-of select="elem[@key='FreeSize']" />
            <xsl:text>; --total: </xsl:text>
            <xsl:value-of select="elem[@key='TotalSize']" />
          </xsl:if>
        </xsl:for-each>
      </xsl:attribute>
    </xsl:if>
    <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https'">
      <xsl:attribute name="href">
        <xsl:choose>
          <xsl:when test="service/@name='http' and service/@tunnel='ssl'">
            <xsl:text>https</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="service/@name" />
          </xsl:otherwise>
        </xsl:choose>
        <xsl:text>://</xsl:text>
        <xsl:value-of select="$hostAddress" />
        <xsl:text>:</xsl:text>
        <xsl:value-of select="@portid" />
      </xsl:attribute>
    </xsl:if>
    <xsl:if test="service/@name='ms-wbt-server' or service/@name='msrpc'">
      <xsl:attribute name="href">
        <xsl:text>rdp.php?v=</xsl:text>
        <xsl:value-of select="$hostAddress" />
        <xsl:text>&amp;p=</xsl:text>
        <xsl:value-of select="@portid" />
      </xsl:attribute>
    </xsl:if>
    <xsl:attribute name="title">
      <xsl:value-of select="@protocol" />
      <xsl:text>:</xsl:text><xsl:value-of select="@portid" />
    </xsl:attribute>
    <xsl:choose>
      <xsl:when test="service/@name='unknown'">
        <xsl:choose>
          <xsl:when test="@protocol='tcp'">:</xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="substring(@protocol, 1, 1)" />
            <xsl:text>:</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:value-of select="@portid" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="service/@name" />
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">
      <i class="dropdown icon"></i>
      <div class="menu">
        <xsl:apply-templates select="$currentPort/script[@id='smb-shares-size']/table">
          <xsl:with-param name="hostAddress" select="$hostAddress" />
        </xsl:apply-templates>
      </div>
    </xsl:if>
  </a>
</xsl:template>

<xsl:template match="table">
  <xsl:param name="hostAddress" />
  <a class="item share-size" href="file://///{$hostAddress}/{@key}" target="_blank" rel="noopener noreferrer" style="--free: {elem[@key='FreeSize']}; --total: {elem[@key='TotalSize']}">
    <xsl:value-of select="@key" />
  </a>
</xsl:template>

</xsl:stylesheet>