<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

  <xsl:import href="head.xsl" />
  <xsl:import href="nav.xsl" />
  <xsl:import href="services.xsl" />
  <xsl:import href="toast.xsl" />

  <xsl:output method="html" encoding="UTF-8" />
  <xsl:output indent="yes" />
  <xsl:strip-space elements='*' />

  <xsl:variable name="stylesheetURL"
    select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'), '&quot;')" />
  <xsl:variable name="base" select="concat($stylesheetURL, '/../../')" />

  <xsl:template match="nmaprun">
    <xsl:variable name="targets" select="substring-after(@args, '.xsl ')" />
    <xsl:variable
      name="current" select="." />
    <xsl:variable name="init"
      select="document(concat($base, 'scans/', translate($targets,'/', '!'), '.xml'))/nmaprun" />

    <html
      lang="fr">
      <xsl:apply-templates select="." mode="head">
        <xsl:with-param name="base" select="$base" />
        <xsl:with-param name="targets" select="$targets" />
      </xsl:apply-templates>

      <body>
        <xsl:apply-templates select="." mode="nav">
        </xsl:apply-templates>

        <main class="ui main container inverted segment">
          <xsl:apply-templates
            select="$current/host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]">
            <xsl:with-param name="init" select="$init" />
            <xsl:with-param name="current" select="$current" />
          </xsl:apply-templates>
        </main>

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
    <xsl:variable name="addr"
      select="address/@addr" />
    <xsl:variable name="initHost"
      select="$init/host[address/@addr=$addr]" />
    <xsl:variable name="currentHost"
      select="$current/host[address/@addr=$addr]" />
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

    <h1>
      <xsl:attribute name="class">
        <xsl:text>ui inverted header </xsl:text>
          <xsl:choose>
          <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
          <xsl:otherwise>red</xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:choose>
        <xsl:when test="hostnames/hostname/@name">
          <xsl:value-of select="hostnames/hostname/@name" />
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="address/@addr" />
        </xsl:otherwise>
      </xsl:choose>
    </h1>

    <table
      class="ui inverted table" style="width: max-content">
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
              <xsl:value-of select="os/osmatch/@name" />
            </td>
          </xsl:if>
          <xsl:if test="distance/@value">
            <td>
              <xsl:value-of select="distance/@value" />
              <xsl:text> rebonds</xsl:text>
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

    <xsl:if
      test="hostscript/script">
      <div class="ui inverted tree accordion">
        <div class="title">
          <i class="dropdown icon"></i> Informations supplémentaires </div>
        <div class="content">
          <xsl:apply-templates select="hostscript/script" />
        </div>
      </div>
    </xsl:if>

    <h2
      class="ui header">Services</h2>

    <div class="ui cards">
      <xsl:apply-templates
        select="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]">
        <xsl:with-param name="initHost" select="$initHost" />
        <xsl:with-param name="currentHost" select="$currentHost" />
        <xsl:with-param name="hostAddress" select="$hostAddress" />
      </xsl:apply-templates>
    </div>

  </xsl:template>

  <xsl:template match="port">
    <xsl:param name="hostAddress" />
    <xsl:param name="initHost" />
    <xsl:param name="currentHost" />
    <xsl:variable
      name="portid" select="@portid" />
    <xsl:variable name="initPort"
      select="$initHost/ports/port[@portid=$portid]" />
    <xsl:variable name="currentPort"
      select="$currentHost/ports/port[@portid=$portid]" />
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

    <div
      class="ui inverted card {$color}">
      <div class="content">
        <div class="header">
          <div class="ui {$color} ribbon label" style="text-transform: uppercase">
            <xsl:value-of select="@protocol" />
            <xsl:text>:</xsl:text>
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
      <xsl:if
        test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https' or service/@name='ms-wbt-server'">
        <a class="ui {$color} button">
          <xsl:attribute name="href" target="_blank">
            <xsl:choose>
              <xsl:when test="service/@name='ms-wbt-server'">
                <xsl:text>rdp.php?v=</xsl:text>
                <xsl:value-of select="$hostAddress" />
                <xsl:text>&amp;p=</xsl:text>
                <xsl:value-of
                  select="@portid" />
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
                <xsl:value-of
                  select="$hostAddress" />
                <xsl:text>:</xsl:text>
                <xsl:value-of select="@portid" />
              </xsl:otherwise>
            </xsl:choose>
          </xsl:attribute>
          <i
            class="external alternate icon"></i> Ouvrir </a>
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
            <xsl:apply-templates
              select="table" />
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

</xsl:stylesheet>