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
                            <th>Upload Verification Screenshot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $key => $doc)
                        <tr id="row_{{ $doc->id }}">
                            {{-- SL No with pagination support --}}
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
                            <div class="d-flex flex-column gap-2">

                                {{-- Thumbnail --}}
                                <div class="d-flex align-items-center gap-2">
                                    @if($doc->verification_screenshot)
                                        <div class="border rounded p-1">
                                            <img src="{{ route('documents.view-screenshot', $doc->id) }}" alt="Screenshot"
                                                class="img-thumbnail" style="width:80px; height:auto;">
                                        </div>
                                    @else
                                        <span class="text-muted small">No file uploaded</span>
                                    @endif
                                </div>

                                {{-- File input --}}
                                <input type="file" class="form-control form-control-sm screenshot-input" data-id="{{ $doc->id }}">

                                {{-- Action buttons (same row) --}}
                                <div class="d-flex mt-1" style="gap: 4px;">
                            <button type="button" class="btn btn-sm btn-primary save-screenshot" data-id="{{ $doc->id }}" title="Save">
                                <i class="fas fa-upload"></i>
                            </button>
                            @if($doc->verification_screenshot)
                            <a href="{{ route('documents.view-screenshot', $doc->id) }}" target="_blank" 
                            class="btn btn-sm" 
                            style="background: #ffbc00; color: #000000; border-color: #ffbc00;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                        </div>


                            </div>
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
    $(document).on('click', '.save-screenshot', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var fileInput = $('tr#row_' + id + ' .screenshot-input')[0];

        if (!fileInput.files.length) {
            alert("Please select a file first.");
            return;
        }

        var formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('document_id', id);
        formData.append('screenshot', fileInput.files[0]);

        $.ajax({
            url: "{{ route('documents.upload-screenshot') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Refresh the view thumbnail
                    $('#screenshot-section-' + id).html(`
                    <a href="/documents/view-screenshot/${id}" target="_blank" class="btn btn-sm btn-secondary mb-2">View</a>
                    <br>
                    <img src="/documents/view-screenshot/${id}" style="width:100px;height:auto;border:1px solid #ccc;border-radius:4px;">
                `);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr) {
                alert("Error: " + (xhr.responseJSON?.message || "Something went wrong"));
            }
        });
    });




    $(document).ready(function() {
        // Approve/Reject document status with optional screenshot upload
        $(document).on('click', '.update-status', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');
            var fileInput = $('tr#row_' + id + ' .screenshot-input')[0];

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('document_id', id);
            formData.append('status', status);

            // Append screenshot if selected
            if (fileInput && fileInput.files.length > 0) {
                formData.append('screenshot', fileInput.files[0]);
            }

            if (confirm("Are you sure to mark this document as " + status + "?")) {
                $.ajax({
                    url: "{{ route('documents.update-status') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // Reload to show updated screenshot link if uploaded
                            window.location.reload();
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

        $('#documents_table').DataTable({
            lengthMenu: [10, 25, 100, 500],
            pageLength: 25
        });
    });
</script>
@endsection