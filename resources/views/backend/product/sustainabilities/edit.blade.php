@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Sustainability Information') }}</h5>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-fill border-light">
                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                    href="{{ route('sustainabilities.edit', ['sustainability' => $sustainability->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                        height="11" class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <form class="p-4" action="{{ route('sustainabilities.update', $sustainability->id) }}"
                        method="POST" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name') }} <i
                                    class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-md-9">
                                <input type="text" name="name"
                                    value="{{ $sustainability->getTranslation('name', $lang) }}" class="form-control"
                                    id="name" placeholder="{{ translate('Name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Image') }}
                                <small>({{ translate('200x200') }})</small><i class="las la-language text-danger"
                                    title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image" class="selected-files"
                                        value="{{ $sustainability->getTranslation('image', $lang) }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Description') }} <i
                                    class="las la-language text-danger"
                                    title="{{ translate('Translatable') }}"></i></label>
                            <div class="col-md-9">
                                <textarea name="description" rows="5" class="form-control" required>{{ $sustainability->getTranslation('description', $lang) }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                        </div>
                    </div> --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('MIR') }}</label>
                            <div class="col-md-9">
                                <input type="text" name="weight" value="{{ $sustainability->weight }}"
                                    class="form-control" id="weight" placeholder="{{ translate('weight') }}" required>
                            </div>
                        </div>


                        <div class="form-group mb-0 text-right">
                            <a href="{{ route('sustainabilities.index') }}"
                                class="btn btn-secondary">{{ translate('Back') }} </a>
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
