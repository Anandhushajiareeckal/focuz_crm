F<div class="card card-default ">
    {{-- collapsed-card --}}
    <div class="card-header">
        <h3 class="card-title"> Document Upload</h3>
        <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="alert alert-sm alert-info d-none    " id="alert_messge_doc">

                </div>
            </div>
        </div>
        <form id="upload_files_form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <input type="hidden" class="student_id" name="student_id" value="{{ $student_id_url }}">
                <table class="table table-sm table-striped">
                    <tr>
                        <th>SL No</th>
                        <th>Category</th>
                        <th>File</th>
                        <th>Path</th>
                    </tr>
                    @foreach ($documentCategoriesAr as $key => $documentCategory)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <input type="hidden" value="{{ $documentCategory->id }}" name="document_category[]"
                                class="document_category">
                            <td>{{ $documentCategory->category_name }}</td>
                            <td> <input type="file" id="file_{{ $documentCategory->id }}"
                                    name="file_{{ $documentCategory->id }}" class="files form-control form-control-sm">
                            </td>
                            <td>
                                @php
                                    $documents = App\Models\Documents::select('document_path')
                                        ->where('student_id', $student_id_url)
                                        ->where('doc_category_id', $documentCategory->id)
                                        ->first();
                                    if ($documents && $documents->document_path) {
                                        $document_path = $documents->document_path;
                                    } else {
                                        $document_path = '';
                                    }
                                @endphp
                                @if ($document_path != '')
                                    <a href="{{ asset('storage/' . $document_path) }}" target="_blank">View file</a>
                                @else
                                    <a href="" id="file_path_{{ $documentCategory->id }}" target="_blank"></a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="row mt-2">
                <div class="col">
                    <div class="alert alert-info alert-sm">
                        Note : In the new version, simply drag and drop files to upload. Name the files correctly and
                        upload them, if you want to.
                    </div>
                </div>
            </div>
        </form>
    </div>


</div>

<script>
    $(document).ready(function() {
        $('#upload_files_form').on('submit', function(e) {
            e.preventDefault();
            preloader.load();
            var formData = new FormData(this);
            //console.log(formData)
            $.ajax({
                url: '{{route('upload_student_docs')}}', // Laravel route for file upload
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    preloader.stop();
                    // console.log(response);
                    showAlert(response.message);
                    $('#upload_files_form .form-control').removeClass('error-outline');
                    $('#alert_messge_doc').addClass('d-none');
                    $('#alert_messge_doc').html('');
                    // console.log(response.file_paths)
                    $.each(response.file_paths, function(key, value) {
                        // console.log(key);
                        $('#file_path_' + key).attr('href', value);
                        $('#file_path_' + key).html('View file');
                        // console.log(value);
                    });
                    updateProgress(response.prolfile_completed[0], response
                        .prolfile_completed[1], 'info');
                },
                error: function(jqXHR) {
                    preloader.stop();
                    // console.log()
                    var errors = jqXHR.responseJSON.errors;
                    // console.log(errors)
                    $('#upload_files_form .form-control').removeClass('error-outline');
                    $('#alert_messge_doc').html('');
                    $('#alert_messge_doc').addClass('d-none');
                    if (jqXHR.responseJSON.error_type) {
                        $('#alert_messge_doc').addClass('alert-info');
                        $('#alert_messge_doc').removeClass('d-none');
                        $('#alert_messge_doc').append(jqXHR.responseJSON.errors);
                    } else {
                        let message = "";
                        $.each(errors, function(key, value) {
                            $('#alert_messge_doc').addClass('alert-info');
                            $('#alert_messge_doc').removeClass('d-none');
                            $('#' + key).addClass('error-outline');
                            message = value[0] + " ";
                        });
                        $('#alert_messge_doc').append(message);
                    }


                }
            });
        });
    });

    function save_docs_form() {
        $('#upload_files_form').submit();
    }
</script>
