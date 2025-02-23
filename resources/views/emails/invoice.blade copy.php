<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order confirmation - Mytreety</title>
    <style type="text/css">
        body {
            font-family: Montserrat;
            margin: 0;
            padding: 0;
            background-color: #e7e7e7;
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        td {
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        /*table,
      td,
      th {
        border: 1px solid red;
      }*/
        img {
            border: 0;
        }

        li {
            display: inline-block;
            margin: 0 10px;
        }

        li img {
            width: 39.33px;
        }

        .wrapper {
            table-layout: fixed;
            background-color: #e7e7e7;
        }

        .webkit {
            max-width: 600px;
            margin: auto;
            background-color: #e7e7e7;
        }

        .cost-summary {
            padding-left: 48px;
        }

        .cost-summary td {
            font-size: 22px;
            text-align: left;
        }

        .three-underlines>td {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {}

        @media screen and (max-width: 400px) {}

    </style>
</head>

<body>
    <center class="wrapper">
        <div class="webkit">
            <table class="outer" align="center">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="align-content: center;">
                                    <p style="font-size: 30px; margin: 0">
                                        Danke, dass Sie unsere Erde schützen
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: 14px; color: #707070">
                                        Bitte bewahren Sie diese Auftragsbestätigung
                                        für Ihre Unterlagen auf. *Alle Preise
                                        enthalten die Mehrwertsteuer. Sobald Sie die
                                        Bestellung erhalten haben, können Sie
                                        innerhalb von 14 Tagen eine Rückerstattung
                                        beantragen. Wenn Sie Fragen zu Ihrer
                                        Bestellung haben, senden Sie bitte eine
                                        E-Mail an hello@mytreety.com oder besuchen
                                        Sie unsere Kontaktseite.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <td>
                    <center style="
                        background-color: #ffffff;
                        border-radius: 15px;
                        width: 100%;
                      ">
                        <table width="100%">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left">
                                                <a href="https://www.mytreety.com" target="_blank"
                                                    style="margin: auto 24px;"><img
                                                        src="{{url('/')}}/public/frontend/emails/Mytreety-main-header-logo-150x66.png" alt="Mytreety"
                                                        style="max-width: 80px;" /></a>
                                                <p style="display: inline-block; font-size: 16px; font-weight: bold">
                                                    Bestellungsbestätigung
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;">
                                                <p style="font-weight: bold; font-size:20px; margin: auto 16px">
                                                    Bestellnummer: <br> {{ $order->code }} </p>
                                                    <p style="font-weight: bold; font-size:20px; margin: auto 16px">
                                                    Bestelldatum: <br> {{ date('d-m-Y', $order->date) }}</p>
                                                <table>
                                                    <tr>
                                                        @php
                                                        $charity= \App\Models\Charity::find($order->charity);
                                                        $enviro= \App\Models\Charity::find($order->climate);


                                                        @endphp
                                                        <td>
                                                            <p>Ausgewählte Wohltätigkeitsorganisation:</p>
                                                        </td>
                                                        <td><img src="{{ uploaded_asset($charity->user->avatar_original) }}"
                                                                style="max-width: 45px;" alt=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Umwelt Wohltätigkeitsorganisation:</td>
                                                        <td><img src="{{ uploaded_asset($enviro->user->avatar_original) }}"
                                                                style="max-width: 50px;" alt=""></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <p
                                                                style="margin: auto 16px auto 80px; text-align: left; font-size: 20px; font-weight: bold">
                                                                Versand nach:
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @php
                                                            $shipping_address = json_decode($order->shipping_address);
                                                        @endphp
                                                        <td>
                                                            <p
                                                                style="margin-left: 80px; margin-right: 16px; text-align: left;">
                                                                {{ $shipping_address->address }}, {{ $shipping_address->city }},
                                                                {{ $shipping_address->country }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="margin: 45px auto;">
                                        <tr>
                                            <th>Artikel</th>
                                            <th>Rang</th>
                                            <th>Preis</th>
                                            <th>Anzahl</th>
                                        </tr>

                                        @foreach ($order->orderDetails as $key => $orderDetail)
                                        <tr>
                                            <td>{{ $orderDetail->product->getTranslation('name') }} @if ($orderDetail->variation != null)
                                                ({{ $orderDetail->variation }})
                                            @endif</td>
                                            <td><img src="https://mytreety.com/wp-content/uploads/2021/10/rank-{{$orderDetail->product->sustainability_rank}}.png" style="max-width: 24px;" alt="rank"></td>
                                            <td>{{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                            <td>{{$orderDetail->quantity}}</td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="cost-summary">
                                        <tr>
                                            <td>Versandpreis</td>
                                            <td>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                                            <td>
                                            <td>
                                                <p style="font-size: 15px;"> <span
                                                        style="color: #7bb542;">In 1 Woche</span></p>
                                            </td>
                                </td>
                            </tr>
                            <tr style="font-size: 28px; font-weight: bold">
                                <td>Gesamtbetrag</td>
                                <td>{{ single_price($order->grand_total) }}</td>
                                <td></td>
                            </tr>
                        </table>
                </td>
                </tr>
                <tr>
                    <td>
                        <p style="color: #7bb542;">Danke für die Rücksichtnahme auf die Umwelt</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul style="list-style-type: none; padding: 0">
                            <li>
                                <a href="https://www.facebook.com/Mytreety"><img src="{{url('/')}}/public/frontend/emails/facebook-icon.png"
                                        alt="Mytreety on Facebook" /></a>
                            </li>
                            <li>
                                <a href="https://twitter.com/Mytreety"><img src="{{url('/')}}/public/frontend/emails/twitter-icon.png"
                                        alt="Mytreety on Twitter" /></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/company/mytreety/"><img src="{{url('/')}}/public/frontend/emails/linkedin-icon.png"
                                        alt="Mytreety on Linkedin" /></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/mytreety/"><img src="{{url('/')}}/public/frontend/emails/instagram-icon.png"
                                        alt="Mytreety on Instagram" /></a>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
    </center>
    </td>
    </tr>
    <tr>
        {{-- <td>
            <button style="
                        background-color: #7bb542;
                        font-size: 20px;
                        font-weight: 600;
                        color: #ffffff;
                        margin: 40px auto;
                        text-decoration: underline;
                        border: none;
                        padding: 20px;
                        padding-inline: 40px;
                        border-radius: 15px;
                        box-shadow: 4px 4px 6px #b5d884 inset;
                      ">
              <a href="{{url('/purchase_history')}}" style="color: #ffff;">Hier klicken</a>
            </button>
        </td> --}}
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td>
                        <table style="margin: auto auto 20px auto;">
                            <tr class="three-underlines">
                                <td><a href="{{url('buyer-policy')}}">Käufer Richtlinien</a></td>
                                <td><a href="{{route('terms')}}">AGB</a></td>
                                <td><a href="{{url('/privacypolicy')}}">Datenschutzbestimmungen</a></td>
                              </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align: left; color:#707070; padding-bottom: 8px">
            Diese E-Mail ist lediglich eine Bestätigung des Eingangs
            Ihrer Bestellung. Ihr Vertrag über den Kauf dieser Artikel
            kommt erst zustande, wenn wir Sie per E-Mail darüber
            informieren, dass die Artikel versandt wurden (und die
            entsprechenden Informationen zur Sendungsverfolgung
            anhängen). Bitte antworten Sie nicht auf diese Nachricht.
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </div>
    </center>
</body>

</html>

{{-- <html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyTreety</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Roboto';
            color: #333542;
        }

        body {
            font-size: .875rem;
        }

        .gry-color *,
        .gry-color {
            color: #878f9c;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {}

    </style>
</head>

<body>
    <div>
        @php
            $logo = get_setting('header_logo');
        @endphp
        <div style="background: #eceff4;padding: 1.5rem;">
            <table>
                <tr>
                    <td>
                        @if ($logo != null)
                            <img loading="lazy" src="{{ uploaded_asset($logo) }}" height="40"
                                style="display:inline-block;">
                        @else
                            <img loading="lazy" src="{{ static_asset('{{url('/')}}/public/frontend/emails/img/logo.png') }}" height="40"
                                style="display:inline-block;">
                        @endif
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1.2rem;" class="strong">{{ get_setting('site_name') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ get_setting('contact_address') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order ID') }}:</span>
                        <span class="strong">{{ $order->code }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order Date') }}:</span>
                        <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
                </tr>
            </table>

        </div>

        <div style="padding: 1.5rem;padding-bottom: 0">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">{{ translate('Bill to') }}:</td>
                </tr>
                <tr>
                    <td class="strong">{{ $shipping_address->name }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }},
                        {{ $shipping_address->country }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 1.5rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="35%">{{ translate('Product Name') }}</th>
                        <th width="15%">{{ translate('Delivery Type') }}</th>
                        <th width="10%">{{ translate('Qty') }}</th>
                        <th width="15%">{{ translate('Unit Price') }}</th>
                        <th width="10%">{{ translate('Tax') }}</th>
                        <th width="15%" class="text-right">{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                            <tr class="">
                                <td>{{ $orderDetail->product->getTranslation('name') }} @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif
                                </td>
                                <td>
                                    @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                        {{ translate('Home Delivery') }}
                                    @elseif ($order->shipping_type == 'pickup_point')
                                        @if ($order->pickup_point != null)
                                            {{ $order->pickup_point->getTranslation('name') }}
                                            ({{ translate('Pickip Point') }})
                                        @endif
                                    @endif
                                </td>
                                <td class="gry-color">{{ $orderDetail->quantity }}</td>
                                <td class="gry-color currency">
                                    {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                <td class="gry-color currency">
                                    {{ single_price($orderDetail->tax / $orderDetail->quantity) }}</td>
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
                <tbody>
                    <tr>
                        <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                        <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
                    </tr>
                    <tr>
                        <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
                        <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
                        <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="gry-color text-left">{{ translate('Coupon') }}</th>
                        <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                    </tr>
                    <tr>
                        <th class="text-left strong">{{ translate('Grand Total') }}</th>
                        <td class="currency">{{ single_price($order->grand_total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html> --}}
