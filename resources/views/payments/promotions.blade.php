@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
    <script src="{{ asset('/js/datatables.min.js') }}"></script>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">View Promotions</h3>
                        </div>
                        <div class="card-body  table-responsive" id="view_students_card">
                            @if (!(is_array($promotions_data) && empty($promotions_data)))
                                <table class="table table-sm table-bordered table-striped" style="font-size: 10pt"
                                    id="promotions_table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Start date</th>
                                            <th>End date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($promotions_data as $key => $promotion)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $promotion->promocode }}</td>
                                                <td>{{ $promotion->description }}</td>
                                                <td>{{ $promotion->discount_amount }}</td>
                                                <td>{{ date('d-m-Y', strtotime($promotion->start_date)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($promotion->end_date)) }}</td>
                                                <td>
                                                    <i class="fa fa-edit text-info edit_promo" ref="{{ $promotion->id }}"
                                                        style="cursor: pointer"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>


    <div class="modal" id="modal_create" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_body">

                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            var table = $("#promotions_table").DataTable({
                order: [
                    [0, 'asc']
                ],
                lengthMenu: [10, 25, 100],
                pageLength: 25
            });


            $(document).on('click', '.edit_promo', function(e) {
                e.preventDefault();
                const edit_id = $(this).attr('ref');

                $.ajax({
                    type: "post",
                    url: "{{ route('manage_promotions') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'edit_id': edit_id
                    },
                    success: function(response) {
                        preloader.stop();
                        $('#modal_title').html("Manage Promotions");
                        $('#modal_body').html(response);
                        $('#modal_create').modal('show');
                    }
                });
            });



        });
    </script>
@endsection
