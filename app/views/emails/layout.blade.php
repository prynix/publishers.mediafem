<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8">
            <title>@yield('title', 'Publishers Adtomatik - ')</title>

            <style type="text/css">
                body,td,th {
                    color: #444;
                    font-family: "Calibri", tahoma, helvetica, arial, sans-serif;
                    font-size: 15px;
                }
            </style>
    </head>
    <body bgcolor="#f5f5f5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <table bgcolor="#ffffff" width="70%" border="0" align="center" cellpadding="20" cellspacing="0">
            <tr>
                <td align="center" valign="middle" height="80">
                    <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">
                    @yield('content')

                    <p>
                        Sincerely,<br />
                        <b style="color: green;">Adtomatik</b><br />
                        http://www.adtomatik.com
                    </p>
                </td>
            </tr>
        </table>
        <table width="70%" border="0" align="center" cellpadding="20" cellspacing="0">
            <tr>
                <td align="left" valign="top">
                    <p style="font-size: 12px;">
                        Copyright © 2009 - {{ date('Y') }} AdTomatik by MediaFem LLC.<br />
                        11380 Prosperity Farms Road, 33410 - Palm Beach, Florida, USA<br />
                        Tel.: +1 786-315-9918
                    </p>
                </td>
            </tr>
        </table>
    </body>
</html>