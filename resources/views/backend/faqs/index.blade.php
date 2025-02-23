@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All FAQs') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('faqs.create') }}" class="btn btn-primary">
                    <span>{{ translate('Add New FAQ') }}</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-block d-md-flex">
            <h5 class="mb-0 h6">{{ translate('All FAQs') }}</h5>
            <form class="" id="sort_categories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type name & Enter') }}">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>

                        <th>{{ translate('Title') }}</th>
                        <th data-breakpoints="lg">{{ translate('Details') }}</th>
                        <th data-breakpoints="lg">{{ translate('Group') }}</th>
                        <th width="10%" class="text-right">{{ translate('Options') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($faqs as $key => $faq)
                        <tr>
                            <td>{{ $key + 1 + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                            <td>{{ $faq->getTranslation('title') }}</td>
                            <td>{{ $faq->getTranslation('sub_title') }}</td>
                            <td>
                                @if ($faq->type == 1)
                                    Vendor Portal
                                @elseif($faq->type == 2)
                                    Charity Portal
                                @elseif($faq->type == 3)
                                    Cus-orders
                                @elseif($faq->type == 4)
                                    Cus-payment
                                @elseif($faq->type == 5)
                                    Cus-refund
                                @elseif($faq->type == 6)
                                    Cus-account
                                @elseif($faq->type == 7)
                                    Cus-shipping
                                @elseif($faq->type == 8)
                                    Cus-social
                                @elseif($faq->type == 9)
                                    Cus-partner
                                @endif
                            </td>

                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('faqs.edit', ['faq' => $faq->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('faqs.destroy', $faq->id) }}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $faqs->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection


@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
@endsection
