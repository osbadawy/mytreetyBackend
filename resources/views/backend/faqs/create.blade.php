@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Faq Information')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('faqs.store') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Title')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Title')}}" id="title" name="title" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Description')}}</label>
                        <div class="col-md-9">
                            <textarea name="sub_title" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Type') }}</label>
                        <div class="col-md-9">
                            <select name="type" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                <option value="1">Vendor Portal</option>
                                <option value="2">Charity Portal</option>
                                <option value="3">Cus-orders</option>
                                <option value="4">Cus-payment</option>
                                <option value="5">Cus-refund</option>
                                <option value="6">Cus-account</option>
                                <option value="7">Cus-shipping</option>
                                <option value="8">Cus-social</option>
                                <option value="9">Cus-partner</option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
