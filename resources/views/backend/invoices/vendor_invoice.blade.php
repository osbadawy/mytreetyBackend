<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Vendor Invoice</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        @page {
            footer: page-footer;
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
        }

        /* change  font-weight & font-size:*/
        body {
            background-color: white;
            font-family: 'Urbanist', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0;
            margin: 0 auto;
            width: 794px;
            height: 1122px;
        }

        .main {
            width: 80%;
            margin: 5% auto 0;
        }

        table.table-table,
        .table-table th {
            border: 0.2px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        th {
            font-weight: 600;
        }

        tr.table-table,
        tr.table-table th,
        tr.table-table td {
            height: 35px;
            background: none;
        }


        .tfoot {
            height: 60px;
        }



        .hr {

            color: #BADCDA;
            height: 10px;
            width: 100%;
            border: none;

        }


        .mt-1 {
            margin-top: 10px;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .mt-3 {
            margin-top: 30px;
        }

        .mt-4 {
            margin-top: 40px;
        }

        .pb-8 {
            padding-bottom: 170px;
        }

        .mt-auto {
            margin-top: auto;
        }

        .ml-auto {
            margin-left: auto;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }


        .text-red {
            color: crimson;
        }



        .link-none {
            text-decoration: none;
            color: black;
        }

        .w-80 {
            width: 80%;
        }



        .right {
            text-align: right;
        }


        .logo {
            width: 180px;

        }

        .title {

            font-weight: bold;
        }

        tr.list-details-money td:not(tr.list-details-money:last-of-type td) {
            background: none;
        }

        tr.list-details-money:not(tr.list-details-money:last-of-type) {
            border-bottom: #BADCDA 2px solid !important;
        }

        .border-b {
            background-color: #BADCDA;
        }

        .text-center {
            text-align: center;
        }

        .list-footer {

            text-decoration: underline;
            margin-top: 15px;
        }

        .footer {
            position: fixed;
            width: 100%;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            /* font-size: 0.85rem; */
        }

        .text-small {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <hr class="hr" style="margin-top: 0px " />
    <div class="parent  mx-auto  pb-8" style="position:relative ;">
        <div class="main">
            <div class="header&title">
                <table class="part1 table-logo-title" style="width:100%">
                    <tr>
                        <td style="width:50%">
                            <h4 class="title order-1">
                                Rechnung
                            </h4>
                        </td>
                        <td style="width:50%;text-align: right;">
                            <img src="./logo.png" alt="logo" class="logo order-0">
                        </td>
                    </tr>
                </table>
                <div class="part2  mt-2">
                    <table style="width: 50%;">
                        <tr>
                            <td style="width: 50%;">
                                Bestellnummer
                            </td>
                            <td style="width: 50%;">
                                @if ($order)
                                    {{ $order->code }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bestelldatum
                            </td>
                            <td>
                                @if ($order)
                                    {{ date('d-m-Y', $order->date) }}
                                @endif

                            </td>
                        </tr>
                    </table>

                </div>
                <div class="part3 mt-4">
                    <table style="width: 100%; text-align: left;">
                        @php
                            $email = null;
                            $tax_number = null;
                            $iban = null;
                            $bank_acc_name = null;
                            $shipping_address = null;
                            $seller = null;
                            $name = null;
                            $phone = null;
                            $orderDetail = null;
                            if ($order) {
                                $shipping_address = json_decode($order->shipping_address);
                                $seller = \App\Models\Seller::where('user_id', $order->seller_id)->first();
                            }

                            if ($seller) {
                                $info = json_decode($seller->verification_info);

                                $name = $seller->name;
                                if ($info) {
                                    $email = $info[0]->value;
                                    $phone = $info[5]->value;
                                    $tax_number = $info[3]->value;
                                    $iban = $seller->bank_acc_no;
                                    $bank_acc_name = $seller->bank_acc_name;
                                }
                            }

                        @endphp
                        <tr>
                            <td style="width:50%;">
                                <h4 class="title">
                                    Mytreety UG

                                </h4>
                            </td>
                            <td style="width:50%;">
                                <h4 class="title">
                                    Verkäufer-Details

                                </h4>
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; ">
                        <tr>
                            <td style="width: 50%;">
                                <table style="width: 100%; text-align: left;">
                                    <tr>
                                        <td class="list  mt-1" style="width: 100%; ">
                                            Röpkestrasse 35, 40235, Düsseldorf,

                                        </td>


                                    </tr>
                                    <tr>
                                        <td class="list mt-1">Deutschland</td>


                                    </tr>
                                    <tr>
                                        <td class="list mt-1"><a href="www.mytreety.com" class="link-none">
                                                www.mytreety.com</a>
                                        </td>


                                    </tr>
                                    <tr>
                                        <td class="list mt-1">+49 1522 7826982
                                        </td>


                                    </tr>
                                    <tr>
                                        <td class="list mt-1">Ust.IDNr DE349232308</td>


                                    </tr>
                                    <tr>
                                        <td class="list mt-1">Registrierungsnummer HRB95195</td>

                                    </tr>
                                    <tr>

                                        <td class="list mt-1">(Amtsgericht Düsseleorf)
                                        </td>

                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; vertical-align: top;">
                                <table style="width:100%; text-align: left;">
                                    <tr>
                                        <td class="list mt-1" style="width: 100%; ">Ust.IDNr {{ $tax_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="list mt-1">{{ $name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="list mt-1">{{ $email }}
                                        </td>
                                    </tr>
                        </tr>
                        <tr>
                            <td class="list mt-1">{{ $phone }}
                            </td>
                        </tr>
                        <tr>
                            <td class="list mt-1">PayPal/IBAN {{ $iban }}</td>
                        </tr>



                    </table>
                    </td>
                    </tr>
                    <!-- the End details customer  -->


                    </table>
                </div>
            </div>
            <div class="table mt-4 ">
                <div class="table-responsive">
                    <table class="table-table" style="width:100%">
                        <tr class="table-table " style=" background:#BADCDA; ">
                            <th style="width:40%; background:#BADCDA; ">Proudktname (SKU) </th>
                            <th style="width:10%; background:#BADCDA; ">Anzahl</th>
                            <th style="width:35%; background:#BADCDA; ">Einzelpreis (inkl. MwSt) </th>
                            <th style="width:15%; background:#BADCDA; ">Insgesamt</th>
                        </tr>
                        @if ($order)
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr class="table-table">

                                    <th>
                                        @if ($orderDetail->product)
                                            {{ $orderDetail->product->name }}
                                            @if ($orderDetail->variation != null)
                                                ({{ $orderDetail->variation }})
                                            @endif
                                        @endif
                                    </th>
                                    <th>{{ $orderDetail->quantity }}</th>
                                    <th>{{ single_price($orderDetail->price / $orderDetail->quantity) }}</th>
                                    <th>{{ single_price($orderDetail->price) }}</th>
                                </tr>
                            @endforeach
                        @endif



                    </table>
                </div>
            </div>
            <div class="details-after-table mt-4">
                <table style="width:45%;" class=" ml-auto">

                    <tr class="list-details-money mt-1 ">
                        <td style="width:65%; " class="left  ">Zwischensumme
                        </td>
                        <td style="width:35%" class="right   ml-auto">
                            @if ($order)
                                {{ single_price($order->orderDetails->sum('price')) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 1px; background:#BADCDA ;" colspan="2"></td>
                    </tr>

                    <tr class="list-details-money mt-1">
                        <td class="left ">Versandkosten</td>
                        <td class="right   ml-auto">
                            @if ($order)
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 1px; background:#BADCDA ;" colspan="2"></td>
                    </tr>
                    @php
                        $mytreety_comm = 0;
                        if ($orderDetail) {
                            $mytreety_comm = 0.15 * $order->sub_total;
                        }

                    @endphp

                    <tr class="list-details-money mt-1">
                        <td class="left ">Mytreety-Provision
                            <span class="text-red">abzgl</span>
                        </td>
                        <td class="right   ml-auto">{{ single_price($mytreety_comm) }} </td>
                    </tr>
                    <tr>
                        <td style="height: 1px; background:#BADCDA ;" colspan="2"></td>
                    </tr>

                    <tr class="list-details-money mt-1">
                        <td class="left ">Fälliger Betrag</td>
                        <td class="right   ml-auto">
                            @if ($order)
                                {{ single_price($order->sub_total - $mytreety_comm) }}
                            @endif
                        </td>
                    </tr>

                    <tr class="w-full tfoot mt-4">
                        <td colspan="2" class="text-center ">

                            Mytreety wird den fälligen Betrag <br>
                            innerhalb von 5 Werktagen nach Ablauf <br>
                            der Rückerstattungsfrist senden
                        </td>
                    </tr>
                </table>
            </div>



        </div>
    </div>

    <htmlpagefooter name="page-footer">
        <div class="footer">
            <div class="w-80 mx-auto">
                <p class="">
                    Diese Rechnung ist nur vorläufig und kann Fehler enthalten; in diesem Fall wenden Sie sich bitte an
                    das
                    Mytreety-Team unter <a href="http://vendor@mytreety.com">vendor@mytreety.com</a>
                </p>
                <p class=" text-small mt-2">Bank: Solarisbank AG (Cuvrystraße 53, 10997 Berlin, Germany), IBAN:
                    DE26110101015303247754, BIC: SOBKDEB2XXX</p>
            </div>
            <table class="part2 mt-3 text-small mx-auto" style="width: 80%;">
                <tr>
                    <th style="width: 25%;" class="list-footer">Verkäufer-Richtlinien</th>
                    <th style="width: 30%;" class="list-footer">Käufer-Richtlinien (widerrufsrecht)</th>
                    <th style="width: 30%;" class="list-footer">Allgemeine Geschäftsbedingungen (AGB) </th>
                    <th style="width: 25%;" class="list-footer">Datenschutzerklärung</th>
                </tr>
            </table>
        </div>

    </htmlpagefooter>
</body>

</html>
