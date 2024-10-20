<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:import href="parseCommand.xsl"/>

    <xsl:template match="nmaprun" mode="head">
        <xsl:param name="basedir"/>
        <xsl:param name="targets"/>
        <xsl:param name="nextCompareWith"/>
        <xsl:param name="refreshPeriod"/>
        <xsl:param name="sudo"/>

        <head>
            <meta charset="utf-8"/>
            <xsl:if test="$refreshPeriod > 0">
                <meta http-equiv="refresh">
                    <xsl:attribute name="content">
                        <xsl:value-of select="$refreshPeriod"/>
                        <xsl:text>;URL=</xsl:text>
                        <xsl:value-of select="$basedir"/>
                        <xsl:text>/scan.php?targets=</xsl:text>
                        <xsl:value-of select="$targets"/>
                        <xsl:text>&amp;</xsl:text>
                        <xsl:call-template name="parseCommand">
                            <xsl:with-param name="argList" select="substring-before(substring-after(@args, ' -'), ' -oX')"/>
                            <xsl:with-param name="asURL" select="true()"/>
                        </xsl:call-template>
                        <xsl:text>compareWith=</xsl:text>
                        <xsl:value-of select="$nextCompareWith"/>
                        <xsl:text>&amp;refreshPeriod=</xsl:text>
                        <xsl:value-of select="$refreshPeriod"/>
                        <xsl:text>&amp;sudo=</xsl:text>
                        <xsl:value-of select="$sudo"/>
                    </xsl:attribute>
                </meta>
            </xsl:if>
            <title>
                <xsl:text>lanScan - </xsl:text>
                <xsl:value-of select="$targets"/>
            </title>
            <link rel="icon" href="{$basedir}/favicon.ico"/>
            <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css"/>
            <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
            <link href="https://cdn.datatables.net/v/se/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.3/fh-4.0.1/r-3.0.3/datatables.min.css" rel="stylesheet"/>
            <link href="{$basedir}/style.css" rel="stylesheet" type="text/css"/>
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
            <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/v/se/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.3/fh-4.0.1/r-3.0.3/datatables.min.js"></script>
            <script>
DataTable.ext.type.detect.unshift(function (d) {
    return /[\d]+\.[\d]+\.[\d]+\.[\d]+/.test(d)
        ? 'ipv4-address'
        : null;
});
    
DataTable.ext.type.order['ipv4-address-pre'] = function (ipAddress) {
    [a, b, c, d] = ipAddress.split(".").map(Number)
    return 16777216*a + 65536*b + 256*c + d;
};
            </script>
        </head>
    </xsl:template>
</xsl:stylesheet>