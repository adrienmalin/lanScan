<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">
<xsl:output method="text" encoding="UTF-8" indent="yes" />

<xsl:param name="site" />
<xsl:param name="network" />

<xsl:template match="nmaprun">
<xsl:text>---
site: </xsl:text><xsl:value-of select="$site" /><xsl:text>

</xsl:text><xsl:value-of select="$network" /><xsl:text>:
</xsl:text>
<xsl:apply-templates select="host"/>
</xsl:template>

<xsl:template match="host">
<xsl:text>  </xsl:text>
<xsl:choose>
<xsl:when test="hostnames/hostname/@name"><xsl:value-of select="hostnames/hostname/@name" /></xsl:when>
<xsl:otherwise>  <xsl:value-of select="address/@addr" /></xsl:otherwise>
</xsl:choose>: [<xsl:apply-templates select="ports/port"/>]
</xsl:template>

<xsl:template match="port">
<xsl:value-of select="service/@name" />
<xsl:text>, </xsl:text>
</xsl:template>

</xsl:stylesheet>