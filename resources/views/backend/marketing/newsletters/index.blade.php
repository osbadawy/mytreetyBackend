@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Send Newsletter')}}</h5>
            </div>

            <div class="card-body">
                <form class="form-horizontal" action="{{ route('newsletters.send') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Customers')}})</label>
                        <div class="col-sm-10">
                            @php $customers=$users->where('user_type','customer') @endphp
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($customers as $customer)
                                    <option value="{{$customer->email}}">{{$customer->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Charites')}})</label>
                        <div class="col-sm-10">
                            @php $charities=$users->where('user_type','charity') @endphp
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($charities as $charity)
                                    <option value="{{$charity->email}}">{{$charity->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Vendors')}})</label>
                        <div class="col-sm-10">
                            @php $vendors=$users->where('user_type','seller') @endphp
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($vendors as $vendor)
                                    <option value="{{$vendor->email}}">{{$vendor->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Subscribers')}})</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" name="subscriber_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($subscribers as $subscriber)
                                    <option value="{{$subscriber->email}}">{{$subscriber->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="subject">{{translate('Newsletter subject')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="subject" id="subject" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Newsletter content')}}</label>
                        <div class="col-sm-10">
                            <textarea rows="8" class="form-control aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]], ["insert", ["link", "picture"]],["view", ["undo","redo"]]]' name="content" required></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Send')}}</button>
                    </div>
                  </form>
              </div>
        </div>
    </div>
</div>

@endsection
