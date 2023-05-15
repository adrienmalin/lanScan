<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">
<xsl:output method="text" encoding="UTF-8" indent="yes" />

<xsl:param name="network"/>

<xsl:template match="nmaprun">
<xsl:text>---
site: Nom du site

group:
  - name: Réseau </xsl:text><xsl:value-of select="$network"/><xsl:text>
    host:
</xsl:text>
<xsl:apply-templates select="host"/>
</xsl:template>

<xsl:template match="host">
<xsl:text>      - address: </xsl:text>
<xsl:choose>
<xsl:when test="hostnames/hostname/@name"><xsl:value-of select="hostnames/hostname/@name" /></xsl:when>
<xsl:otherwise>  <xsl:value-of select="address/@addr" /></xsl:otherwise>
</xsl:choose>
        service: [<xsl:apply-templates select="ports/port"/>]
</xsl:template>

<xsl:template match="port">
<xsl:value-of select="service/@name" />
<xsl:if test="position() != last()">
<xsl:text>, </xsl:text>
</xsl:if>
</xsl:template>

</xsl:stylesheet>