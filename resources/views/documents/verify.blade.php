@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
<script src="{{ asset('/js/datatables.min.js') }}"></script>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Document Verification</h3>
            </div>
            
            <div class="card-body table-responsive">
                @if($documents->count())
                <table id="documents_table" class="table table-bordered table-striped dataTable dtr-inline" style="font-size:10pt;">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Student</th>
                            <th>Category</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th>Welcome Mail Screenshot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $key => $doc)
                        <tr id="row_{{ $doc->id }}">
                            {{-- ✅ SL No with pagination support --}}
                            <td>{{ ($documents->currentPage() - 1) * $documents->perPage() + ($key + 1) }}</td>

                            <td>
                                <a href="{{ route('view_profile', [1, $doc->student_id]) }}" target="_blank">
                                    {{ $doc->student->first_name }} {{ $doc->student->last_name }}
                                </a>
                            </td>
                            <td>{{ $doc->doc_category_id }}</td>
                            <td>
                                <a href="{{ asset('storage/'.$doc->document_path) }}" target="_blank">View File</a>
                            </td>
                            <td class="{{ $doc->status === 'approved' 
                ? 'text-success' 
                : ($doc->status === 'rejected' 
                    ? 'text-danger' 
                                    : 'text-warning') }}">
                    {{ $doc->status ? ucfirst($doc->status) : 'Verification Pending...' }}
                </td>

                            <td>
                                @if($doc->verification_screenshot)
                                    <a href="{{ asset('storage/'.$doc->verification_screenshot) }}" 
                                       target="_blank" class="btn btn-sm btn-secondary mb-1">
                                        View Screenshot
                                    </a>
                                @endif
                                <input type="file" class="form-control form-control-sm screenshot-input"
                                       data-id="{{ $doc->id }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info update-status"
                                        data-id="{{ $doc->id }}" data-status="approved">
                                    Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger update-status"
                                        data-id="{{ $doc->id }}" data-status="rejected">
                                    Reject
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $documents->links() }}
                @else
                    <p>No documents found.</p>
                @endif
            </div>
        </div>
    </div>
</section>


@endsection
@section('script')

<script>
$(document).ready(function() {
    

    //  Approve/Reject with optional screenshot upload
    $(document).on('click', '.update-status', function(e) {
        
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).data('status');
        var fileInput = $('tr#row_' + id + ' .screenshot-input')[0];

        var formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('document_id', id);
        formData.append('status', status);
        console.log(status);
        
        if (fileInput && fileInput.files.length > 0) {
            formData.append('screenshot', fileInput.files[0]);
        }

        if(confirm("Are you sure to mark this document as " + status + "?")) {
            $.ajax({
                url: "{{ route('documents.update-status') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        let row = $('#row_' + id);
                        let statusCell = row.find('.status-cell');
                        window.location.reload();
                        // // Update plain colored text
                        // if (status === 'approved') {
                        //     statusCell.html('<span style="color:green; font-weight:bold;">Approved</span>');
                        // } else if (status === 'rejected') {
                        //     statusCell.html('<span style="color:red; font-weight:bold;">Rejected</span>');
                        // } else {
                        //     statusCell.html('<span style="color:orange; font-weight:bold;">Pending</span>');
                        // }

                        // //  Update screenshot link if new screenshot uploaded
                        // if (response.screenshot_url) {
                        //     let screenshotCell = row.find('td:nth-child(6)');
                        //     screenshotCell.find('.btn-secondary').remove();
                        //     screenshotCell.prepend(
                        //         '<a href="'+response.screenshot_url+'" target="_blank" class="btn btn-sm btn-secondary mb-1">View Screenshot</a>'
                        //     );
                        // }
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + (xhr.responseJSON?.message || "Something went wrong"));
                }
            });
        }
    });
});


$('#documents_table').DataTable({
        lengthMenu: [10, 25, 100, 500],
        pageLength: 25
    });


</script>

@endsection
