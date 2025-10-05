<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Document Upload</h3>
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
                <div class="alert alert-sm alert-info d-none" id="alert_messge_doc"></div>
            </div>
        </div>

        <div class="row">
            <div class="col table-responsive">
                <table class="table table-sm table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Category</th>
                            <th>Path</th>
                            <th>Offer Letter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentsDataAr as $key => $documentsData)
                            @php
                                $payment = $documentsData->payment ?? null;
                                $iconClass = 'text-secondary';
                                $cursorStyle = 'not-allowed';
                                $title = 'No Payment';
                                $showIcon = false;

                                if ($payment && $payment->status === 'active') {
                                    $iconClass = 'text-primary';
                                    $cursorStyle = 'pointer';
                                    $title = 'Download Offer Letter';
                                    $showIcon = true;
                                }
                            @endphp

                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $documentsData->doc_category->category_name }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $documentsData->document_path) }}"
                                        target="_blank">View file</a>
                                </td>
                                {{-- <td>
                                    @php
                                        $offerPath = $payment->offer_letter_path ?? null;
                                        $fileExists =
                                            $offerPath &&
                                            file_exists(storage_path('app/public/offer_letters/' . $offerPath));
                                    @endphp

                                    @if ($payment->status === 'active')
                                        @if ($fileExists)
                                            <a href="{{ route('offer_letter_download', ['filename' => $offerPath]) }}"
                                                target="_blank">
                                                <i class="fa fa-file-pdf text-success"></i> Download Offer Letter
                                            </a>
                                        @else
                                            <a href="javascript:void(0);" class="download_offerletter"
                                                data-id="{{ $payment->id }}">
                                                <i class="fa fa-file-pdf text-primary"></i> Generate Offer Letter
                                            </a>
                                        @endif
                                    @else
                                        <i class="fa fa-file-pdf text-muted" title="Payment not approved"></i>
                                    @endif
                                </td> --}}


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


</script>
