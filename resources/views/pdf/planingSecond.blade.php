<html>
<head>
<style type="text/css">
    @page {
        size: 123.8  175.0 cm;
        margin: 0;
    }
    body{
        background-color:#F7F9FC;
        font-family: 'Lato', Helvetica, sans-serif;
        font-size: 80px;
    }
    #container{
        background-color:#fff;
        padding: 100px 150px 5px 150px;
        margin: 0px 100px 0px 100px;
        border-radius: 50px;
        border-style: groove;
        border-color: #e2e7ed;
        border-width: 1px;
    }
    h1{
        color:#5C7C9D;
        font-size:100px;
    }
    .gray-speacer{
        background-color:#fff;
        height:100px;
        width:100000px;
    }
    .t-title td{
        width: 5300px;
    }
    .t-header{
        color:#5C7C9D;

    }
    .t-header td{
        width: 1300px;
    }
    .t-header .first-table td{
        color:#a4a7ab;
    }
    .tg .first-table td{
        background-color:#e2e7ed;
    }
    .tg .tg-0pky{border-color:#e2e7ed;text-align:left;vertical-align:top}

    .zurcher-logo{
        width: 700px;
    }
    .house-logo{
        width: 400px;
    }
    .t-total td {
        background-color:#5C7C9D;
        color: #fff;
    }
    .t-total .first-table td{
        background-color:#e2e7ed;
        color:#5C7C9D;
    }
    .t-bemerkungen td{
        width:1855px;
        
    }
    .p-bemerkungen{
        padding:10px;
        width:3800px;
        color:#5C7C9D;
        
    }
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{
        font-size:25px;
        padding:18px 20px;
        border-style:solid;
        border-width:1px;
        overflow:hidden;
        word-break:normal;
        border-color:#e2e7ed;
        width:210px;
        color:#5C7C9D;
    }
    .tg th{
        font-size:25px;
        font-weight:normal;
        padding:9px 5px;
        border-style:solid;
        border-width:1px;
        overflow:hidden;
        word-break:normal;
        border-color:#5C7C9D;
        color:#5C7C9D;
        }
    .tg .tg-zv4m{

        border-color:#5C7C9D;
        border-style:solid;
        border-width:1px;   
        border-top: none ;
        border-left: none;
        text-align:left;
        vertical-align:top}
    .tg .tg-15li{background-color:#e2e7ed;border-right-color:#BDCAD9;border-top-color:#BDCAD9;text-align:left;vertical-align:top;font-size:35px;width:120px;}
    .tg .tg-drlf{border-left-color:#5C7C9D;}
    .tg .tg-drrh{border-right-color:#5C7C9D;}
    .tg .th-spwh{background-color:#fff;}
    .tg .tg-lightgb{background-color:#f4f7fa}
    .tg .tg-1ans{background-color:#e2e7ed;text-align:center;vertical-align:top;font-size:35px;}
    .tg .tg-0lax{text-align:left;vertical-align:top}
    .tg .tg-space{text-align:left;vertical-align:top; border-top: none; border-right:none; border-bottom:none; border-left:none;}
    .tg .tg-space-fst{text-align:left;vertical-align:top; border-right: none; border-bottom: none; border-color: #5C7C9D;}
    .tg .tg-space-fnl{text-align:left;vertical-align:top; border-left: none; border-right: solid; border-bottom: none; border-color: #5C7C9D;}
    .font-35{font-size:35px;}
</style>



</head>
<body>
<div class="gray-speacer"></div>
<div id="container">
    <table class="t-title">
        <tr>    
            <td><h1>MEDIA-PLANUNG: ONLINE</h1></td>
            <td><img class="zurcher-logo" style="max-height: 450px;width: auto;" src="{{ public_path() }}{{ $logo }}" /></td>
        </tr>
    </table>

    <br><br>

    <table  class="t-header">
        <tr class="first-table">
            <td>Kampagne</td>
            <td>Laufzeit</td>
            <td> </td>
            @if ($type == 'internal')
            <td>Datum</td>
            @else
            <td>Version / Datum</td>
            @endif
        </tr>
        <tr>
            <td>{{ $header[0] }}</td>
            <td>{{ $header[1] }}</td>
            <td></td>
            <td>{{ $header[2] }}</td>
        </tr>
    </table>

    <br><br>


    <table class="tg">
        <tr>
            <td class="tg-space" style="border-left:solid;border-top:solid;">&nbsp;</td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="border-top:solid;"></td>
            <td class="tg-space" style="font-size:35px;border-top:solid;font-weight:bold;" colspan="2">Mediakosten net:</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;text-align:right;font-weight:bold;">{{ $total_right[0] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-right:solid;text-align:right;font-weight:bold;">{{ $total_right[1] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-space" style="border-left:solid;">&nbsp;</td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;"colspan="2">Planung & Setup:</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;text-align:right;font-weight:bold;">17 x CHF 175</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;border-right:solid;text-align:right;font-weight:bold;">>{{ $total_right[3] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-15li tg-drlf" style="font-weight:bold;">SPRACHE</td>
            <td class="tg-15li" style="font-weight:bold;">WERBEDRUCK</td>
            <td class="tg-15li" style="font-weight:bold;">ANTEIL IN %</td>
            <td class="tg-15li" style="font-weight:bold;">KOSTEN N/N</td>
            <td class="tg-15li" style="font-weight:bold;">ANTEIL IN %</td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;" colspan="2">Reporting & Optimierung:</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;text-align:right;font-weight:bold;">{{ $total_right[4] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;border-right:solid;text-align:right;font-weight:bold;">>{{ $total_right[5] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-space" style="border-left:solid;font-size:35px;font-weight:bold;">D-CH</td>
            <td class="tg-space" style="font-size:35px;font-weight:bold;">{{ $total_left[0] }}</td>
            <td class="tg-space" style="font-size:35px;font-weight:bold;">{{ $total_left[1] }}</td>
            <td class="tg-space" style="font-size:35px;font-weight:bold;">{{ $total_left[2] }}</td>
            <td class="tg-space" style="font-size:35px;font-weight:bold;">{{ $total_left[3] }}</td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;" colspan="2">Media - Honorar:</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;text-align:right;font-weight:bold;">{{ $total_right[6] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;border-right:solid;text-align:right;font-weight:bold;">>{{ $total_right[7] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-space" style="border-left:solid;font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">F-CH</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[4] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[5] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[6] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[7] }}</td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;" colspan="2">Technische Kosten:</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;text-align:right;font-weight:bold;">{{ $total_right[8] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;border-right:solid;text-align:right;font-weight:bold;">>{{ $total_right[9] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-space" style="border-left:solid;font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">I-CH</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[8] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[9] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[10] }}</td>
            <td class="tg-space" style="font-size:35px;border-top:solid;border-top-color:#e2e7ed;font-weight:bold;">{{ $total_left[11] }}</td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-15li" style="font-weight:bold;" colspan="2">Total Kosten Agentur Service:</td>
            <td class="tg-15li" style="text-align:right;font-weight:bold;">{{ $total_right[10] }}</td>
            <td class="tg-15li" style="border-right:solid;text-align:right;font-weight:bold;">{{ $total_right[11] }}</td>
            <td class="tg-space"></td>
        </tr>
        <tr>
            <td class="tg-15li" style="border-left:solid;border-bottom:solid;font-weight:bold;">Total</td>
            <td class="tg-15li" style="border-bottom:solid;font-weight:bold;">{{ $total_left[12] }}</td>
            <td class="tg-15li" style="border-bottom:solid;font-weight:bold;">{{ $total_left[13] }}</td>
            <td class="tg-15li" style="border-bottom:solid;font-weight:bold;">{{ $total_left[14] }}</td>
            <td class="tg-15li" style="border-bottom:solid;font-weight:bold;">{{ $total_left[15] }}</td>
            <td class="tg-space"style="border-bottom:solid;font-weight:bold;"></td>
            <td class="tg-space"style="border-bottom:solid;font-weight:bold;"></td>
            <td class="tg-space"style="border-bottom:solid;font-weight:bold;"></td>
            <td class="tg-15li" style="border-bottom:solid;font-weight:bold;" colspan="2">Total Kosten Agentur Service:</td>
            <td class="tg-15li" style="border-bottom:solid;text-align:right;font-weight:bold;">{{ $total_right[12] }}</td>
            <td class="tg-15li" style="border-right:solid;border-bottom:solid;text-align:right;">{{ $total_right[13] }}</td>
            <td class="tg-space"></td>
        </tr>
    </table>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <table class="t-title">
        <tr>    
            <td> </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img class="house-logo" src="{{ public_path() }}/icons/amo-logo.png" /></td>
        </tr>
    </table>
</div>
</body>
</html>