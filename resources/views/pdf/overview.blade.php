<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
<style type="text/css">
    @page {
        size: 123.8  175.0 cm;
        margin: 0;
    }

    body{
        background-color:#fff;
        font-family: 'Lato', sans-serif !important;
        font-size: 70px;
    }
    #container{
        background-color:#fff;
        padding: 100px;
        margin: 0px;
    }
    h1{
        color:#5C7C9D;
        font-size:120px;
        font-weight: 500;
    }
    .gray-speacer{
        background-color:#ffffff;
        height:100px;
        width:100000px;
    }
    .t-title{width: 100%}
    .t-title td{
        width: 50%;
    }
    .t-header{
        color:#5C7C9D;

    }
    .t-header strong{
        color: #A4B7CB!important;
    }
    .t-header td{
        width: 800px;
        font-weight: 500;
    }
    .t-header .first-table td{
        color:#a4a7ab;
    }
    .tg  {border-collapse:collapse;border-spacing:0;border-color:#DAE6EB;}
    .tg td{
        font-size:70px;
        padding:30px 50px;
        border-style:none;
        border-top-style: solid;
        border-width: 5px;
        border-color:#DAE6EB;
        color:#5C7C9D;
        width: 900px;
    }
    .tg .first-table td{
        background-color:#587B99;
    }
    .tg .tg-0pky{border-color:#DAE6EB;text-align:left;vertical-align:top;}
    .tg .tg-0pkt{border-color:#DAE6EB;text-align:left;vertical-align:top;background-color:#a4b7cb;}
    .tg .tg-0pky strong{font-weight: bold;}


    tr.first-table td{
        color: #fff;
    }

    .zurcher-logo{
        width: 700px;
    }
    .house-logo{
        width: 480px;
    }
    .t-total td {
        background-color:#5C7C9D;
        color: #fff;
    }
    .t-total tr:first-child {

    }
    .t-total .first-table td{
        background-color:#587B99;
    }
    .t-bemerkungen td{
        width:100%;

    }
    .t-total tr{
        margin: 0px;
    }

    .hrbg{
        border-top: 4px solid #BDCAD9;
        margin-bottom: 20px;
        height:5px;
        width: 100%;

    }

    .tr_highlight{
        background: #DAE6EB;
    }
    .rounded-circle{border-radius:3px;}

    .bt_border{
        border-top:4px solid #DAE6EB!important;
    }
    .br_border{
        border-right:4px solid #f1f1f1!important;
    }
    .bb_border{
        border-bottom:4px solid #DAE6EB!important;
    }
    .bl_border{
        border-left:4px solid #DAE6EB!important;
    }
    .bt_border2{
        border-top:3px solid #DAE6EB!important;
    }
    .br_border2{
        border-right:3px solid #DAE6EB!important;
    }
    .bb_border2{
        border-bottom:3px solid #DAE6EB!important;
    }
    .bl_border2{
        border-left:3px solid #DAE6EB!important;
    }

    .tg tr td:last-child{
        border-right: 0px!important;
    }

    .cell_border{
        border:4px solid #DAE6EB!important;
    }

    .text-right{
        text-align: right!important;
    }

    .border-top-left-right{
        border-top:4px solid #5C7C9D ;
        border-left:4px solid #5C7C9D ;
        border-right:4px solid #5C7C9D ;
    }
    .w120{width: 120px;}
    .w250{width: 250px}
    .w500{width: 500px}

    .nowrap{
        white-space: nowrap;
    }

    table{width: 100%}
    strong.sub{font-weight: 500;
    font-size: 80px;}
    .subHead{
        font-weight: bold!important;
        font-size: 80px;
    }
    .subHead1{
        font-weight: bold!important;
        font-size: 80px;
        float: right;
        text-align: right;
        width: 150px !important;
    }
    .color1{
        color: #A9B3C4!important;
    }
</style>
</head>
<body>
<div class="gray-speacer"></div>
<div id="container">
    <table class="t-title">
        <tr>    
            <td style="text-align: left;">
                <h1 class="color1" style="height: 100px;margin-top: -150px;">Kostenübersicht Media-Planung</h1>
                <img style="width: 1200px;" class="zurcher-logo" src="{{ public_path() }}/icons/header.png" />
            </td>
            <td style="text-align: right"><img  class="zurcher-logo" style="width: 400px; " src="{{ public_path() }}{{ $logo }}" /></td>
        </tr>
    </table>

    <br><br>

    <table class="t-header">
        <tr class="first-table">
            <td><strong class="sub color1">Kampagne</strong></td>
            <td  ><strong class="sub color1">Laufzeit</strong></td>
            @if ($type == 'internal')
            <td style="text-align: right; float: right;width: 100px !important;"><strong class="sub color1">Datum</strong></td>
            @else
                <td style="text-align: right; float: right;width: 150px !important;"><strong class="sub color1">Version</strong></td>
                <td style="text-align: right; float: right;width: 150px !important;"><strong class="sub color1">Datum</strong></td>
            @endif
        </tr>
        <tr>
            <td class="subHead">{{ $header[0] }}</td>
            <td   class="subHead">{{ $header[1] }}</td>
            @if ($type == 'internal')
                <td class="subHead1">{{ $header[2] }}</td>
            @else
                <td class="subHead1">{{ $header[2] }}</td>
                <td class="subHead1">{{ $header[3] }}</td>
            @endif
        </tr>
    </table>
    <br><br>
    <table class="tg">
        <tr class="first-table rounded-circle">
            <td class="tg-0pky" style="border-top: 0"><strong >Mediagattung</strong></td>
            @if ($type == 'internal')
                <td class="tg-0pky w500" style="border-top: 0"><strong >Version</strong></td>
            @else
                <td class="tg-0pky w500" style="border-top: 0">&nbsp;</td>
            @endif
            <td class="tg-0pky w250 text-right" style="border-top: 0"><strong>Kosten in CHF</strong></td>
            <td class="tg-0pky w250 text-right" style="border-top: 0"><strong>in %</strong></td>
        </tr>
        @foreach ($first_table as $k => $row)
        <tr style="">
            <?php $style = ($k==0)?' style="border-top: 0"':''?>
            @foreach ($row as $n=>$cell)
                    @if ($type != 'internal' && $n==1)
                        <td class="tg-0pky <?php if($n>1) echo 'text-right';?>" <?php echo $style;?>></td>
                    @elseif($type != 'internal')
                        <td class="tg-0pky <?php if($n>1) echo 'text-right';?>" <?php echo $style;?>>{{ $cell }}</td>
                    @else
                        <td class="tg-0pky br_border <?php if($n>1) echo 'text-right';?>" <?php echo $style;?>>{{ $cell }}</td>
                    @endif

            @endforeach
        </tr>
        @endforeach
        <tr class="tr_highlight">
            <td class="tg-0pky "><strong>{{ $first_table_total[0] }}</strong></td>
            @if ($type == 'internal')
                <td class="tg-0pky"><strong>{{ $first_table_total[1] }}</strong></td>
            @else
                <td class="tg-0pky">&nbsp;</td>
            @endif
            <td class="tg-0pky text-right"><strong>{{ $first_table_total[2] }}</strong></td>
            <td class="tg-0pky text-right"><strong>{{ $first_table_total[3] }}</strong></td>
        </tr>
    </table>

    <br>

    <table class="tg ">
        <tr class="first-table rounded-circle" >
            <td class="tg-0pky w500"  style="border-top: 0"><strong>Agentur-Services</strong></td>
            <td class="tg-0pky w500"  style="border-top: 0"> </td>
            <td class="tg-0pky w250 text-right"  style="border-top: 0"><strong>Kosten in CHF</strong></td>
            <td class="tg-0pky w250 text-right"  style="border-top: 0"><strong>in %</strong></td>
        </tr>
        @foreach ($second_table as $k=> $row)
        <tr style="">
            <?php

            $style = ($k==0)?' style="border-top: 0"':''?>
            @foreach ($row as $n=>$cell)
                @if ($type == 'internal')
                    <td style="border-left: none !important; <?php echo ($k==0)?'border-top: none !important;':'';?> " class="tg-0pky br_border <?php if($n>1) echo 'text-right';?>" <?php echo $style;?>>{{ $cell }}</td>
                @else
                    <td style="border-left: none !important; border-right: none !important;<?php echo ($k==0)?'border-top: none !important;':'';?>" class="tg-0pky br_border <?php if($n>1) echo 'text-right';?>" <?php echo $style;?>>{{ $cell }}</td>
                @endif
            @endforeach
        </tr>
        @endforeach
        <tr class="tr_highlight">
            <td class="tg-0pky"><strong>{{ $second_table_total[0] }}</strong></td>
            <td class="tg-0pky"><strong>{{ $second_table_total[1] }}</strong></td>
            <td class="tg-0pky text-right"><strong>{{ $second_table_total[2] }}</strong></td>
            <td class="tg-0pky text-right"><strong>{{ $second_table_total[3] }}</strong></td>
        </tr>
    </table>
    <br>

    <table class="tg ">
        <tr class="first-table rounded-circle" >
            <td class="tg-0pky w500"  style="border-top: 0"><strong>Abzüge</strong></td>
            <td class="tg-0pky w500"  style="border-top: 0"> </td>
            <td class="tg-0pky w250 text-right"  style="border-top: 0"><strong>Kosten in CHF</strong></td>
            <td class="tg-0pky w250 text-right"  style="border-top: 0"><strong>in %</strong></td>
        </tr>
        @foreach ($deductsCost['deductServices'] as $k=> $cell)

            <tr style="">
                <td style="border-left: none !important;" class="tg-0pky br_border">{{ $k }}</td>
                <td style="border-left: none !important;" class="tg-0pky br_border"></td>
                <td style="border-left: none !important; text-align:right;" class="tg-0pky br_border">{{ number_format($cell,2,'.',"'") }}</td>
                <td style="border-left: none !important;" class="tg-0pky br_border"></td>
            </tr>
        @endforeach
        <tr class="tr_highlight">
            <td class="tg-0pky"><strong>Total Abzüge</strong></td>
            <td class="tg-0pky"><strong></strong></td>
            <td class="tg-0pky text-right"><strong>{{ number_format($deductsCost['subtotal'],2,'.',"'")}}</strong></td>
            <td class="tg-0pky text-right"><strong></strong></td>
        </tr>
    </table>
    <br>

    <table class="tg t-total font-s80 ">
        <tr class="tr_highlight">
            <td class="tg-0pky" style="border-top: 0;  background-color:#DAE6EB;color: #5C7C9D;"><strong>{{ $total_table[0][0] }}</strong></td>
            <td class="tg-0pky" style="border-top: 0;background-color:#DAE6EB;color: #5C7C9D;"><strong></strong></td>
            <td class="tg-0pky text-right" style="border-top: 0;background-color:#DAE6EB;color: #5C7C9D;"><strong>{{ $total_table[0][1] }}</strong></td>
            <td class="tg-0pky text-right" style="border-top: 0;background-color:#DAE6EB;color: #5C7C9D;"><strong>{{ $total_table[0][2] }}</strong></td>
        </tr>
        <tr class="first-table rounded-circle" style="border-top: 0;">
            <td class="tg-0pkt" style="border-top: 0;"><strong>{{ $total_table[1][0] }}</strong></td>
            <td class="tg-0pkt" style="border-top: 0;"><strong></strong></td>
            <td class="tg-0pkt text-right" style="border-top: 0;"><strong>{{ $total_table[1][1] }}</strong></td>
            <td class="tg-0pkt text-right" style="border-top: 0;"><strong>{{ $total_table[1][2] }}</strong></td>
        </tr>
    </table>

    <br><br>

    <table class="tg t-bemerkungen" style="width: 4480px; border:1px solid #DAE6EB!important;">
        <tr class="tr_highlight">
            <td class="tg-0pky" style="border-top: 0;"><strong>Bemerkungen</strong><td>
        </tr>
        <tr><td class="tg-0pky cell_border"  >
                {!! $bemerkungen !!}
                <br>
        </td></tr>
    </table>

    <br><br>
    <br><br>
    <div style="display: flex;  justify-content: space-between; align-items: center; position: relative;">
        <table class="t-title">
            <tr>
                <td> </td>
                <td style="text-align: right; position: relative; height: 400px;">
                    <br><br><br>
                    <img style="position: absolute; bottom: -50px; float: right;" class="house-logo" src="{{ public_path() }}/icons/amo-logo.png" />
                </td>
            </tr>
        </table>
    </div>
</div>
@php
//die();
@endphp
</body>
</html>
