@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Charities')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_charitys" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Charities') }}</h5>
            </div>

            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                </div>
            </div>

            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_charitys()">
                    <option value="">{{translate('Filter by Approval')}}</option>
                    <option value="1"  @isset($approved) @if($approved == 'paid') selected @endif @endisset>{{translate('Approved')}}</option>
                    <option value="0"  @isset($approved) @if($approved == 'unpaid') selected @endif @endisset>{{translate('Non-Approved')}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email & Enter') }}">
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <!--<th data-breakpoints="lg">#</th>-->
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </div>
                    </th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    {{-- <th data-breakpoints="lg">{{translate('Charity Operations')}}</th> --}}
                    {{-- <th data-breakpoints="lg">{{translate('Country')}}</th> --}}
                    {{-- <th data-breakpoints="lg">{{translate('Postal Code')}}</th>
                    <th data-breakpoints="lg">{{translate('Address')}}</th>
                    <th data-breakpoints="lg">{{translate('City')}}</th> --}}
                    <th data-breakpoints="lg">{{translate('Total Earned')}}</th>
                    <th data-breakpoints="lg">{{translate('Left Earned')}}</th>
                    <th data-breakpoints="lg">{{translate('Attachment')}}</th>
                    <th data-breakpoints="lg">{{translate('Created at')}}</th>
                    <th data-breakpoints="lg">{{translate('Verification Info')}}</th>
                    <th data-breakpoints="lg">{{translate('Approval')}}</th>
                    {{-- <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th> --}}
                    {{-- <th data-breakpoints="lg">{{ translate('Due to charity') }}</th> --}}
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($charities as $key => $charity)

                    @if($charity->user != null)
                        <tr>
                            <!--<td>{{ ($key+1) + ($charities->currentPage() - 1)*$charities->perPage() }}</td>-->
                            <td>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$charity->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>@if($charity->user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$charity->name}}</td>
                            <td>{{$charity->user->phone}}</td>
                            <td>{{$charity->user->email}}</td>
                            {{-- <td>{{$charity->operations}}</td> --}}
                            {{-- <td>{{$charity->country}}</td>
                            <td>{{$charity->postal_code}}</td>
                            <td>{{$charity->address}}</td>
                            <td>{{$charity->city}}</td> --}}
                            <td>{{$charity->total_earned}}</td>
                            <td>{{$charity->left_earned}}</td>
                            <td>{{$charity->atatchment}}</td>
                            <td>{{$charity->created_at}}</td>

                            <td>
                                @if ($charity->verification_info != null)
                                    <a href="{{ route('charities.show_verification_request', $charity->id) }}">
                                      <span class="badge badge-inline badge-info">{{translate('Show')}}</span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_approved(this)" value="{{ $charity->id }}" type="checkbox" <?php if($charity->verification_status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            {{-- <td>{{ \App\Models\Product::where('user_id', $charity->user->id)->count() }}</td> --}}
                            {{-- <td>
                                @if ($charity->admin_to_pay >= 0)
                                    {{ single_price($charity->admin_to_pay) }}
                                @else
                                    {{ single_price(abs($charity->admin_to_pay)) }} (Due to Admin)
                                @endif
                            </td> --}}
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                      <i class="las la-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        <a href="#" onclick="show_charity_profile('{{$charity->id}}');"  class="dropdown-item">
                                          {{translate('Profile')}}
                                        </a>
                                        <a href="{{route('charities.login', encrypt($charity->id))}}" class="dropdown-item">
                                          {{translate('Log in as this Charity')}}
                                        </a>
                                        {{-- <a href="#" onclick="show_seller_payment_modal('{{$charity->id}}');" class="dropdown-item">
                                          {{translate('Go to Payment')}}
                                        </a> --}}
                                        {{-- <a href="{{route('sellers.payment_history', encrypt($charity->id))}}" class="dropdown-item">
                                          {{translate('Payment History')}}
                                        </a> --}}
                                        <a href="{{route('charities.edit', encrypt($charity->id))}}" class="dropdown-item">
                                          {{translate('Edit')}}
                                        </a>
                                        @if($charity->user->banned != 1)
                                          <a href="#" onclick="confirm_ban('{{route('charities.ban', $charity->id)}}');" class="dropdown-item">
                                            {{translate('Ban this seller')}}
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                          </a>
                                        @else
                                          <a href="#" onclick="confirm_unban('{{route('charities.ban', $charity->id)}}');" class="dropdown-item">
                                            {{translate('Unban this seller')}}
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                          </a>
                                        @endif
                                        <a href="#" class="dropdown-item confirm-delete" data-href="{{route('charities.destroy', $charity->id)}}" class="">
                                          {{translate('Delete')}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
              {{ $charities->appends(request()->input())->links() }}
            </div>
        </div>
    </from>
</div>

@endsection

@section('modal')
	<!-- Delete Modal -->
	@include('modals.delete_modal')

	<!-- charity Profile Modal -->
	<div class="modal fade" id="profile_modal">
		<div class="modal-dialog">
			<div class="modal-content" id="profile-modal-content">

			</div>
		</div>
	</div>

	<!-- charity Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	<!-- Ban charity Modal -->
	<div class="modal fade" id="confirm-ban">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
					<button type="button" class="close" data-dismiss="modal">
					</button>
				</div>
				<div class="modal-body">
						<p>{{translate('Do you really want to ban this charity?')}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
					<a class="btn btn-primary" id="confirmation">{{translate('Proceed!')}}</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Unban charity Modal -->
	<div class="modal fade" id="confirm-unban">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
						<button type="button" class="close" data-dismiss="modal">
						</button>
					</div>
					<div class="modal-body">
							<p>{{translate('Do you really want to ban this charity?')}}</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
						<a class="btn btn-primary" id="confirmationunban">{{translate('Proceed!')}}</a>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_charity_profile(id){
            $.post('{{ route('charities.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile_modal #profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('charities.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Approved sellers updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

        // function bulk_delete() {
        //     var data = new FormData($('#sort_sellers')[0]);
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{route('bulk-seller-delete')}}",
        //         type: 'POST',
        //         data: data,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         success: function (response) {
        //             if(response == 1) {
        //                 location.reload();
        //             }
        //         }
        //     });
        // }

    </script>
@endsection
