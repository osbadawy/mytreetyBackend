<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  translate('Vendor Invoice') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">

	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0;
		}
        .footer {
          position: fixed;
          left: 0;
          bottom: 0;
          width: 100%;
          background-color: #EEF5E5;
          color: #000;
          text-align: center;
          margin-top: 40%;
        }
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #EEF5E5;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
	</style>
</head>
<body>
	<div>

		@php
			$logo = get_setting('header_logo');
		@endphp

		<div style="background: #EEF5E5;padding: 1rem;">
			<table>
				<tr>
					<td>
						@if($logo != null)
							<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
						@else
							<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">
						@endif
					</td>
					<td style="font-size: 1.5rem;" class="text-right strong">Rechnung des Verkäufers</td>
				</tr>
			</table>
			<table>
				<tr>
                    {{-- {{ get_setting('site_name') }}  --}}
					<td style="font-size: 1rem;" class="strong"> <br>
                        <span style="color: #7AB541">W.</span> https://www.mytreety.com <br>

                        <span style="color: #7AB541">T.</span> (+49)15227826982 <br>

                        <span style="color: #7AB541">A.</span> Röpkestrasse 35, 40235, Düsseldorf, Germany <br>

                        <span style="color: #7AB541">Registrierungsnumme:</span> HRB 95195 (Amtsgericht Düsseldorf) <br>
                        <span style="color: #7AB541">Umsatzsteueridentifikationsnummer:</span> DE349232308 <br><br>

                    </td>
					<td class="text-right"></td>
				</tr>
				<tr>
                    {{-- {{ get_setting('contact_address') }} --}}
					<td class="gry-color small"></td>
					<td class="text-right"></td>
				</tr>
				<tr>
                    {{-- {{  translate('Email') }}: {{ get_setting('contact_email') }} --}}
					<td class="gry-color small"></td>
					<td class="text-right small"><span class="gry-color small">Bestellnummer:</span> <span class="strong">@if($order){{ $order->code }}@endif</span></td>
				</tr>
				<tr>
                    {{-- {{  translate('Phone') }}: {{ get_setting('contact_phone') }} --}}
					<td class="gry-color small"></td>
					<td class="text-right small"><span class="gry-color small">Bestelldatum:</span> <span class=" strong">@if($order){{ date('d-m-Y', $order->date) }}@endif</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1rem;padding-bottom: 0">
            <table>
				@php
                    $email=null;
                    $tax_number=null;
                    $iban=null;
                    $bank_acc_name=null;
                    $shipping_address=null;
                    $seller=null;
                    $name=null;
                    $phone=null;
                    $orderDetail=null;
                    if($order){

                        $shipping_address = json_decode($order->shipping_address);
                        $seller= \App\Models\Seller::where('user_id',$order->seller_id)->first();
                    }

                    if($seller){
                        $info=json_decode($seller->verification_info);

                        $name=$seller->name;
                        if($info){
                        $email=$info[0]->value;
                        $phone=$info[5]->value;
                        $tax_number=$info[3]->value;
                        $iban=$seller->bank_acc_no;
                        $bank_acc_name=$seller->bank_acc_name;
                    }

                    }


				@endphp
                {{-- <td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }}, {{ $shipping_address->postal_code }}, {{ $shipping_address->country }}</td> --}}
				<tr><td class="strong small gry-color">Verkäufer Details:</td></tr>
                <tr><td class="strong">Umsatzsteueridentifikationsnummer:</td><td class="strong text-left">Bankdaten:</td></tr>
				<tr><td class="strong">{{$name}}</td><td class="strong text-left">IBAN/Kontonummer:{{$iban}}</td></tr>
				<tr><td class="strong text-left">Name des Empfängers:{{$bank_acc_name}}</td></tr>
				<tr><td class="gry-color small">{{ translate('Email') }}: {{ $email }}</td></tr>
				<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $phone }}</td></tr>
			</table>
		</div>

	    <div style="padding: 1rem;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #EEF5E5;">
	                    <th width="35%" class="text-left">Produktname</th>
						{{-- <th width="15%" class="text-left">{{ translate('Delivery Type') }}</th> --}}
	                    <th width="10%" class="text-left">ANZAHL</th>
	                    <th width="15%" class="text-left">Einzelpreis</th>
	                    {{-- <th width="10%" class="text-left">{{ translate('Tax') }}</th> --}}
	                    <th width="15%" class="text-right">Insgesamt</th>
	                </tr>
				</thead>
				<tbody class="strong">
                    @if($order)
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td>{{ $orderDetail->product->name }} @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif</td>
								{{-- <td>
									@if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
										{{ translate('Home Delivery') }}
									@elseif ($order->shipping_type == 'pickup_point')
										@if ($order->pickup_point != null)
											{{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
										@endif
									@endif
								</td> --}}
								<td class="">{{ $orderDetail->quantity }}</td>
								<td class="currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								{{-- <td class="currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td> --}}
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
                    @endif
	            </tbody>
			</table>
		</div>

	    <div style="padding:0 1.5rem;">
	        <table class="text-right sm-padding small strong">
	        	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
	        	</thead>
		        <tbody>
			        <tr>
			            <td class="text-left">
                            {{-- @php
                                $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                            @endphp
                            {!! str_replace($removedXML,"", QrCode::size(100)->generate($order->code)) !!} --}}
			            </td>
			            <td>
					        <table class="text-right sm-padding small strong">
						        <tbody>
							        <tr>
							            <th class="gry-color text-left">Zwischensumme</th>
							            <td class="currency">@if($order) {{ single_price($order->orderDetails->sum('price')) }} @endif</td>
							        </tr>
							        <tr>
							            <th class="gry-color text-left">Versandkosten</th>
							            <td class="currency">@if($order) {{ single_price($order->orderDetails->sum('shipping_cost')) }} @endif </td>
							        </tr>
							        {{-- <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
							        </tr> --}}
				                    {{-- <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
							            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
							        </tr> --}}
                                    <tr>
							            <th class="text-left strong" >Mytreety-Provision</th>
                                        @php
                                        $price=0;
                                        if($orderDetail){

                                            $price=0.10*(0.81 * $orderDetail->price);
                                        }

                                        @endphp
							            <td class="currency">{{ single_price($price) }}<br>

                                        </td>

							        </tr>
							        <tr>
							            <th class="text-left strong" >Fälliger Betrag</th>
							            <td class="currency">@if($order){{ single_price($order->grand_total - $price) }}@endif<br>

                                        </td>

							        </tr>
                                    <tr>
                                        <th class="text-left strong">
                                            Alle Bestellungen inklusive Mehrwertsteue
                                        </th>
                                    </tr>
                                    <tr>
							            {{-- <th class="text-left strong"></th> --}}
							            <th class="text-left strong "> <span style="color: red">Mytreety wird den fälligen Betrag innerhalb von 5 Werktagen nach Ablauf der Rückerstattungsfrist senden.</span>


                                        </th>

							        </tr>


						        </tbody>
						    </table>
			            </td>
			        </tr>
		        </tbody>
		    </table>
	    </div>

        <footer class="footer">
            <div >
                Diese Rechnung ist nur vorläufig und kann Fehler enthalten; in diesem Fall wenden Sie sich bitte an das Mytreety-Team unter <a href="mailto:vendor@mytreety.com" >vendor@mytreety.com</a>               <br>
                Bank: Solarisbank AG (Cuvrystraße 53, 10997 Berlin, Germany), IBAN: DE26110101015303247754, BIC: SOBKDEB2XXX <br>

                <a href="{{url('/seller-policy')}}">Verkäufer-Richtlinien</a> &nbsp;
                <a href="{{url('/buyer-policy')}}"> Käufer-Richtlinien (widerrufsrecht)</a> &nbsp;
                <a href="{{url('/terms')}}">Allgemeine Geschäftsbedingungen (AGB)</a> &nbsp;
                <a href="{{url('/privacypolicy')}}">Datenschutzerklärung</a> &nbsp;


            </div>
        </footer>

	</div>
</body>
</html>
