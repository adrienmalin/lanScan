<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="1.1">

    <xsl:import href="parseCommand.xsl"/>

    <xsl:template match="nmaprun" mode="nav">
        <xsl:param name="basedir"/>
        <xsl:param name="targets"/>
        <xsl:param name="nextCompareWith"/>
        <xsl:param name="refreshPeriod"/>
        <xsl:param name="sudo"/>
        <nav class="ui inverted teal fixed menu">
            <a class="ui teal button item" href="{$basedir}">
                <xsl:text>lan</xsl:text>
                <svg class="logo" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs206"/><g id="g998" transform="matrix(0,0.04687491,-0.04687491,0,24,2.2682373e-5)"><g id="g147"><g id="g145"><path d="m 322.065,92.046 c -46.24,0 -83.851,37.619 -83.851,83.857 v 168.712 c 0,25.224 -21.148,45.745 -46.372,45.745 -25.224,0 -46.372,-20.521 -46.372,-45.745 V 199.464 h -38.114 v 145.151 c 0,46.24 38.246,83.859 84.486,83.859 46.24,0 84.486,-37.619 84.486,-83.859 V 175.903 c 0,-25.223 20.514,-45.743 45.737,-45.743 25.223,0 45.737,20.521 45.737,45.743 v 134.092 h 38.114 V 175.903 c 0,-46.239 -37.611,-83.857 -83.851,-83.857 z" id="path143"/></g></g><g id="g153"><g id="g151"><path d="M 144.198,0 H 108.625 C 98.101,0 89.568,8.746 89.568,19.271 c 0,1.157 0.121,2.328 0.318,3.598 h 73.052 c 0.197,-1.27 0.318,-2.441 0.318,-3.598 C 163.256,8.746 154.723,0 144.198,0 Z" id="path149"/></g></g><g id="g159"><g id="g157"><path d="m 420.183,486.591 h -71.731 c -0.626,2.541 -0.978,4.077 -0.978,6.176 0,10.525 8.532,19.234 19.057,19.234 h 35.573 c 10.525,0 19.057,-8.709 19.057,-19.234 0,-2.098 -0.352,-3.635 -0.978,-6.176 z" id="path155"/></g></g><g id="g165"><g id="g163"><rect x="87.027" y="41.925999" width="80.040001" height="138.481" id="rect161"/></g></g><g id="g171"><g id="g169"><rect x="344.93301" y="329.052" width="80.040001" height="138.481" id="rect167"/></g></g><g id="g173"></g><g id="g175"></g><g id="g177"></g><g id="g179"></g><g id="g181"></g><g id="g183"></g><g id="g185"></g><g id="g187"></g><g id="g189"></g><g id="g191"></g><g id="g193"></g><g id="g195"></g><g id="g197"></g><g id="g199"></g><g id="g201"></g></g></svg>
                <xsl:text>can</xsl:text>
            </a>
            <form id="lanScanForm" class="right menu">
                <xsl:call-template name="parseCommand">
                    <xsl:with-param name="argList" select="substring-before(substring-after(@args, ' -'), ' -oX')"/>
                    <xsl:with-param name="asURL" select="false()"/>
                </xsl:call-template>
                <div class="ui category search item">
                    <div id="targetsInputDiv" class="ui icon input">
                    <input class="prompt" type="text" id="targetsInput" name="targets" oninput="hiddenInput.value=this.value"
                        pattern="[a-zA-Z0-9._\/ \-]+" value="{$targets}" placeholder="Scanner un réseau..."
                        title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc.
Exemples: 192.168.1.0/24 scanme.nmap.org 10.0-255.0-255.1-254"/>
                        <i class="satellite dish icon"></i>
                    </div>
                    <input type="hidden" name="compareWith" value="{$nextCompareWith}"/>
                    <input type="hidden" name="refreshPeriod" value="{$refreshPeriod}"/>
                    <input type="hidden" name="sudo" value="{$sudo}"/>
                    <button id="hiddenButton" style="display: none;" type="submit" formmethod="get" formaction="{$basedir}/scan.php"></button>
                    <button id="refreshButton" class="ui teal icon submit button" type="submit" formmethod="get" formaction="{$basedir}/scan.php">
                        <i class="sync icon"></i>
                    </button>
                    <button class="ui teal icon submit button" type="submit" formmethod="get" formaction="{$basedir}/">
                        <i class="settings icon"></i>
                    </button>
                    <a class="ui teal icon button" href="https://nmap.org/man/fr/index.html" target="_blank">
                        <i class="question circle icon"></i>
                    </a>
                </div>
            </form>
        </nav>

        <script>
hiddenButton.onclick = function(event) {
    if (lanScanForm.checkValidity()) {
        targetsInputDiv.classList.add('loading')
        $.toast({
            title      : 'Scan en cours...',
            message    : 'Merci de patienter',
            class      : 'info',
            showIcon   : 'satellite dish',
            displayTime: 0,
            closeIcon  : true,
            position   : 'bottom right',
        })
    }
}
refreshButton.onclick = function(event) {
    refreshButton.getElementsByTagName('i')[0].className = 'loading spinner icon'
    $.toast({
        title      : 'Scan en cours...',
        message    : 'Merci de patienter',
        class      : 'info',
        showIcon   : 'satellite dish',
        displayTime: 0,
        closeIcon  : true,
        position   : 'bottom right',
    })
}
        </script>
    </xsl:template>
</xsl:stylesheet>