<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema" version="1.1">

    <xsl:template match="nmaprun" mode="head">
        <xsl:param name="base" />
        <xsl:param name="name" />
        <xsl:param name="targets" />

        <head>
            <meta charset="utf-8" />
            <base href="{$base}" />
            <meta http-equiv="refresh" content="300">
                <xsl:attribute name="content">
                    <xsl:text>300</xsl:text>
                <xsl:if test="$name">
                        <xsl:text>;URL=rescan.php?name=</xsl:text>
                    <xsl:value-of select="$name" />
                    </xsl:if>
                </xsl:attribute>
            </meta>
            <title>
                <xsl:choose>
                    <xsl:when test="$name">
                        <xsl:value-of select="$name" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="$targets" />
                    </xsl:otherwise>
                </xsl:choose>
            </title>
            <link rel="icon" href="favicon.ico" />
            <link rel="stylesheet" type="text/css"
                href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.3/dist/semantic.min.css" />
            <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css"
                rel="stylesheet" type="text/css" />
            <link
                href="https://cdn.datatables.net/v/se/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.3/fh-4.0.1/r-3.0.3/datatables.min.css"
                rel="stylesheet" />
            <link rel="stylesheet" type="text/css" href="style.css" />
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
            <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
            <script
                src="https://cdn.datatables.net/v/se/jszip-3.10.1/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.3/fh-4.0.1/r-3.0.3/datatables.min.js"></script>
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