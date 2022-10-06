@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'custom_pages'])
@section('title', isset($title) ? $title : '')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/customPage/jquery-ui.css')}}">
@endsection
@section('content')
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Settings')}}</li>
                    <li class="active-item">{{__('Custom Pages')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="user-management pt-4">
        <div class="row">
            <div class="col-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Custom Pages List')}}</h3>
                    </div>
                    <div class="right d-flex align-items-center">
                        <div class="add-btn">
                            <a href="{{route('adminCustomPageAdd')}}">{{__('+ Add New Page')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-area">
                    <div>
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th>{{__('Slug')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Created At')}}</th>
                                <th class="text-center">{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="sortable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $('#table').DataTable({
                processing: true,
                serverSide: true,

                responsive: true,
                ajax: '{{route('adminCustomPageList')}}',
                order: [2, 'desc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "key"},
                    {"data": "type"},
                    {"data": "title"},
                    {"data": "created_at"},
                    {"data": "actions"}
                ]
            });
        })(jQuery);
    </script>
    <script src="{{asset('assets/customPage/jquery-3.6.0.js')}}"></script>
    <script src="{{asset('assets/customPage/jquery-ui.js')}}"></script>
    <script>
        (function($) {
            "use strict";
            $(function () {
                $("#sortable").sortable();
                $("#sortable").disableSelection();
            });

            $("#sortable").sortable({

                update: function () {
                    var l_ar = [];
                    $(".shortable_data").each(function (index, data) {
                        l_ar.push($(this).val());
                    });

                    $.get("{{route('customPageOrder')}}?vals=" + l_ar, function (data) {
                        $(".result").html(data);
                        VanillaToasts.create({
                            text: data.message,
                            backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                            type: 'success',
                            timeout: 3000
                        });
                    });
                }
            });
        })(jQuery);
    </script>
@endsection
