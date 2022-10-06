@extends('admin.master',['menu'=>'coin', 'sub_menu'=>'coin_pair'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Coin Pair Settings')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
            <div class="col-sm-3 text-right">
                <a class="add-btn theme-btn" href="" data-toggle="modal" data-target="#pairModal"><i class="fa fa-plus"></i>{{__('Add New Pair')}}</a>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management p-4">
        <div class="row">
            <div class="col-12">
                <div class="table-area">
                    <div>
                        <table id="table" class=" table table-borderless custom-table display text-lg-center" width="100%">
                            <thead>
                            <tr>
                                <th scope="col" class="all">{{__('Base Coin')}}</th>
                                <th scope="col">{{__('Pair Coin')}}</th>
                                <th scope="col">{{__('Last Price')}}</th>
                                <th scope="col" class="all">{{__('Active Status')}}</th>
                                <th scope="col">{{__('Created At')}}</th>
                                <th scope="col" class="all">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($items[0]))
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{isset($item->parent_coin) ? check_default_coin_type($item->parent_coin->coin_type) : ''}}</td>
                                        <td>{{isset($item->child_coin) ? check_default_coin_type($item->child_coin->coin_type) : ''}}</td>
                                        <td>{{ $item->price }} {{isset($item->parent_coin) ? check_default_coin_type($item->parent_coin->coin_type) : ''}}</td>
                                        <td>
                                            <div>
                                                <label class="switch">
                                                    <input type="checkbox" onclick="return processForm('{{encrypt($item->id)}}')"
                                                           id="notification" name="" @if($item->status == STATUS_ACTIVE) checked @endif>
                                                    <span class="slider" for="status"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <ul class="d-flex activity-menu">
                                                <li class="viewuser">
                                                    <a data-toggle="modal" data-target="#pair_edit_{{$item->id}}" title="{{__('Edit')}}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </li>
                                                <li class="viewuser">
                                                    <a href="#delete1WV4d6uF6Ytu18v1Pl_{{($item->id)}}" data-toggle="modal" title="{{__("Delete")}}" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <div id="delete1WV4d6uF6Ytu18v1Pl_{{($item->id)}}" class="modal fade delete" role="dialog">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header"><h6 class="modal-title">{{__('Delete')}}</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                                <div class="modal-body"><p>{{ __('Do you want to delete ?')}}</p></div>
                                                                <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">{{__("Close")}}</button>
                                                                    <a class="btn btn-danger"href="{{route('coinPairsDelete', encrypt($item->id))}}">{{__('Confirm')}} </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div id="pair_edit_{{$item->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">{{__('Update Coin Pair')}}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{Form::open(['route' => 'saveCoinPairSettings', 'files' => 'true' ])}}
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <input type="hidden" name="edit_id" value="{{encrypt($item->id)}}">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{__('Base Coin')}}</label>
                                                                        <select class=" form-control" name="parent_coin_id"  style="width: 100%;">
                                                                            <option value="">{{__('Select')}}</option>
                                                                            @if(isset($coins[0]))
                                                                                @foreach($coins as $coin)
                                                                                    <option @if($item->parent_coin_id == $coin->id) selected @endif value="{{$coin->id}}">{{check_default_coin_type($coin->coin_type)}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{__('Pair Coin')}}</label>
                                                                        <select class=" form-control" name="child_coin_id"  style="width: 100%;">
                                                                            <option value="">{{__('Select')}}</option>
                                                                            @if(isset($coins[0]))
                                                                                @foreach($coins as $coin)
                                                                                    <option @if($item->child_coin_id == $coin->id) selected @endif value="{{$coin->id}}">{{check_default_coin_type($coin->coin_type)}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <button class="btn btn-info" type="submit">{{__('Update')}}</button>
                                                                </div>
                                                            </div>
                                                            {{Form::close()}}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="pairModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{__('Add Coin Pair')}}</h4>
                </div>
                <div class="modal-body">
                    {{Form::open(['route' => 'saveCoinPairSettings', 'files' => 'true' ])}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('Base Coin')}}</label>
                                <select class=" form-control" name="parent_coin_id"  style="width: 100%;">
                                    <option value="">{{__('Select')}}</option>
                                    @if(isset($coins[0]))
                                        @foreach($coins as $coin)
                                            <option value="{{$coin->id}}">{{check_default_coin_type($coin->coin_type)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('Pair Coin')}}</label>
                                <select class=" form-control" name="child_coin_id"  style="width: 100%;">
                                    <option value="">{{__('Select')}}</option>
                                    @if(isset($coins[0]))
                                        @foreach($coins as $coin)
                                            <option value="{{$coin->id}}">{{check_default_coin_type($coin->coin_type)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('Initial Price')}}</label>
                                <input type="text" class="form-control" name="price" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-info" type="submit">{{__('Save')}}</button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

            $('#table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                select: false,
                bDestroy: true
            });

            function processForm(active_id) {

                $.ajax({
                    type: "POST",
                    url: "{{ route('changeCoinPairStatus') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'active_id': active_id
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
            }
    </script>
@endsection
