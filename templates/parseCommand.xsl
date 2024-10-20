<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:template name="parseCommand">
        <xsl:param name="argList" select=""/>
        <xsl:param name="asURL" select="false()"/>
        <xsl:variable name="nextArgs" select="substring-after($argList, ' -')"/>
        <xsl:variable name="argAndValue">
            <xsl:choose>
                <xsl:when test="$nextArgs">
                    <xsl:value-of select="substring-before($argList, ' -')"/>
                </xsl:when>
                <xsl:otherwise><xsl:value-of select="$argList"/></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:choose>
            <xsl:when test="starts-with($argAndValue, '-')">
                <xsl:choose>
                    <xsl:when test="contains($argAndValue, ' ')">
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring-before($argAndValue, ' ')"/>
                            <xsl:with-param name="value" select="substring-after($argAndValue, ' ')"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="$argAndValue"/>
                            <xsl:with-param name="value" select="on"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="starts-with($argAndValue, 'P') or starts-with($argAndValue, 's') or starts-with($argAndValue, 'o')">
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring($argAndValue, 1, 2)"/>
                            <xsl:with-param name="value" select="substring($argAndValue, 3)"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="input">
                            <xsl:with-param name="name" select="substring($argAndValue, 1, 1)"/>
                            <xsl:with-param name="value" select="substring($argAndValue, 2)"/>
                            <xsl:with-param name="asURL" select="$asURL"/>
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>

        <xsl:if test="$nextArgs">
            <xsl:call-template name="parseCommand">
                <xsl:with-param name="argList" select="$nextArgs"/>
                <xsl:with-param name="asURL" select="$asURL"/>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>

    <xsl:template name="input">
        <xsl:param name="name"/>
        <xsl:param name="value" select=""/>
        <xsl:param name="asURL" select="false()"/>
        <xsl:choose>
            <xsl:when test="$asURL">
                <xsl:text>-</xsl:text>
                <xsl:value-of select="$name"/>
                <xsl:text>=</xsl:text>
                <xsl:choose>
                    <xsl:when test="$value"><xsl:value-of select="$value"/></xsl:when>
                    <xsl:otherwise>on</xsl:otherwise>
                </xsl:choose>
                <xsl:text>&amp;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <input type="hidden" name="-{$name}">
                    <xsl:attribute name="value">
                        <xsl:choose>
                            <xsl:when test="$value"><xsl:value-of select="$value"/></xsl:when>
                            <xsl:otherwise>on</xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                </input>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>