<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:template match="runstats">
        <xsl:param name="init"/>
        <script>
            <xsl:if test="finished/@summary">
$.toast({
    title      : '<xsl:value-of select="finished/@exit"/>',
    message    : `<xsl:value-of select="finished/@summary"/>`,
    showIcon   : 'satellite dish',
    displayTime: 0,
    closeIcon  : true,
    position   : 'bottom right',
})
            </xsl:if>
            <xsl:if test="finished/@errormsg">
$.toast({
    title      : '<xsl:value-of select="finished/@exit"/>',
    message    : `<xsl:value-of select="finished/@errormsg"/>`,
    showIcon   : 'exclamation triangle',
    class      : 'error',
    displayTime: 0,
    closeIcon  : true,
    position   : 'bottom right',
})
            </xsl:if>
            <xsl:if test="$init/runstats/finished">
$.toast({
    message    : 'Comparaison avec les résultats du ' + new Date("<xsl:value-of select="$init/runstats/finished/@timestr"/>").toLocaleString(),
    class      : 'info',
    showIcon   : 'calendar',
    displayTime: 0,
    closeIcon  : true,
    position   : 'bottom right',
})
            </xsl:if>
        </script>
    </xsl:template>

</xsl:stylesheet>