<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

  <xsl:import href="lib/head.xsl" />
  <xsl:import href="lib/nav.xsl" />
  <xsl:import href="lib/services.xsl" />
  <xsl:import href="lib/toast.xsl" />

  <xsl:output method="html" encoding="UTF-8" />
  <xsl:output indent="yes" />
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

        <main class="ui main container inverted segment">
          <xsl:apply-templates select="$current/host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]">
            <xsl:with-param name="init" select="$init" />
            <xsl:with-param name="current" select="$current" />
          </xsl:apply-templates>
        </main>

        <footer class="ui footer inverted segment"> Résultat de la commande :<br />
        <code>
          <xsl:value-of select="@args" />
        </code>
      </footer>

      <script src="script.js"></script>
      <script>
$('.ui.tree.accordion').accordion()
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

  <h1 class="ui header">
    <div>
      <xsl:attribute name="class">
        <xsl:text>ui horizontal label </xsl:text>
        <xsl:choose>
          <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
          <xsl:otherwise>red</xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:value-of select="$currentHost/status/@state" />
    </div>
    <xsl:choose>
      <xsl:when test="hostnames/hostname/@name">
        <xsl:value-of select="hostnames/hostname/@name" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="address/@addr" />
      </xsl:otherwise>
    </xsl:choose>
  </h1>

  <table class="ui inverted table" style="width: max-content">
    <thead>
      <tr>
        <xsl:if test="address[@addrtype='ipv4']/@addr">
          <th>Adresse IPv4</th>
        </xsl:if>
        <xsl:if test="address[@addrtype='mac']/@addr">
          <th>Adresse MAC</th>
        </xsl:if>
        <xsl:if test="address/@vendor">
          <th>Constructeur</th>
        </xsl:if>
        <xsl:if test="os/osmatch/@name">
          <th>OS</th>
        </xsl:if>
        <xsl:if test="distance/@value">
          <th>Distance</th>
        </xsl:if>
        <xsl:if test="uptime/@lastboot">
          <th>Dernier redémarrage</th>
        </xsl:if>
      </tr>
    </thead>
    <tbody>
      <tr>
        <xsl:if test="address[@addrtype='ipv4']/@addr">
          <td>
            <xsl:value-of select="address[@addrtype='ipv4']/@addr" />
          </td>
        </xsl:if>
        <xsl:if test="address[@addrtype='mac']/@addr">
          <td>
            <xsl:value-of select="address[@addrtype='mac']/@addr" />
          </td>
        </xsl:if>
        <xsl:if test="address/@vendor">
          <td>
            <xsl:value-of select="address/@vendor" />
          </td>
        </xsl:if>
        <xsl:if test="os/osmatch/@name">
          <td>
            <abbr title="Confiance : {os/osmatch/@accuracy}%">
              <xsl:value-of select="os/osmatch/@name" />
            </abbr>
          </td>
        </xsl:if>
        <xsl:if test="distance/@value">
          <td>
            <xsl:value-of select="distance/@value" />
            <xsl:text> étape(s)</xsl:text>
          </td>
        </xsl:if>
        <xsl:if test="uptime/@lastboot">
          <td>
            <xsl:value-of select="uptime/@lastboot" />
          </td>
        </xsl:if>
      </tr>
    </tbody>
  </table>

  <xsl:if test="hostscript/script">
    <div class="ui inverted tree accordion">
      <div class="title">
        <i class="dropdown icon"></i> Informations supplémentaires </div>
      <div class="content">
        <xsl:apply-templates select="hostscript/script" />
      </div>
    </div>
  </xsl:if>

  <h2 class="ui header">Services</h2>

  <div class="ui inverted two small cards">
    <xsl:apply-templates select="$currentHost/ports/port[not(state/@state='closed')] | $initHost/ports/port[not(state/@state='closed')][not(@portid=$currentHost/ports/port/@portid)]">
      <xsl:with-param name="initHost" select="$initHost" />
      <xsl:with-param name="currentHost" select="$currentHost" />
      <xsl:with-param name="hostAddress" select="$hostAddress" />
    </xsl:apply-templates>
  </div>

  <xsl:apply-templates select="trace" />
</xsl:template>

