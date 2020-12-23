<html>
<head>
<style type="text/css">
    body {
        background-color:#F7F9FC;
        font-family: sans-serif;
        font-size: 80px;
    }
    #container {
        background-color:#fff;
        padding: 100px 150px 5px 150px;
        margin: 0px 100px 0px 100px;
        border-radius: 50px;
        border-style: groove;
        border-color: #e2e7ed;
        border-width: 1px;
    }
    h1 {
        color:#5C7C9D;
        font-size:100px;
    }
    .gray-speacer {
        background-color:#F7F9FC;
        height:100px;
        width:80000px;
    }
    .t-title{width: 100%}
    .t-title td{
        width: 50%;
    }
    .t-header {
        color:#5C7C9D;
    }
    .t-header td {
        width: 1765px;
        font-size: 80px;
    }
    .t-header .first-table td {
        color:#a4a7ab;
    }
    .tg .first-table td {
        background-color:#e2e7ed;
    }
    .tg .tg-0pky {
        border-color:#e2e7ed;
        text-align:left;
        vertical-align:top
    }
    .zurcher-logo {
        width: 700px;
    }
    .house-logo {
        width: 400px;
    }
    .t-total td {
        background-color:#5C7C9D;
        color: #fff;
    }
    .t-total .first-table td {
        background-color:#e2e7ed;
        color:#5C7C9D;
    }
    .t-bemerkungen td {
        width:1855px;
    }
    .p-bemerkungen {
        padding:10px;
        width:3800px;
        color:#5C7C9D;
        
    }
    .tg {
        border-collapse:collapse;
        border-spacing:0;
    }
    .tg td {
        font-size:25px;
        padding:18px 20px;        
        overflow:hidden;
        word-break:normal;
        color:#5C7C9D;
    }
    .tg th {
        font-size:25px;
        font-weight:normal;
        padding:18px 20px;
        overflow:hidden;
        word-break:normal;
        color:#5C7C9D;
        vertical-align:middle!important;
    }
    .tg .tg-zv4m {
        border-color:#5C7C9D;       
        border-top: none ;
        border-left: none;
        text-align:left;
        vertical-align:top
    }
    .tg .tg-15li {
        background-color:#e2e7ed;       
        text-align:left;
        vertical-align:top;
        font-size:25px;
    }
    .tg .tg-16li {
        background-color:#a4b7ca;
        color:#fff;        
    }

    .tg .tg-17li {
        background-color:#F4F7FA;
    }
     
    .tg .th-spwh {
        background-color:#fff;
    }
    .tg .tg-lightgb {
        background-color:#f4f7fa
    }
    .tg .tg-1ans {
        background-color:#e2e7ed;
        text-align:center;
        vertical-align:top;
        font-size:35px;
    }
    .tg .tg-0lax {
        text-align:left;
        vertical-align:top
    }
    .tg .tg-space {
        text-align:left;
        vertical-align:center;
        border-top: none;
        border-right:none;
        border-bottom:none;
        border-left:none;
    }
    .tg .tg-space-fst {
        text-align:left;
        vertical-align:top;
        border-right: none;
        border-bottom: none;
        border-color: #5C7C9D;
    }
    .tg .tg-space-fnl {
        text-align:left;
        vertical-align:top;
        border-left: none;
        border-right: solid;
        border-bottom: none;
        border-color: #5C7C9D;
    }
    .lightbold{color: #A4B7CB;font-weight: bold;}
    .text-bold{
        font-weight: bold!important;
    }

    .bt_border{
        border-top:4px solid #5C7C9D!important;
    }
    .br_border{
        border-right:4px solid #5C7C9D!important;
    }
    .bb_border{
        border-bottom:4px solid #5C7C9D!important;
    }
    .bl_border{
        border-left:4px solid #5C7C9D!important;
    }
    .bt_border2{
        border-top:3px solid #BDCAD9!important;
    }
    .br_border2{
        border-right:3px solid #BDCAD9!important;
    }
    .bb_border2{
        border-bottom:3px solid #BDCAD9!important;
    }
    .bl_border2{
        border-left:3px solid #BDCAD9!important;
    }

    .border-top-left-right{
        border-top:4px solid #5C7C9D ;
        border-left:4px solid #5C7C9D ;
        border-right:4px solid #5C7C9D ;
    }
    .w120{width: 110px;}
    .w140{width: 150px;}
    .w150{width:180px;}
    .w220{width:220px;}
    .w250{width:250px;}
    .w80{width:110px;}
    .w30{width: 30px;}
    .w200{width: 200px;}
    .w300{width: 350px;}
    .w500{width: 500px;}
    .wDetail {width: 1000px}
    .nowrap{
        white-space: nowrap;
    }
    .tb_bottom td{
        padding: 0px;
    }
    .tb_bottom td span{
        padding: 10px 20px;
        display: inline-block;
    }
    .text-right{text-align: right!important;}
</style>



</head>
<body>
@php
    $_total_page = ceil(sizeof($date)/16);
@endphp
@for($_p =0; $_p < $_total_page; $_p++)
    @if($_p >0)<h2 style="page-break-before: always;" ></h2>@endif

<div class="gray-speacer"></div>
<div id="container">
    <table class="t-title full100">
        <tr>    
            <td><h1>MEDIA-PLANUNG: ONLINE</h1></td>
            <td style="text-align: right; padding-right: 200px;"><img class="zurcher-logo" style="height: 300px;width: auto;" src="{{ public_path() }}{{ $logo }}" /></td>
        </tr>
    </table>

    <br><br>

    <table class="t-header full100">
        <tr class="first-table">
            <td><span class="lightbold">Kampagne</span></td>
            <td><span class="lightbold">Laufzeit</span></td>
            <td> </td>
            @if ($type == 'internal')
                <td><span class="lightbold">Datum</span></td>
            @else
                <td><span class="lightbold">Version / Datum</span></td>
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

    @php

        ($_p < $_total_page-1) ? $size = 16 : $size = sizeof($date) - ($_total_page-1)*16;

        $totalAd = 0;
        $totalTkpGross = 0;
        $totalGross = 0;
        $totalDiscount = 0;
        $totalNet = 0;
        $totalBk = 0;
        $totalTkp = 0;
        $totalNN = 0;
        $totalWidthGrid = $size*120;
        $autoTableWidth = 1870 + $size*120;

    @endphp

    <table class="tg" width="{{$autoTableWidth}}" style="width: {{$autoTableWidth}}px">
        <tr class="text-bold">
            <th width="840" class="tg-zv4m" colspan="4"></th>
            <th width="960" class="tg-1ans bt_border bl_border br_border" colspan="8" >MEDIA- UND KOSTENPLANUNG</th>
            <th width="30" class="tg-zv4m" colspan="1"></th>
            <th width="{{$totalWidthGrid}}" class="tg-1ans border-top-left-right" colspan="{{$size}}">EINSATZZEITRAUM UND WERBEDRUCKVERTEILUNG</th>
        </tr>
        <tr class="text-bold">
            <th width="300" class="nowrap tg-15li bt_border bl_border br_border2 w300">PLATZIERUNG</th>
            <th width="{{$detailWidth}}" class="nowrap tg-15li bt_border br_border2 w{{$detailWidth}}">DETAILS</th>
            <th width="120" class="nowrap tg-15li bt_border br_border2 w120">SPRACHE</th>
            <th width="120" class="nowrap tg-15li bt_border br_border bl_border2 w220">FORMAT</th>
            <th width="120" class="nowrap tg-15li bl_border bt_border br_border2 w120">WERBE-<br>DRUCK</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">TKP<br>BRUT CHF<br></th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">KOSTEN<br>CHF</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">RABATT<br>IN %</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">KOSTEN<br>NETTO CHF</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">BK IN %</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 w120">TKP NET-<br>NET CHF</th>
            <th width="120" class="nowrap tg-15li bt_border bl_border2 br_border w120">KOSTEN<br>CHF</th>
            <th width="30" class="nowrap tg-drrh br_border w30" ></th>
            @for ($j = 0; $j < $size - 1; $j++)
                <th width="120" class="tg-15li bt_border br_border2">{!!$date[$j + ($_p*16)]??''!!}</th>
            @endfor
            <th  width="120" class="tg-15li bt_border br_border">{!!$date[$j + ($_p*16)]??''!!}</th>
        </tr>
        <tr>
            <td class="tg-space bl_border"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space br_border"></td>
            <td class="tg-space br_border" ></td>
            @for ($j = 0; $j < $size; $j++)
                <td class="tg-space @if($j == $size - 1) br_border @else br_border2 @endif"></td>
            @endfor

        </tr>

        @for ($i = 0; $i < sizeof($name); $i++)
        <tr>
            <td class="tg-15li bl_border bl_border br_border2"><strong>{{$name[$i]}}</strong></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border2"></td>
            <td class="tg-15li br_border"></td>
            <td class="tg-space br_border" ></td>
                @for ($j = 0; $j < $size; $j++)
                    <td class="tg-15li @if($j == $size - 1) br_border @else br_border2 @endif"></td>
            @endfor
        </tr>

        @php
            $x = 0;
            $subTotalAd = 0;
            $subTotalTkpGross = 0;
            $subTotalGross = 0;
            $subTotalDiscount = 0;
            $subTotalNet = 0;
            $subTotalBk = 0;
            $subTotalTkp = 0;
            $subTotalNN = 0;

        @endphp

        @foreach ($media[$i] as $row)
                @php
                //dd($row);
                @endphp
                <tr>
                <td class="tg-0lax bl_border br_border2 bt_border2">{!! $row[0] !!}@if($isViewMediaId){!! $row[12] !!}@endif</td>
                <td class="tg-0lax br_border2 bt_border2">{!! $row[1] !!}</td>
                <td class="tg-0lax br_border2 bt_border2">{!! $row[2] !!}</td>
                <td class="tg-0lax br_border bt_border2">{!! $row[3] !!}</td>

                <?php if($row[4] == 0 || $row[4] == '0' || $row[4] == 0.00 || $row[4] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[4], 0, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[5] == 0 || $row[5] == '0' || $row[5] == 0.00 || $row[5] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[5], 2, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[6] == 0 || $row[6] == '0' || $row[6] == 0.00 || $row[6] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[6], 2, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[7] == 0 || $row[7] == '0' || $row[7] == 0.00 || $row[7] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[7], 2, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[8] == 0 || $row[8] == '0' || $row[8] == 0.00 || $row[8] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[8], 0, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[9] == 0 || $row[9] == '0' || $row[9] == 0.00 || $row[9] == '0.00'){ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax br_border2 bt_border2 text-right">{!! number_format($row[9], 2, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[10] == 0 || $row[10] == '0' || $row[10] == 0.00 || $row[10] == '0.00'){ ?>
                    <td class="tg-0lax  tg-17li br_border2 bt_border2 text-right"></td>
                <?php }else{ ?>
                    <td class="tg-0lax  tg-17li br_border2 bt_border2 text-right">{!! number_format($row[10], 2, '.', '\'') !!}</td>
                <?php } ?>

                <?php if($row[11] == 0 || $row[11] == '0' || $row[11] == 0.00 || $row[11] == '0.00'){ ?>
                    <td class="tg-0lax tg-17li br_border bt_border2 text-right">
                <?php }else{ ?>
                    <td class="tg-0lax tg-17li br_border bt_border2 text-right">{!! number_format($row[11], 2, '.', '\'') !!}</td>
                <?php } ?>



                <td class="tg-drrh  br_border" ></td>
                    @for ($j = 0; $j < $size; $j++)
                        @if($j<$size - 1)
                            <td class="tg-0lax bt_border2 @if($j == $size - 1) br_border @else br_border2 @endif text-right">
                                @if ($row[4] != 0 && $row[4] != '')
                                    @if ($discount[$i][$x][$j+ ($_p*16)] != 0)
                                        {!! $discount[$i][$x][$j+ ($_p*16)] !!}
                                    @endif
                                @else
                                    folgt
                                @endif
                            </td>
                        @else
                            @if($hasExtra && ($_p == $_total_page -1))
                                @if($x==0)
                                     <td class="tg-0lax tg-drrh bt_border2 br_border" rowspan="{{ count($media[$i]) }}" style="text-align:center;vertical-align: middle; width: 220px">{{trans('language.extraWeekMSG')}}</td>
                                @endif
                            @else
                                <td class="tg-0lax bt_border2 @if($j == $size - 1) br_border @else br_border2 @endif text-right">
                                    @if ($row[4] != 0 && $row[4] != '')
                                        @if ($discount[$i][$x][$j+ ($_p*16)] != 0)
                                            {!! $discount[$i][$x][$j+ ($_p*16)] !!}
                                        @endif
                                    @else
                                        folgt
                                    @endif
                                </td>
                            @endif
                        @endif
                    @endfor
                @php $x++ @endphp
            </tr>

            @php
                $subTotalAd += $row[4];
                $subTotalTkpGross += $row[5];
                $subTotalGross += $row[6];
                $subTotalDiscount += $row[7];
                $subTotalNet += $row[8];
                $subTotalBk += $row[9];
                $subTotalTkp += $row[10];
                $subTotalNN += $row[11];

                $totalAd += $row[4];
                $totalTkpGross += $row[5];
                $totalGross += $row[6];
                $totalDiscount += $row[7];
                $totalNet += $row[8];
                $totalBk += $row[9];
                $totalTkp += $row[10];
                $totalNN += $row[11];
            @endphp
        @endforeach

           @php
           if($subTotalAd!=0){
               $subTotalTkpGross = $subTotalGross/$subTotalAd*1000;
               $subTotalTkp = $subTotalNN/$subTotalAd*1000;
           }
           @endphp
        <tr>
            <td class="tg-15li bl_border"><strong>Total {{$name[$i]}}</strong></td>
            <td class="tg-15li"></td>
            <td class="tg-15li"></td>
            <td class="tg-15li tg-drrh"></td>
            <td class="tg-15li text-right">{{number_format($subTotalAd, 0, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalTkpGross, 2, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalGross, 2, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalDiscount, 2, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalNet, 0, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalBk, 2, '.', '\'')}}</td>
            <td class="tg-15li text-right">{{number_format($subTotalTkp, 2, '.', '\'')}}</td>
            <td class="tg-15li br_border text-right">{{number_format($subTotalNN, 2, '.', '\'')}}</td>
            <td class="tg-drrh br_border" ></td>
                @for ($j = 0; $j < $size; $j++)
                    <td class="tg-15li @if($j == $size - 1) br_border @else br_border2 @endif text-right">
                @if (isset($disSum[$i][$j+ ($_p*16)]))
                {{number_format($disSum[$i][$j+ ($_p*16)], 0, '.', '\'')}}
                @endif
            </td>
            @endfor

        </tr>

        <tr>
            <td class="tg-space bl_border"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space"></td>
            <td class="tg-space br_border"></td>
            <td class="tg-space br_border"></td>
                @for ($j = 0; $j < $size; $j++)
                    <td class="tg-space @if($j == $size - 1) br_border @else br_border2 @endif"></td>
            @endfor

        </tr>
        @endfor

        @php
        if($totalAd){
            $totalTkpGross = $totalGross/$totalAd * 1000;
            $totalTkp = $totalNN/$totalAd * 1000;
        }
        @endphp
        <tr>
            <td class="tg-16li bl_border"><strong>Total Media</strong></td>
            <td class="tg-16li"></td>
            <td class="tg-16li"></td>
            <td class="tg-16li"></td>
            <td class="tg-16li text-right">{{number_format($totalAd, 0, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalTkpGross, 2, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalGross, 2, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalDiscount, 2, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalNet, 0, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalBk, 2, '.', '\'')}}</td>
            <td class="tg-16li text-right">{{number_format($totalTkp, 2, '.', '\'')}}</td>
            <td class="tg-16li tg-drrh text-right">{{number_format($totalNN, 2, '.', '\'')}}</td>
            <td class="tg-drrh bl_border br_border" style="border-top:none;border-bottom:none"></td>
            @for ($j = 0; $j < $size; $j++)
                <td class="tg-16li bb_border @if($j == $size - 1) br_border @endif text-right" style="padding:10px;">
                @if (isset($totalDisSum[$j+ ($_p*16)]))
                {{number_format($totalDisSum[$j+ ($_p*16)], 0, '.', '\'')}}
                @endif
            </td>
            @endfor

        </tr>
        <tr><td colspan="12" class="bl_border br_border">&nbsp;</td><td colspan="{{$size+1}}"></td></tr>
        <tr>
            <td colspan="4" class="bl_border bb_border"  style="padding: 0!important;">
              @php
              //dd($rightTable);
                  $sofLeft = sizeof($leftTable);
                  $sofRight = sizeof($rightTable);

              @endphp
                <table class="tg tb_bottom" style="margin-bottom: 1px; margin-left: 1px;">
                    @if($sofLeft < $sofRight)
                        @for ($i = 0; $i <= $sofRight-$sofLeft; $i++)
                            <tr><td colspan="5">&nbsp;</td></tr>
                        @endfor
                    @endif

                    @for ($i = 0; $i < sizeof($leftTable) - 1; $i++)
                        <tr class="text-bold">
                            @if ($leftTable[$i][0] == 'SPRACHE')
                                <td class="tg-15li w220 nowrap"><span>{{ $leftTable[$i][0] }}</span></td>
                                <td class="tg-15li w220 nowrap text-bold" ><span>{{ $leftTable[$i][1] }}&nbsp;</span></td>
                                <td class="tg-15li w220 nowrap text-bold" ><span>{{ $leftTable[$i][2] }}&nbsp;</span></td>
                                <td class="tg-15li w220 nowrap text-bold" ><span>{{ $leftTable[$i][3] }}&nbsp;</span></td>
                                <td class="tg-15li w220 nowrap text-bold" ><span>{{ $leftTable[$i][4] }}&nbsp;</span></td>
                            @elseif ($leftTable[$i][0] == '')
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][0] }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][1] }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][2] }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][3] }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][4] }}&nbsp;</span></td>
                            @else
                                <td class="tg-space bb_border2"><span>{{ $leftTable[$i][0] }}</span></td>
                                <td class="tg-space bb_border2"><span>{{ number_format($leftTable[$i][1], 0, '.', '\'') }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ number_format($leftTable[$i][2], 2, '.', '\'') }}&nbsp;</span></td>
                                <td class="tg-space bb_border2 "><span>{{ number_format($leftTable[$i][3], 0, '.', '\'') }}&nbsp;</span></td>
                                <td class="tg-space bb_border2"><span>{{ number_format($leftTable[$i][4], 2, '.', '\'') }}&nbsp;</span></td>
                            @endif
                        </tr>
                    @endfor
                        <tr class="text-bold">
                            <td class="tg-15li "><span>{{ $leftTable[$i][0] }}</span></td>
                            <td class="tg-15li"><span>{{ number_format($leftTable[$i][1], 0, '.', '\'') }}</span></td>
                            <td class="tg-15li"><span>{{ $leftTable[$i][2] }}</span></td>
                            <td class="tg-15li"><span>{{ number_format($leftTable[$i][3], 0, '.', '\'') }}</span></td>
                            <td class="tg-15li"><span>{{ number_format($leftTable[$i][4], 2, '.', '\'') }}</span></td>
                        </tr>
                </table>
            </td>
            <td colspan="8" class="bb_border br_border" style="padding: 0!important;">
                <table class="tg tb_bottom" align="right" style="margin-bottom: 1px; margin-right: 1px">
                    @if($sofLeft > $sofRight)
                        @for ($i = 0; $i < $sofLeft - $sofRight; $i++)
                            <tr><td colspan="3">&nbsp;</td></tr>
                        @endfor
                    @endif
                    @for ($i = 0; $i < sizeof($rightTable) - 1; $i++)
                    <tr class="text-bold">
                        <td class="tg-space bb_border2 w500 nowrap" colspan="2"><span>{{ $rightTable[$i][0] }}</span></td>
                        <td class="tg-space bb_border2 w150 text-right"><span>{{ $rightTable[$i][1] }}</span></td>
                        <td class="tg-space bb_border2 w150 text-right"><span>{{ $rightTable[$i][2] }} %</span></td>
                    </tr>

                    @endfor

                        <tr class="text-bold">
                            <td class="tg-15li  nowrap" colspan="2"><span>{{ $rightTable[$i][0] }}</span></td>
                            <td class="tg-15li  text-right"><span>{{ $rightTable[$i][1] }}</span></td>
                            <td class="tg-15li   text-right"><span>{{ $rightTable[$i][2] }} %</span></td>
                            <td class="tg-space "></td>
                        </tr>
                </table>
            </td>
            <td colspan="{{$size+1}}"></td>
        </tr>


    </table>
    <table class="t-title">
        <tr>    
            <td> </td>
            <td style="text-align: right;position: relative; height: 200px"><img class="house-logo" style="position: absolute; top: -300px; right: 200px;" src="{{ public_path() }}/house_logo.png" />
            </td>
        </tr>
    </table>
</div>
@endfor
@php
   //die();
@endphp
</body>
</html>

