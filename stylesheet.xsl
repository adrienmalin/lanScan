<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    version="2.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:output indent="yes" />
    <xsl:strip-space elements="*" />

    <xsl:template match="nmaprun">

        <html lang="fr">
            <head>
                <meta charset="utf-8" />
                <title><xsl:value-of select="./@args" /></title>
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <style>
                    a {
                        margin: 0 2px;
                    }
                </style>
                <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/>
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/fc-4.1.0/fh-3.2.3/r-2.3.0/rr-1.2.8/sc-2.0.6/datatables.min.css"/>
            </head>

            <body>

                <table id="scanResults" class="table table-striped table-hover compact caption-top" style="width:100%">
                    <thead>
                        <tr>
                            <th>Adresse IP</th>
                            <th>Nom DNS</th>
                            <th>Services</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/nmaprun/host[status/@state='up']">
                            <tr>
                                <td>
                                    <xsl:value-of select="address/@addr" />
                                </td>
                                <td>
                                    <xsl:value-of select="hostnames/hostname/@name" />
                                </td>
                                <td>
                                    <xsl:for-each select="ports/port[state/@state='open']">
                                        <a target="_blank" style="text-transform:uppercase;" type="button" class="btn btn-success btn-sm">
                                            <xsl:variable name="protocol">
                                                <xsl:choose>
                                                    <xsl:when test="service/@name='http' or service/@name='https' or service/@name='http-alt' or @portid = 8006 or @portid = 9292 or @portid = 20618">
                                                        <xsl:choose>
                                                            <xsl:when test="service/@tunnel='ssl' or script[@id='ssl-cert'] or script[@id='ssl-date']">
                                                                <xsl:text>https://</xsl:text>
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:text>http://</xsl:text>
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </xsl:when>
                                                    <xsl:when test="service/@name='ftp' or service/@name='ssh' or service/@name='telnet'">
                                                        <xsl:value-of select="service/@name" />
                                                        <xsl:text>://</xsl:text>
                                                    </xsl:when>
                                                    <xsl:when test="service/@name = 'microsoft-ds' or service/@name = 'netbios-ssn'">
                                                        <xsl:text>file://///</xsl:text>
                                                    </xsl:when>
                                                </xsl:choose>
                                            </xsl:variable>
                                            <xsl:choose>
                                                <xsl:when test="$protocol != ''">
                                                    <xsl:attribute name="href">
                                                        <xsl:value-of select="$protocol" />
                                                        <xsl:choose>
                                                            <xsl:when test="count(../../hostnames/hostname) > 0">
                                                                <xsl:value-of select="../../hostnames/hostname/@name" />
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <xsl:value-of select="../../address/@addr" />
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                        <xsl:text>:</xsl:text>
                                                        <xsl:value-of select="@portid"/>
                                                    </xsl:attribute>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:attribute name="class">
                                                        btn btn-success btn-sm disabled
                                                    </xsl:attribute>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                            <xsl:attribute name="title">
                                                <xsl:for-each select="service/@*">
                                                    <xsl:value-of select="concat(name(), ': ', ., ', ')"/>
                                                </xsl:for-each>
                                            </xsl:attribute>
                                            <span class="badge bg-secondary rounded-pill"><xsl:value-of select="@portid"/></span>
                                            <xsl:value-of select="service/@name" />
                                        </a>
                                    </xsl:for-each>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </tbody>
                    <caption>
                        <pre class="mb-0" style="white-space:pre-wrap; word-wrap:break-word;">
                            <xsl:value-of select="/nmaprun/@args" />
                        </pre>
                        <time>
                            <xsl:value-of select="/nmaprun/@startstr" />
                        </time> - <time>
                            <xsl:value-of select="/nmaprun/runstats/finished/@timestr" />
                        </time><br />
                        <small>
                            <xsl:value-of select="/nmaprun/@scanner" /> v
                            <xsl:value-of select="/nmaprun/@version" />
                        </small>
                    </caption>

                </table>
            
                <script
                    src="https://code.jquery.com/jquery-3.6.0.min.js"
                    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                    crossorigin="anonymous">
                </script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/fc-4.1.0/fh-3.2.3/r-2.3.0/rr-1.2.8/sc-2.0.6/datatables.min.js"></script>
                <script>
                    $(document).ready( function() {
                        $('#scanResults').DataTable({
                            fixedHeader: true,
                            lengthMenu: [
                                [256, 512, 1024, 2048, -1],
                                [256, 512, 1024, 2048, "All"]
                            ],
                            scrollCollapse: true,
                            paging: false,
                            responsive: true,
                        });
                    } );
                </script>
            </body>

        </html>
    </xsl:template>
</xsl:stylesheet>