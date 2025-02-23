@extends('backend.layouts.app')

@section('content')

<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6">{{ translate('Charity Verification') }}</h5>
      <div class="pull-right clearfix">
        <a href="{{ route('charities.reject', $charity->id) }}" class="btn btn-default d-innline-block">{{translate('Reject')}}</a></li>
        <a href="{{ route('charities.approve', $charity->id) }}" class="btn btn-circle btn-dark d-innline-block">{{translate('Accept')}}</a>
      </div>
  </div>
  <div class="card-body row">
      <div class="col-md-5">
          <h6 class="mb-4">{{ translate('Charity Info') }}</h6>
          <p class="text-muted">
              <strong>{{ translate('Name') }} :</strong>
              <span class="ml-2">{{ $charity->user->name }}</span>
          </p>
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


          <br>


      </div>
      <div class="col-md-5">
        <h6 class="mb-4">{{ translate('Verification Info') }}</h6>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%">
              <tbody>
                  @foreach (json_decode($charity->verification_info) as $key => $info)
                      <tr>
                          <th class="text-muted">{{ $info->label }}</th>
                          @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                              <td>{{ $info->value }}</td>
                          @elseif ($info->type == 'multi_select')
                              <td>
                                  {{ implode(', ', json_decode($info->value)) }}
                              </td>
                          @elseif ($info->type == 'file')
                              <td>
                                  <a href="{{ my_asset($info->value) }}" target="_blank" class="btn-info">{{translate('Click here')}}</a>
                              </td>
                          @endif
                      </tr>
                  @endforeach
              </tbody>
          </table>
          <div class="text-center">
              <a href="{{ route('charities.reject', $charity->id) }}" class="btn btn-sm btn-default d-innline-block">{{translate('Reject')}}</a></li>
              <a href="{{ route('charities.approve', $charity->id) }}" class="btn btn-sm btn-dark d-innline-block">{{translate('Accept')}}</a>
          </div>
      </div>
  </div>
</div>

@endsection
