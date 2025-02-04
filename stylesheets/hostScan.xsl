<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

  <xsl:import href="head.xsl" />
  <xsl:import href="services.xsl" />
  <xsl:import href="toast.xsl" />

  <xsl:output method="html" encoding="UTF-8" />
  <xsl:output indent="yes" />
  <xsl:strip-space elements='*' />

  <xsl:variable name="stylesheetURL" select="substring-before(substring-after(processing-instruction('xml-stylesheet'),'href=&quot;'), '&quot;')" />
  <xsl:variable name="base" select="concat($stylesheetURL, '/../../')" />

  <xsl:template match="nmaprun">
    <xsl:variable name="targets" select="substring-after(@args, '.xsl ')" />
    <xsl:variable name="current" select="." />
    <xsl:variable name="init" select="document(concat($base, 'scans/', translate($targets,'/', '!'), '.xml'))/nmaprun" />

    <html lang="fr">
      <xsl:apply-templates select="." mode="head">
          <xsl:with-param name="base" select="$base"/>
          <xsl:with-param name="targets" select="$targets"/>
      </xsl:apply-templates>

      <body>
        <nav class="ui inverted secondary menu">
          <h3>
            <a href="." class="button item logo">lan<svg class="logo" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="40" height="40"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:svg="http://www.w3.org/2000/svg">
              <defs id="defs206"></defs>
              <g id="g998" transform="matrix(0,0.04687491,-0.04687491,0,24,2.2682373e-5)">
                <g id="g147">
                  <g id="g145">
                    <path d="m 322.065,92.046 c -46.24,0 -83.851,37.619 -83.851,83.857 v 168.712 c 0,25.224 -21.148,45.745 -46.372,45.745 -25.224,0 -46.372,-20.521 -46.372,-45.745 V 199.464 h -38.114 v 145.151 c 0,46.24 38.246,83.859 84.486,83.859 46.24,0 84.486,-37.619 84.486,-83.859 V 175.903 c 0,-25.223 20.514,-45.743 45.737,-45.743 25.223,0 45.737,20.521 45.737,45.743 v 134.092 h 38.114 V 175.903 c 0,-46.239 -37.611,-83.857 -83.851,-83.857 z" id="path143"></path>
                  </g>
                </g>
                <g id="g153">
                  <g id="g151">
                    <path d="M 144.198,0 H 108.625 C 98.101,0 89.568,8.746 89.568,19.271 c 0,1.157 0.121,2.328 0.318,3.598 h 73.052 c 0.197,-1.27 0.318,-2.441 0.318,-3.598 C 163.256,8.746 154.723,0 144.198,0 Z" id="path149"></path>
                  </g>
                </g>
                <g id="g159">
                  <g id="g157">
                    <path d="m 420.183,486.591 h -71.731 c -0.626,2.541 -0.978,4.077 -0.978,6.176 0,10.525 8.532,19.234 19.057,19.234 h 35.573 c 10.525,0 19.057,-8.709 19.057,-19.234 0,-2.098 -0.352,-3.635 -0.978,-6.176 z" id="path155"></path>
                  </g>
                </g>
                <g id="g165">
                  <g id="g163">
                    <rect x="87.027" y="41.925999" width="80.040001" height="138.481" id="rect161"></rect>
                  </g>
                </g>
                <g id="g171">
                  <g id="g169">
                    <rect x="344.93301" y="329.052" width="80.040001" height="138.481" id="rect167"></rect>
                  </g>
                </g>
                <g id="g173"></g>
                <g id="g175"></g>
                <g id="g177"></g>
                <g id="g179"></g>
                <g id="g181"></g>
                <g id="g183"></g>
                <g id="g185"></g>
                <g id="g187"></g>
                <g id="g189"></g>
                <g id="g191"></g>
                <g id="g193"></g>
                <g id="g195"></g>
                <g id="g197"></g>
                <g id="g199"></g>
                <g id="g201"></g>
              </g>
            </svg>
    can</a>
        </h3>
      </nav>

      <main class="ui main container inverted segment">
        <xsl:apply-templates select="$current/host | $init/host[not(address/@addr=$current/host/address/@addr)][not(status/@state='down')]">
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
    <xsl:choose>
      <xsl:when test="hostnames/hostname/@name">
        <xsl:value-of select="hostnames/hostname/@name" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="address/@addr" />
      </xsl:otherwise>
    </xsl:choose>
    <span>
      <xsl:attribute name="class">
        <xsl:text>ui label </xsl:text>
        <xsl:choose>
          <xsl:when test="$currentHost/status/@state='up'">green</xsl:when>
          <xsl:otherwise>red</xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:value-of select="$currentHost/status/@state" />
    </span>
  </h1>

  <table class="ui inverted table" style="width: max-content">
    <thead>
      <tr>
        <th>Adresse IPv4</th>
        <th>Adresse MAC</th>
        <th>Constructeur</th>
        <th>OS</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <xsl:value-of select="address[@addrtype='ipv4']/@addr" />
        </td>
        <td>
          <xsl:value-of select="address[@addrtype='mac']/@addr" />
        </td>
        <td>
          <xsl:value-of select="address/@vendor" />
        </td>
        <td>
          <xsl:value-of select="os/osmatch/@name" />
        </td>
      </tr>
    </tbody>
  </table>

  <div class="ui inverted tree accordion">
    <div class="title">
      <i class="dropdown icon"></i>
      Informations supplémentaires
    </div>
    <div class="content">
      <xsl:apply-templates select="hostscript/script" />
    </div>
  </div>

  <h2 class="ui header">Services</h2>

  <div class="ui cards">
    <xsl:apply-templates select="$currentHost/ports/port | $initHost/ports/port[not(@portid=$currentHost/ports/port/@portid)][not(state/@state='closed')]">
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
  <xsl:variable name="portid" select="@portid" />
  <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]" />
  <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]" />

  <div>
    <xsl:attribute name="class">
      <xsl:text>ui inverted card </xsl:text>
      <xsl:choose>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
        <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
        <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
        <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
        <xsl:otherwise>red</xsl:otherwise>
      </xsl:choose>
    </xsl:attribute>
    <div class="content">
      <div class="header">
        <div style="text-transform: uppercase">
          <xsl:attribute name="class">
            <xsl:text>ui red ribbon label </xsl:text>
            <xsl:choose>
              <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
              <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
              <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
              <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
              <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
              <xsl:otherwise>red</xsl:otherwise>
            </xsl:choose>
          </xsl:attribute>
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
                <i class="dropdown icon"></i>
                Détails
              </div>
              <div class="content">
                <xsl:apply-templates select="script" />
              </div>
            </div>
          </xsl:if>
        </div>
      </div>
    </div>
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
            <table class="ui small inverted fixed definition table">
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
            <table class="ui small inverted fixed definition table">
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
      <table class="ui small inverted fixed definition table">
        <tbody>
          <xsl:apply-templates select="elem" />
        </tbody>
      </table>
    </xsl:when>
  </xsl:choose>
</xsl:template>

<xsl:template match="elem">
  <tr>
    <td>
      <xsl:value-of select="@key" />
    </td>
    <td>
      <xsl:value-of select="." />
    </td>
  </tr>
</xsl:template>

</xsl:stylesheet>