<xsl:template match="port">
  <xsl:param name="hostAddress" />
  <xsl:param name="initHost" />
  <xsl:param name="currentHost" />
  <xsl:variable name="portid" select="@portid" />
  <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]" />
  <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]" />
  <xsl:variable name="color">
    <xsl:choose>
      <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
      <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
      <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
      <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
      <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
      <xsl:otherwise>red</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>

  <div class="ui card {$color}">
    <div class="content">
      <div class="header">
        <div class="ui {$color} right floated label" title="{state/@state}">
          <div class="detail" style="text-transform: uppercase">
            <xsl:value-of select="@protocol" />
            <xsl:text>:</xsl:text>
          </div>
          <xsl:value-of select="@portid" />
        </div>
        <xsl:value-of select="service/@name" />
        <xsl:if test="service/@tunnel">
          <span>
            <xsl:text>/</xsl:text>
            <xsl:value-of select="service/@tunnel" />
          </span>
        </xsl:if>
      </div>
      <div class="meta">
        <xsl:if test="service/@product">
          <span>
            <xsl:value-of select="service/@product" />
          </span>
        </xsl:if>
        <xsl:if test="service/@version">
          <span>
            <xsl:text>v</xsl:text>
            <xsl:value-of select="service/@version" />
          </span>
        </xsl:if>
        <xsl:if test="service/@extrainfo">
          <span>
            <xsl:value-of select="service/@extrainfo" />
          </span>
        </xsl:if>
        <div class="description">
          <xsl:if test="script">
            <div class="ui inverted tree accordion">
              <div class="title">
                <i class="dropdown icon"></i> Détails </div>
              <div class="content">
                <xsl:apply-templates select="script" />
              </div>
            </div>
          </xsl:if>
        </div>
      </div>
    </div>
    <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https' or service/@name='ms-wbt-server' or service/@name='msrpc'">
      <a class="ui {$color} button" target="_blank">
        <xsl:attribute name="href">
          <xsl:choose>
            <xsl:when test="service/@name='ms-wbt-server' or service/@name='msrpc'">
              <xsl:text>rdp.php?v=</xsl:text>
              <xsl:value-of select="$hostAddress" />
              <xsl:text>&amp;p=</xsl:text>
              <xsl:value-of select="@portid" />
            </xsl:when>
            <xsl:otherwise>
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
            </xsl:otherwise>
          </xsl:choose>
        </xsl:attribute>
        <i class="external alternate icon"></i>
        <xsl:text>Ouvrir</xsl:text>
      </a>
    </xsl:if>
    <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">
      <div class="ui {$color} center aligned dropdown share-size button">
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
        <i class="external alternate icon"></i>
        <xsl:text>Ouvrir</xsl:text>
        <i class="dropdown icon"></i>
        <div class="menu">
          <xsl:apply-templates select="$currentPort/script[@id='smb-shares-size']/table">
            <xsl:with-param name="hostAddress" select="$hostAddress" />
          </xsl:apply-templates>
        </div>
      </div>
    </xsl:if>
  </div>

</xsl:template>

<xsl:template match="script">
  <div class="ui inverted accordion">
    <div class="title">
      <i class="dropdown icon"></i>
      <xsl:value-of select="@id" />
    </div>
    <div class="content">
      <xsl:choose>
        <xsl:when test="elem or table">
          <xsl:if test="elem">
            <table class="ui small compact inverted fixed definition table">
              <tbody>
                <xsl:apply-templates select="elem" />
              </tbody>
            </table>
          </xsl:if>
          <xsl:apply-templates select="table" />
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="@output" />
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </div>
</xsl:template>

<xsl:template match="table">
  <xsl:choose>
    <xsl:when test="@key">
      <div class="ui inverted accordion">
        <div class="title">
          <i class="dropdown icon"></i>
          <xsl:value-of select="@key" />
        </div>
        <div class="content">
          <xsl:if test="elem">
            <table class="ui small compact inverted fixed definition table">
              <tbody>
                <xsl:apply-templates select="elem" />
              </tbody>
            </table>
          </xsl:if>
          <xsl:apply-templates select="table" />
        </div>
      </div>
    </xsl:when>
    <xsl:when test="elem">
      <table class="ui small compact inverted fixed definition table">
        <tbody>
          <xsl:apply-templates select="elem" />
        </tbody>
      </table>
    </xsl:when>
  </xsl:choose>
</xsl:template>

<xsl:template match="elem">
  <tr>
    <td style="width: min-content">
      <xsl:value-of select="@key" />
    </td>
    <td>
      <xsl:value-of select="." />
    </td>
  </tr>
</xsl:template>

<xsl:template match="table">
  <xsl:param name="hostAddress" />
  <a class="item share-size" href="file://///{$hostAddress}/{@key}" target="_blank" rel="noopener noreferrer" style="--free: {elem[@key='FreeSize']}; --total: {elem[@key='TotalSize']}">
    <xsl:value-of select="@key" />
  </a>
</xsl:template>


<xsl:template match="trace">
  <h2 class="ui header">Traceroute</h2>

  <table class="ui inverted table">
    <thead>
      <tr>
        <th>Étape</th>
        <th>Adresse</th>
        <th>Temps</th>
      </tr>
    </thead>
    <tbody>
      <xsl:apply-templates select="hop" />
    </tbody>
  </table>
</xsl:template>

<xsl:template match="hop">
  <tr>
    <td>
      <xsl:value-of select="@ttl" />
    </td>
    <td>
      <xsl:choose>
        <xsl:when test="@host">
          <xsl:value-of select="@host" />
          <xsl:text> (</xsl:text>
          <xsl:value-of select="@ipaddr" />
          <xsl:text>)</xsl:text>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="@ipaddr" />
        </xsl:otherwise>
      </xsl:choose>
    </td>
    <td>
      <xsl:value-of select="@rtt" />
      <xsl:text> ms</xsl:text>
    </td>
  </tr>
</xsl:template>

</xsl:stylesheet>