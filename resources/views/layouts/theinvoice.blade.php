<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

@php
$feriQty = (float)($invoice->feri_quantity ?? 0);
$feriUnits = (float)($invoice->feri_units ?? 0);
$codQty = (float)($invoice->cod_quantities ?? 0);
$codUnits = (float)($invoice->cod_units ?? 0);
$euroRate = (float)($invoice->euro_rate ?? 1);
$transporterQty = (float)($invoice->transporter_quantity ?? 0);

// Calculating the amounts
$feriAmount = $feriQty * $feriUnits;
$codAmount = $codQty * $codUnits;
$upTotal = $feriAmount + $codAmount;
$transporterAmount = $transporterQty * 0.018;
$grandTotal = ($transporterAmount + ($upTotal * $euroRate)) - 5;

$formattedDate = \Carbon\Carbon::parse($invoice->invoice_date)->format('d - F - Y');
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        PRES-2025-{{ $invoice->id }}
    </title>
    <meta name="author" content="Jordan Chaki" />
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

    table,
    tbody {
        vertical-align: top;
        overflow: visible;
    }
    </style>
</head>

<body>
    <p style="padding-top: 4pt; text-indent: 0pt; text-align: left"><br /></p>

    <table>
        <tr>
            <td>
                <div class="lefthand" style="width: 450px;">
                    <p style="padding-left: 35pt; text-indent: 0pt; text-align: left">
                        P.O BOX 75391
                    </p>
                    <p style="
        padding-top: 1pt;
        padding-left: 35pt;
        text-indent: 0pt;
        line-height: 112%;
        text-align: left;
      ">
                        Dar es Salaam, Avocado Street, Kawe <br>
                        <span class="h3">TIN: </span>141-853-023
                    </p>
                    <h3 style="
        padding-left: 35pt;
        text-indent: 0pt;
        line-height: 12pt;
        text-align: left;
      ">
                        CELL: <span class="p">+255 753 123 283</span>
                    </h3>
                    <p style="
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
                <div class="righthand" style="float: right;">

                    <h1 style="
        padding-top: 5pt;
        padding-left: 21pt;
        text-indent: 0pt;
        line-height: 59pt;
        text-align: left;
      ">
                        PRESIS
                    </h1>
                    <p class="s2" style="
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

    <p class="s1" style="margin-top: 40px; text-align: center;">
        {{ $feriapp->status != 5 ? 'DRAFT' : '' }} FERI/AD INVOICE
    </p>


    <p style="padding-top: 7pt; text-indent: 0pt; text-align: left"><br /></p>
    <table style="border-collapse: collapse" cellspacing="0">
        <tr style="height: 25pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s3" style="
              padding-top: 9pt;
              padding-left: 62pt;
              text-indent: 0pt;
              line-height: 14pt;
              text-align: left;
            ">
                    BILL TO:
                </p>
            </td>
            <td style="width: 274pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s4" style="
              padding-top: 9pt;
              padding-left: 17pt;
              text-indent: 0pt;
              line-height: 14pt;
              text-align: left;
            ">
                    INVOICE NUMBER
                </p>
            </td>
        </tr>
        <tr style="height: 17pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s5" style="
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
                <p class="s6" style="padding-left: 17pt; text-indent: 0pt; text-align: left">
                    PRES-2025-{{ $invoice->id }}
                </p>
            </td>
        </tr>
        <tr style="height: 41pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p class="s5" style="padding-left: 62pt; text-indent: 0pt; text-align: left">
                    P.O. Box 4543
                </p>
                <p class="s5" style="
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
                <p class="s4" style="
              padding-top: 8pt;
              padding-left: 18pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    DATE
                </p>
                <p class="s6" style="padding-left: 18pt; text-indent: 0pt; text-align: left">
                    {{ $formattedDate }}
                </p>
            </td>
        </tr>
        <tr style="height: 24pt">
            <td style="width: 322pt" bgcolor="#3F4A7E">
                <p style="padding-left: 62pt; text-indent: 0pt; text-align: left">
                    <a href="mailto:payables@alistairgroup.com" class="s7">payables@alistairgroup.com</a>
                </p>
            </td>
            <td style="
            width: 274pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
        </tr>
    </table>
    <p style="padding-top: 8pt; text-indent: 0pt; text-align: left"><br /></p>
    <table style="border-collapse: collapse; margin-left: 58.7072pt" cellspacing="0">
        <tr style="height: 28pt">
            <td style="
            width: 141pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p class="s4" style="text-indent: 0pt; line-height: 15pt; text-align: left">
                    ITEMS
                </p>
            </td>
            <td style="
            width: 99pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p class="s4" style="
              padding-left: 5pt;
              text-indent: 0pt;
              line-height: 15pt;
              text-align: left;
            ">
                    CURRENCY
                </p>
            </td>
            <td style="
            width: 81pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p class="s4" style="
              padding-left: 31pt;
              text-indent: 0pt;
              line-height: 15pt;
              text-align: left;
            ">
                    QTY
                </p>
            </td>
            <td style="
            width: 101pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p class="s4" style="
              padding-left: 25pt;
              text-indent: 0pt;
              line-height: 15pt;
              text-align: left;
            ">
                    UNIT COST
                </p>
            </td>
            <td style="
            width: 71pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
          " bgcolor="#FFF">
                <p class="s4" style="
              padding-left: 12pt;
              text-indent: 0pt;
              line-height: 15pt;
              text-align: left;
            ">
                    AMOUNT
                </p>
            </td>
        </tr>
        <tr style="height: 25pt">
            <td style="width: 141pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s8" style="padding-top: 7pt; text-indent: 0pt; text-align: left">
                    Feri Cost Per ton/cbm
                </p>
            </td>
            <td style="width: 99pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 7pt;
              padding-left: 5pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    EUR - €
                </p>
            </td>
            <td style="width: 81pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 7pt;
              padding-left: 31pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    {{ $feriQty }}
                </p>
            </td>
            <td style="width: 101pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 7pt;
              padding-left: 25pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    {{ $feriUnits }}
                </p>
            </td>
            <td style="width: 71pt; border-top-style: solid; border-top-width: 1pt" bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 7pt;
              padding-left: 12pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    € {{ $feriAmount }}
                </p>
            </td>
        </tr>
        <tr style="height: 31pt">
            <td style="
            width: 141pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="padding-top: 4pt; text-indent: 0pt; text-align: left">
                    Feri/COD Certificate Admin
                </p>
            </td>
            <td style="
            width: 99pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 4pt;
              padding-left: 5pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    EUR - €
                </p>
            </td>
            <td style="
            width: 81pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 4pt;
              padding-left: 31pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    {{ $codQty }}
                </p>
            </td>
            <td style="
            width: 101pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 4pt;
              padding-left: 25pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    {{ $codUnits }}
                </p>
            </td>
            <td style="
            width: 71pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 4pt;
              padding-left: 12pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    € {{ $codAmount }}
                </p>
            </td>
        </tr>
        <tr style="height: 31pt">
            <td style="
            width: 141pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 99pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 81pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 101pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s9" style="
              padding-top: 8pt;
              padding-left: 25pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    Total
                </p>
            </td>
            <td style="
            width: 71pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 8pt;
              padding-left: 12pt;
              text-indent: 0pt;
              text-align: left;
            ">
                    € {{ $upTotal }}
                </p>
            </td>
        </tr>
        <tr style="height: 54pt">
            <td style="
            width: 141pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="padding-top: 8pt; text-indent: 0pt; text-align: left">
                    <br />
                </p>
                <p class="s8" style="text-indent: 0pt; text-align: left">
                    Transporter DRC Freight TAX
                </p>
                <p class="s10" style="padding-top: 2pt; text-indent: 0pt; text-align: left">
                    1.80% of Freight cost
                </p>
            </td>
            <td style="
            width: 99pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="padding-top: 8pt; text-indent: 0pt; text-align: left">
                    <br />
                </p>
                <p class="s8" style="padding-left: 6pt; text-indent: 0pt; text-align: left">
                    USD - $
                </p>
            </td>
            <td style="
            width: 81pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="padding-top: 8pt; text-indent: 0pt; text-align: left">
                    <br />
                </p>
                <p class="s8" style="padding-left: 32pt; text-indent: 0pt; text-align: left">
                    {{ $transporterQty }}
                </p>
            </td>
            <td style="
            width: 101pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="padding-top: 8pt; text-indent: 0pt; text-align: left">
                    <br />
                </p>
                <p class="s8" style="padding-left: 26pt; text-indent: 0pt; text-align: left">
                    1.80%
                </p>
            </td>
            <td style="
            width: 71pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-bottom-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="padding-top: 8pt; text-indent: 0pt; text-align: left">
                    <br />
                </p>
                <p class="s8" style="padding-left: 13pt; text-indent: 0pt; text-align: left">
                    ${{ number_format($transporterAmount, 2, '.', ',') }}
                </p>
            </td>
        </tr>
        <tr style="height: 21pt">
            <td style="
            width: 141pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 99pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 81pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
          " bgcolor="#FFF">
                <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="
            width: 101pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s9" style="
              padding-top: 8pt;
              padding-left: 24pt;
              text-indent: 0pt;
              line-height: 12pt;
              text-align: left;
            ">
                    Total
                </p>
            </td>
            <td style="
            width: 71pt;
            border-top-style: solid;
            border-top-width: 1pt;
            border-top-color: #a6a6a6;
          " bgcolor="#FFF">
                <p class="s8" style="
              padding-top: 8pt;
              padding-left: 12pt;
              text-indent: 0pt;
              line-height: 12pt;
              text-align: left;
            ">
                    ${{ number_format($transporterAmount, 2, '.', ',') }}
                </p>
            </td>
        </tr>
    </table>
    <p style="padding-top: 7pt; text-indent: 0pt; text-align: left"><br /></p>
    <!-- <p style="text-indent: 0pt; text-align: left"><br /></p> -->


    <div class="total" style="padding-right: 120px">

        <p class="s" style="
        padding-top: 2pt;
        text-indent: 0pt;
        text-align: right;
      ">
            DISCOUNT APPLIED <span class="">: $5</span>
        </p>
        <p class="s" style="
        padding-top: 2pt;
        text-indent: 0pt;
        text-align: right;
      ">
            GRAND TOTAL (USD) <span class="">: ${{ number_format($grandTotal, 2, '.', ',') }}</span>
        </p>
        <p class="s9" style="
        padding-top: 1pt;
        text-indent: 0pt;
        text-align: right;
      ">
            GRAND TOTAL (TZ) <span class="h2">:
                {{ number_format(($grandTotal * $invoice->tz_rate), 2, '.', ',') }}</span>
        </p>
        <p class="s11" style="
        padding-top: 2pt;
        padding-left: 16pt;
        text-indent: 0pt;
        text-align: left;
      ">

        </p>
    </div>
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 60pt; text-indent: 0pt; text-align: left">
                    APPLICATION REF
                </h2>
                <h3 style="
        padding-top: 4pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Applicant: <span class="p">{{ $applicantName }}</span>
                </h3>
                <h3 style="
        padding-top: 1pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Customer Ref No: <span class="p">{{ $invoice->customer_ref}}</span>
                </h3>
                <h3 style="
        padding-top: 1pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Customer PO: <span class="p">{{ $feriapp->po}}</span>
                </h3>
                <h3 style="
        padding-top: 1pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Customer Trip No: <span class="p">{{ $invoice->customer_trip_no}}</span>
                </h3>
                <h3 style="
        padding-top: 1pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Application Invoice No: <span class="p">PRES-2025-{{ $invoice->id }}</span>
                </h3>
                <h3 style="
        padding-top: 1pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
                    Feri/COD Certificate NO: <span class="p">{{ $invoice->certificate_no}}</span>
                </h3>
            </td>
            <td>
            </td>
        </tr>
    </table>


    <p style="padding-top: 3pt; text-indent: 0pt; text-align: left"><br /></p>
    <h3 style="text-indent: 0pt; text-align: right; padding-right: 80px;">NOTE</h3>
    <p style="
        padding-top: 4pt;
        padding-left: 184pt;
        text-indent: 0pt;
        text-align: left;
      ">
        <a href="http://www.ex.com/" class="a" target="_blank">Exchange rates are based on the daily ex rates. For
            reference visit </a><a href="http://www.ex.com/" target="_blank">www.ex.com</a>
    </p>
    <h2 style="
        padding-top: 8pt;
        padding-left: 60pt;
        text-indent: 0pt;
        text-align: left;
      ">
        BANKING DETAILS
    </h2>
    <h3 style="
        padding-top: 3pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Account Name: <span class="p">PRESIS CONSULTANCY LIMITED</span>
    </h3>
    <h3 style="
        padding-top: 1pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Banker: <span class="p">CRDB BANK PLC</span>
    </h3>
    <h3 style="
        padding-top: 1pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Bank Branch: <span class="p">MIKOCHENI</span>
    </h3>
    <h3 style="
        padding-top: 1pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Account Number: <span class="p">0250828197600 - USD</span>
    </h3>
    <h3 style="
        padding-top: 1pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Branch Code: <span class="p">003374</span>
    </h3>
    <h3 style="
        padding-top: 1pt;
        padding-left: 59pt;
        text-indent: 0pt;
        text-align: left;
      ">
        Swift Code: <span class="p">CORUTZTZ</span>
    </h3>
    <!-- <p style="text-indent: 0pt; text-align: left" /> -->
    <br><br><br><br><br>
    <div style="background-color: #3F4A7E; height: 55px; color: #3F4A7E;">s</div>
</body>

</html>