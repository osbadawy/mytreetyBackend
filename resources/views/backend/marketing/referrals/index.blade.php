@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Referral History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        {{-- <th data-breakpoints="lg">#</th> --}}
                        <th>{{ translate('Referral Owner') }}</th>
                        <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                        <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referrals as $key => $referral)
                        <tr>
                            {{-- <td>{{ ($key+1) + ($referrals->currentPage() - 1)*$referrals->perPage() }}</td> --}}
                            <td>
                                <div class="text-truncate">{{ $referral->referral_owner_name }} -
                                    {{ $referral->referral_owner_email }}
                                    <br><b>{{ translate('Referral Code') }}:({{ $referral->referral_code }})</b>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate">{{ $referral->customer_email }}
                                    ({{ $referral->customer_name }})
                                </div>
                            </td>
                            <td>{{ date('d-m-Y', strtotime($referral->created_at)) }}</td>
                            <td class="text-right">
                                <a href="{{url("/admin/all_orders?referral_code=$referral->referral_code")}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" data-href="#"
                                    title="{{ translate('View Orders') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="clearfix">
            <div class="pull-right">
                {{ $referrals->appends(request()->input())->links() }}
            </div>
        </div> --}}
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">{{ translate('Top Referral Products') }}</h6>
        </div>
        <div class="card-body">
            <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                data-md-items="3" data-sm-items="2" data-arrows='true'>
                @foreach ($products_counts as $key => $products_count)
                    <div class="carousel-box">
                        <div
                            class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                            <div class="position-relative">

                                <img class="img-fit lazyload mx-auto h-210px"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ $products_count->product->thumbnail_img }}"
                                    alt="{{ $products_count->product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                            </div>
                            <div class="p-md-3 p-2 text-left">
                                <div class="fs-15">
                                    @if (home_base_price($products_count->product) != home_discounted_base_price($products_count->product))
                                        <del
                                            class="fw-600 opacity-50 mr-1">{{ home_base_price($products_count->product) }}</del>
                                    @endif
                                    <span
                                        class="fw-700 text-primary">{{ home_discounted_base_price($products_count->product) }}</span>
                                </div>
                                <div class="rating rating-sm mt-1">
                                    {{ renderStarRating($products_count->product->rating) }}
                                </div>
                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                    <a href="#" class="d-block text-reset">{{ $products_count->product->name }}</a>
                                </h3>
                            </div>
                            <h3 class="fw-600 fs-13 lh-1-4 mb-0" style="background-color: #77BCB7;color: #ffff; text-align: center">
                                <a href="#"
                                    class="d-block text-reset"><b>{{ translate('Referrer Count') }}: {{ $products_count->count }}</b></a>
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- @include('modals.delete_modal') --}}
@endsection
