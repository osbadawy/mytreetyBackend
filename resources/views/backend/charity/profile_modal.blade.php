<div class="modal-body">

  <div class="text-center">
      <span class="avatar avatar-xxl mb-3">
          <img src="{{ uploaded_asset($charity->user->avatar) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
      </span>
      <h1 class="h5 mb-1">{{ $charity->user->name }}</h1>
      {{-- <p class="text-sm text-muted">{{ $charity->user->shop->name }}</p> --}}

      {{-- <div class="pad-ver btn-groups">
          <a href="{{ $charity->user->shop->facebook }}" class="btn btn-icon demo-pli-facebook icon-lg add-tooltip" data-original-title="Facebook" data-container="body"></a>
          <a href="{{ $charity->user->shop->twitter }}" class="btn btn-icon demo-pli-twitter icon-lg add-tooltip" data-original-title="Twitter" data-container="body"></a>
          <a href="{{ $charity->user->shop->google }}" class="btn btn-icon demo-pli-google-plus icon-lg add-tooltip" data-original-title="Google+" data-container="body"></a>
      </div> --}}
  </div>
  <hr>

  <!-- Profile Details -->
  <h6 class="mb-4">{{translate('Drtails')}}</h6>
  <p class="text-muted">
    <strong>{{translate('Email')}}</strong>
    <span class="ml-2">{{ $charity->user->email }}</span>
</p>
<p class="text-muted">
    <strong>{{translate('Country')}}</strong>
    <span class="ml-2">{{ $charity->country }}</span>
</p>
<p class="text-muted">
    <strong>{{translate('City')}}</strong>
    <span class="ml-2">{{ $charity->city }}</span>
</p>
<p class="text-muted">
    <strong>{{translate('Postal Code')}}</strong>
    <span class="ml-2">{{ $charity->postal_code }}</span>
  </p>
  <p class="text-muted">
      <strong>{{translate('Address')}}</strong>
      <span class="ml-2">{{ $charity->address }}</span>
  </p>
<p class="text-muted">
  <strong>{{translate('Phone')}}</strong>
  <span class="ml-2">{{ $charity->user->phone }}</span>
</p>

<p class="text-muted">
  <strong>{{translate('Operations')}}</strong>
  <span class="ml-2">{{ $charity->operations }}</span>
</p>


  {{-- <p><i class="demo-pli-map-marker-2 icon-lg icon-fw mr-1"></i>{{ $charity->user->shop->address }}</p> --}}
  {{-- <p><a href="{{ route('shop.visit', $charity->user->shop->slug) }}" class="btn-link"><i class="demo-pli-internet icon-lg icon-fw mr-1"></i>{{ $charity->user->shop->name }}</a></p> --}}
  <p><i class="demo-pli-old-telephone icon-lg icon-fw mr-1"></i>{{ $charity->user->phone }}</p>

  <h6 class="mb-4">{{translate('Bank Info')}}</h6>
  <p>{{translate('Bank Name')}} : {{ $charity->bank_name }}</p>
  <p>{{translate('Bank Acc Name')}} : {{ $charity->bank_acc_name }}</p>
  <p>{{translate('Bank Acc Number')}} : {{ $charity->bank_acc_no }}</p>
  <p>{{translate('Bank Routing Number')}} : {{ $charity->bank_routing_no }}</p>

  <br>

  {{-- <div class="table-responsive">
      <table class="table table-striped mar-no">
          <tbody>
          <tr>
              <td>{{ translate('Total Products') }}</td>
              <td>{{ App\Models\Product::where('user_id', $charity->user->id)->get()->count() }}</td>
          </tr>
          <tr>
              <td>{{ translate('Total Orders') }}</td>
              <td>{{ App\Models\OrderDetail::where('charity_id', $charity->user->id)->get()->count() }}</td>
          </tr>
          <tr>
              <td>{{ translate('Total Sold Amount') }}</td>
              @php
                  $orderDetails = \App\Models\OrderDetail::where('charity_id', $charity->user->id)->get();
                  $total = 0;
                  foreach ($orderDetails as $key => $orderDetail) {
                      if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                          $total += $orderDetail->price;
                      }
                  }
              @endphp
              <td>{{ single_price($total) }}</td>
          </tr>
          <tr>
              <td>{{ translate('Wallet Balance') }}</td>
              <td>{{ single_price($charity->user->balance) }}</td>
          </tr>
          </tbody>
      </table>
  </div> --}}
</div>
