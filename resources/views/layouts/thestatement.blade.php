<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

@php
    $tatal = 0;
    $firstId = null;
    $lastId = null;
@endphp

@foreach ($invoice as $key => $record)
    @if ($loop->first)
        @php $firstId = $record->id; @endphp
    @endif
    @if ($loop->last)
        @php $lastId = $record->id; @endphp
    @endif
@endforeach


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        ALM Statement
    </title>
    <meta name="author" content="xxxcaliper" />
    <meta name="keywords" content="DAGlQ12i2k4,BAE3pwnYqAY,0" />
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        .p,
        p {
            color: black;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
            margin: 0pt;
        }

        .h3,
        h3 {
            color: black;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        .a,
        a {
            color: black;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .s1 {
            color: black;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 18pt;
        }

        h1 {
            color: black;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 52.5pt;
        }

        .s2 {
            color: #a5a5aa;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 18pt;
        }

        .s3 {
            color: #fff;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .s4 {
            color: black;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .s5 {
            color: #fff;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .s6 {
            color: black;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s7 {
            color: #fff;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .s8 {
            color: black;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .s9 {
            color: black;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        .s10 {
            color: black;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 7pt;
        }

        .h2,
        h2 {
            color: black;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .s11 {
            color: black;
            font-family: "Century Gothic", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
            vertical-align: 1pt;
        }

        .hrr {
            /* border-top-style: solid;
      border-top-width: 1pt; */
            width: 90pt;
            padding-top: 0.1pt;
            padding-bottom: 0.1pt;
        }

        .hrh {
            border-top-style: solid;
            border-top-width: 1pt;
            /* padding-top: 5pt;
      padding-bottom: 5pt; */
        }

        .theonetable {
            font-size: 13px;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }
    </style>
</head>

<body>
    <p style="padding-top: 4pt; text-indent: 0pt; text-align: left">
        <br />
    </p>

    <table>
        <tr>
            <td>
                <div class="lefthand" style="width: 450px">
                    <p
                        style="
                                padding-left: 35pt;
                                text-indent: 0pt;
                                text-align: left;
                            ">
                        P.O BOX 75391
                    </p>
                    <p
                        style="
                                padding-top: 1pt;
                                padding-left: 35pt;
                                text-indent: 0pt;
                                line-height: 112%;
                                text-align: left;
                            ">
                        Dar es Salaam, Avocado Street, Kawe <br />
                        <span class="h3">TIN: </span>141-853-023
                    </p>
                    <h3
                        style="
                                padding-left: 35pt;
                                text-indent: 0pt;
                                line-height: 12pt;
                                text-align: left;
                            ">
                        CELL: <span class="p">+255 753 123 283</span>
                    </h3>
                    <p
                        style="
                                padding-top: 1pt;
                                padding-left: 35pt;
                                text-indent: 0pt;
                                text-align: left;
                            ">
                        <a href="mailto:giraldinen@presisfinace.co.tz">giraldinen@presisfinace.co.tz</a>
                    </p>
                </div>
            </td>
            <td>
                <div class="righthand" style="float: right">
                    <h1
                        style="
                                padding-top: 5pt;
                                padding-left: 21pt;
                                text-indent: 0pt;
                                line-height: 59pt;
                                text-align: left;
                            ">
                        PRESIS
                    </h1>
                    <p class="s2"
                        style="
                                padding-left: 24pt;
                                text-indent: 0pt;
                                line-height: 19pt;
                                text-align: left;
                            ">
                        CONSULTANCY LTD
                    </p>
                </div>
            </td>
        </tr>
    </table>
    <p style="text-indent: 0pt; text-align: left"><br /></p>

    <p class="s1" style="margin-top: 40px; text-align: center">
        {{ strtoupper(date('F')) }} STATEMENT
    </p>

    <p style="padding-top: 3pt; text-indent: 0pt; text-align: left">
        <br />
    </p>
    <table style="border-collapse: collapse" cellspacing="0">
        <tr style="height: 25pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s3"
                    style="
                            padding-top: 9pt;
                            padding-left: 62pt;
                            text-indent: 0pt;
                            line-height: 14pt;
                            text-align: left;
                        ">
                    BILL TO:
                </p>
            </td>
            <td style="
                        width: 274pt;
                        border-top-style: solid;
                        border-top-width: 1pt;
                    "
                bgcolor="#FFF">
                <p class="s4"
                    style="
                            padding-top: 9pt;
                            padding-left: 17pt;
                            text-indent: 0pt;
                            line-height: 14pt;
                            text-align: left;
                        ">
                    STATEMENT NUMBER
                </p>
            </td>
        </tr>
        <tr style="height: 17pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s5"
                    style="
                            padding-top: 3pt;
                            padding-left: 62pt;
                            text-indent: 0pt;
                            line-height: 12pt;
                            text-align: left;
                        ">
                    ALISTAIR JAMES COMPANY Ltd.
                </p>
            </td>
            <td style="width: 274pt" bgcolor="#FFF">
                <p class="s6"
                    style="
                            padding-left: 17pt;
                            text-indent: 0pt;
                            text-align: left;
                        ">
                    STMNT-{{ $firstId }}-U-{{ $lastId }}
                </p>
            </td>
        </tr>
        <tr style="height: 41pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s5"
                    style="
                            padding-left: 62pt;
                            text-indent: 0pt;
                            text-align: left;
                        ">
                    P.O. Box 4543
                </p>
                <p class="s5"
                    style="
                            padding-left: 62pt;
                            padding-right: 167pt;
                            text-indent: 0pt;
                            line-height: 14pt;
                            text-align: left;
                        ">
                    Kurasini Temeke Dar es salaam
                </p>
            </td>
            <td style="width: 274pt" bgcolor="#FFF">
                <p class="s4"
                    style="
                            padding-top: 8pt;
                            padding-left: 18pt;
                            text-indent: 0pt;
                            text-align: left;
                        ">
                    DATE
                </p>
                <p class="s6"
                    style="
                            padding-left: 18pt;
                            text-indent: 0pt;
                            text-align: left;
                        ">
                    {{ date('d F Y') }}
                </p>
            </td>
        </tr>
        <tr style="height: 24pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p
                    style="
                            padding-left: 62pt;
                            text-indent: 0pt;
                            text-align: left;
                        ">
                    <a href="mailto:payables@alistairgroup.com" class="s7">payables@alistairgroup.com</a>
                </p>
            </td>
            <td style="
                        width: 274pt;
                        border-bottom-style: solid;
                        border-bottom-width: 1pt;
                    "
                bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
        </tr>
    </table>
    <p style="padding-top: 3pt; text-indent: 0pt; text-align: left">
        <br />
    </p>
    <table style="border-collapse: collapse; margin-left: 58.7072pt" cellspacing="0">
        <thead style="height: 28pt">
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                Date
            </td>
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                Invoice No
            </td>
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                Feri/AD No
            </td>
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                Cus Trip No
            </td>
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                PO
            </td>
            <td style="border-bottom-style: solid; border-bottom-width: 1px">
                Amount
            </td>
        </thead>
        @foreach ($invoice as $record)
            <tr class="theonetable" style="height: 25pt">
                <td class="hrr">{{ \Carbon\Carbon::parse($record->invoice_date)->format('j/n/Y') }}</td>
                <td class="hrr">PRES-2025-P{{ $record->id }}</td>
                <td class="hrr">{{ $record->certificate_no }}</td>
                <td class="hrr">{{ $record->customer_ref }}</td>
                <td class="hrr">{{ $record->po }}</td>

                @php
                    $amount = (float) str_replace(',', '', $record->amount);
                    $rate = (float) str_replace(',', '', $record->tz_rate);
                    $tzamountRaw = $amount * $rate;
                    $tzamount = number_format($tzamountRaw, 2, '.', ',');
                @endphp
                <td class="hrr">{{ $tzamount }} /=</td>
                @php
                    $tatal = ($tatal ?? 0) + $tzamountRaw;
                @endphp
            </tr>
        @endforeach
        <tr class="theonetable">
            <td style="margin-top: 20pt" class="hrh hrr"></td>
            <td style="margin-top: 20pt" class="hrh hrr"></td>
            <td style="margin-top: 20pt" class="hrh hrr"></td>
            <td style="margin-top: 20pt" class="hrh hrr"></td>
            <td style="margin-top: 20pt" class="hrh hrr"></td>
            <td style="margin-top: 20pt" class="hrh hrr">
                {{-- lost code --}}
            </td>
        </tr>
        <tr class="theonetable">
            <td style="margin-top: 20pt" class=""></td>
            <td style="margin-top: 20pt" class=""></td>
            <td style="margin-top: 20pt" class=""></td>
            <td style="margin-top: 20pt" class=""></td>
            <td style="margin-top: 20pt" class="">Total (TZS)</td>
            <td style="margin-top: 20pt" class="">
                <b>{{ number_format($tatal, 2) }}</b> TZS
            </td>
        </tr>
    </table>
    <p style="padding-top: 7pt; text-indent: 0pt; text-align: left">
        <br />
    </p>
    <!-- <p style="text-indent: 0pt; text-align: left"><br /></p> -->

    <!-- <p style="padding-top: 3pt; text-indent: 0pt; text-align: left">
    <br />
  </p> -->
    <h2
        style="
                padding-top: 8pt;
                padding-left: 60pt;
                text-indent: 0pt;
                text-align: left;
            ">
        BANKING DETAILS
    </h2>
    <h3
        style="
                padding-top: 3pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Account Name: <span class="p">PRESIS CONSULTANCY LIMITED</span>
    </h3>
    <h3
        style="
                padding-top: 1pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Banker: <span class="p">CRDB BANK PLC</span>
    </h3>
    <h3
        style="
                padding-top: 1pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Bank Branch: <span class="p">MIKOCHENI</span>
    </h3>
    <h3
        style="
                padding-top: 1pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Account Number: <span class="p">0150828197600 - TZS</span> | <span class="p">0250828197600 -
            USD</span>
    </h3>
    <h3
        style="
                padding-top: 1pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Branch Code: <span class="p">003074 - TZS</span> | <span class="p">003374 - USD</span>
    </h3>
    <h3
        style="
                padding-top: 1pt;
                padding-left: 59pt;
                text-indent: 0pt;
                text-align: left;
            ">
        Swift Code: <span class="p">CORUTZTZ</span>
    </h3>

    <br>
    <br>

    <div style="background-color: #3f4a7e; height: 55px; color: #3f4a7e">
        s
    </div>
</body>

</html>
