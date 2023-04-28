<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">
<xsl:output method="text" encoding="UTF-8" indent="yes" />

<xsl:param name="site"/>

<xsl:template match="lanScan">
<xsl:text>nmap -v -T4 -p </xsl:text>
<xsl:apply-templates select="//service"/>
<xsl:text> --script "$DIR/http-info.nse" -oX "$DIR/</xsl:text>
<xsl:value-of select="@scanpath"/>
<xsl:text>.tmp" </xsl:text>
<xsl:apply-templates select="//host"/>
<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="service">
<xsl:value-of select="." />
<xsl:if test="position() != last()">
<xsl:text>,</xsl:text>
</xsl:if>
</xsl:template>

<xsl:template match="host">
<xsl:value-of select="@address" />
<xsl:if test="position() != last()">
<xsl:text> </xsl:text>
</xsl:if>
</xsl:template>

</xsl:stylesheet>