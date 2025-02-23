@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Faq Information') }}</h5>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-fill border-light">
                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                    href="{{ route('faqs.edit', ['faq' => $faq->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                        height="11" class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <form class="p-4" action="{{ route('faqs.update', $faq->id) }}"
                        method="POST" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('title') }} <i
                                    class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-md-9">
                                <input type="text" name="title"
                                    value="{{ $faq->getTranslation('title', $lang) }}" class="form-control"
                                    id="name" placeholder="{{ translate('title') }}" required>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Details') }} <i
                                    class="las la-language text-danger"
                                    title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-md-9">
                                <textarea name="sub_title" rows="5" class="form-control" required>{{ $faq->getTranslation('sub_title', $lang) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Type') }}</label>
                            <div class="col-md-9">
                                <select name="type" required class="form-control aiz-selectpicker mb-2 mb-md-0">


                                    <option value="1" @if ($faq->type == 1) selected @endif >Vendor Portal</option>
                                    <option value="2" @if ($faq->type == 2) selected @endif >Charity Portal</option>
                                    <option value="3" @if ($faq->type == 3) selected @endif >Cus-orders</option>
                                    <option value="4" @if ($faq->type == 4) selected @endif >Cus-payment</option>
                                    <option value="5" @if ($faq->type == 5) selected @endif >Cus-refund</option>
                                    <option value="6" @if ($faq->type == 6) selected @endif >Cus-account</option>
                                    <option value="7" @if ($faq->type == 7) selected @endif >Cus-shipping</option>
                                    <option value="8" @if ($faq->type == 8) selected @endif >Cus-social</option>
                                    <option value="9" @if ($faq->type == 9) selected @endif >Cus-partner</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <a href="{{ route('faqs.index') }}"
                                class="btn btn-secondary">{{ translate('Back') }} </a>
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
