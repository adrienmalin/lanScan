<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:template match="port" mode="service">
        <xsl:param name="hostAddress"/>
        <xsl:param name="initHost"/>
        <xsl:param name="currentHost"/>
        <xsl:param name="class"/>
        <xsl:variable name="portid" select="@portid"/>
        <xsl:variable name="initPort" select="$initHost/ports/port[@portid=$portid]"/>
        <xsl:variable name="currentPort" select="$currentHost/ports/port[@portid=$portid]"/>

        <a target="_blank">
            <xsl:attribute name="class">
                <xsl:value-of select="$class"/>
                <xsl:text> </xsl:text>
                <xsl:choose>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=500">red</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=400">orange</xsl:when>
                    <xsl:when test="$currentPort/script[@id='http-info']/elem[@key='status']>=200">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='open'">green</xsl:when>
                    <xsl:when test="$currentPort/state/@state='filtered'">orange</xsl:when>
                    <xsl:otherwise>red</xsl:otherwise>
                </xsl:choose>
                <xsl:choose>
                    <xsl:when test="$currentPort/script[@id='smb-shares-size']/table"> mini dropdown button share-size</xsl:when>
                    <xsl:otherwise> small</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">
                <xsl:attribute name="style">
                    <xsl:for-each select="$currentPort/script[@id='smb-shares-size']/table">
                        <xsl:sort select="elem[@key='FreeSize'] div elem[@key='TotalSize']" order="ascending"/>
                        <xsl:if test="position()=1">
                            <xsl:text>--free: </xsl:text>
                            <xsl:value-of select="elem[@key='FreeSize']"/>
                            <xsl:text>; --total: </xsl:text>
                            <xsl:value-of select="elem[@key='TotalSize']"/>
                        </xsl:if>
                    </xsl:for-each>
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="service/@name='ftp' or service/@name='ssh' or service/@name='http' or service/@name='https'">
                <xsl:attribute name="href">
                    <xsl:value-of select="service/@name"/>
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
            <xsl:attribute name="title">
                <xsl:value-of select="@portid"/>/<xsl:value-of select="@protocol"/>
            </xsl:attribute>
            <xsl:choose>
                <xsl:when test="service/@name='unknown'">
                    <xsl:choose>
                        <xsl:when test="@protocol='tcp'">:</xsl:when>
                        <xsl:otherwise><xsl:value-of select="substring(@protocol, 1, 1)"/>:</xsl:otherwise>
                    </xsl:choose>
                    <xsl:value-of select="@portid"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="service/@name"/>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="$currentPort/script[@id='smb-shares-size']/table">
                <i class="dropdown icon"></i>
                <div class="menu">
                    <xsl:apply-templates select="$currentPort/script[@id='smb-shares-size']/table">
                        <xsl:with-param name="hostAddress" select="$hostAddress"/>
                    </xsl:apply-templates>
                </div>
            </xsl:if>
        </a>
    </xsl:template>

    <xsl:template match="table">
        <xsl:param name="hostAddress"/>
        <a class="item share-size" href="file://///{$hostAddress}/{@key}" target="_blank" rel="noopener noreferrer" style="--free: {elem[@key='FreeSize']}; --total: {elem[@key='TotalSize']}">
            <xsl:value-of select="@key"/>
        </a>
    </xsl:template>
</xsl:stylesheet>