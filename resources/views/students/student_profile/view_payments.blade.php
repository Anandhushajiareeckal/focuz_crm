<div class="row">
    <div class="col">
        <div class="alert alert-info">
            Feature can be added: Invoices can be shared digitally, reducing your workflow.
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Payments</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <td>Course</td>
                            <td>Payment Method</td>
                            <td>Bank</td>
                            <td>Card Type</td>
                            <td>Trans Date</td>
                            <td>Trans Ref</td>
                            <td>Course Fee</td>
                            <td>Amount</td>
                            <td>Discount Amount</td>
                            <td>Total Amount</td>
                            <td>Total Payed Amount</td>
                            <td>Balance Amount</td>
                            <td>Status</td>
                            <td>Invoice</td>
                            <td>Offer Letter</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursePaymentsAr as $coursePayment)
                            @php
                                $course_id = $coursePayment->course_id;

                                // Get course name
                                $course_data = DB::table('courses')
                                    ->join('streams', 'courses.stream_id', '=', 'streams.id')
                                    ->where('courses.id', $course_id)
                                    ->select('courses.specialization', 'streams.code')
                                    ->first();

                                $course_name = $course_data->code;
                                if ($course_data->specialization != '') {
                                    $course_name .= ' ' . $course_data->specialization;
                                }

                                $course_schedule_id = $coursePayment->course_schedule_id;

                                // Get payments
                                $paymentsDataAr = App\Models\Payments::with([
                                    'payment_methods:id,method_name',
                                    'discounts:id,promocode',
                                    'card_types:id,type_name',
                                    'banks:id,bank_name',
                                ])
                                    ->where('student_id', $student_id)
                                    ->where('course_id', $course_id)
                                    ->get();

                                // Course fee
                                $course_fee = App\Models\CourseSchedules::where('id', $course_schedule_id)->value('course_fee');

                                // Calculate totals using only active payments
                                $total_paid_amount = $paymentsDataAr->where('status', 'active')->sum(function($p) {
                                    return ($p->amount ?? 0) + ($p->discount_amount ?? 0);
                                });

                                $balance_amount = max(0, $course_fee - $total_paid_amount);
                            @endphp

                            @if ($paymentsDataAr->count() > 0)
                                @foreach ($paymentsDataAr as $paymentsData)
                                    @php
                                        $amount = $paymentsData->amount ?? 0;
                                        $discount_amount = $paymentsData->discount_amount ?? 0;
                                        $payed_amount = $amount + $discount_amount;

                                        // Set table row class based on status
                                        $table_class = match($paymentsData->status) {
                                            'active' => 'table-primary',
                                            'pending' => 'table-warning',
                                            'reversed' => 'table-danger',
                                            default => '',
                                        };
                                    @endphp

                                    <tr class="{{ $table_class }}">
                                        <td style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <span style="display:inline-block; max-width:inherit; overflow:hidden; text-overflow:ellipsis; vertical-align:bottom;">
                                                {{ $coursePayment->courses->universities->university_code ?? '' }}
                                                {{ $course_name }}
                                            </span>
                                        </td>
                                        <td>{{ $paymentsData->payment_methods->method_name ?? '-' }}</td>
                                        <td>{{ $paymentsData->banks->bank_name ?? '-' }}</td>
                                        <td>{{ $paymentsData->card_types->type_name ?? '-' }}</td>
                                        <td>{{ $paymentsData->payment_date ? date('d-m-Y', strtotime($paymentsData->payment_date)) : '-' }}</td>
                                        <td>{{ $paymentsData->transaction_ref ?? '-' }}</td>
                                        <td>{{ number_format($course_fee, 2) }}</td>
                                        <td class="text-right font-weight-bold text-primary">{{ number_format($amount, 2) }}</td>
                                        <td class="text-right font-weight-bold text-primary">{{ number_format($discount_amount, 2) }}</td>
                                        <td class="text-right">{{ number_format($payed_amount, 2) }}</td>
                                        <td>{{ number_format($total_paid_amount, 2) }}</td>
                                        <td>
                                            @if ($balance_amount > 0)
                                                <a href="{{ route('add_students', [4, $student_id, $course_id, $course_schedule_id]) }}" target="_blank">
                                                    {{ number_format($balance_amount, 2) }}
                                                </a>
                                            @else
                                                {{ number_format($balance_amount, 2) }}
                                            @endif
                                        </td>
                                        <td>{{ ucwords($paymentsData->status) }}</td>

                                        {{-- Invoice --}}
                                        <td>
                                            @if (in_array($paymentsData->status, ['pending', 'reversed']))
                                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                            @elseif ($paymentsData->verified_by)
                                                <i class="fa fa-file-pdf text-danger download_invoice"
                                                   data-id="{{ $paymentsData->id }}"
                                                   style="font-size:12pt;cursor:pointer"></i>
                                            @else
                                                CONTACT IT
                                            @endif
                                        </td>

                                        {{-- Offer Letter --}}
                                        <td>
                                            @if (in_array($paymentsData->status, ['pending', 'reversed']))
                                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                            @elseif ($paymentsData->verified_by)
                                                <i class="fa fa-envelope text-success download_offer_letter"
                                                   data-id="{{ $paymentsData->student_id }}"
                                                   style="font-size:12pt;cursor:pointer"></i>
                                            @else
                                                CONTACT IT
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    td:hover {
        max-width: 800px !important;
        white-space: normal !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('.download_invoice').click(function(e) {
            e.preventDefault();
            var checkedIds = [$(this).attr('data-id')];
            preloader.load();
            $.ajax({
                type: "POST",
                url: "{{ route('invoice_print') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "checkedIdsJson": checkedIds
                },
                success: function(response) {
                    preloader.stop();
                    $('#download').attr('href', response);
                    $('#download')[0].click();
                }
            });
        });

        $(document).on("click", ".download_offer_letter", function(e) {
            e.preventDefault();
            let paymentId = $(this).data("id");
            window.location.href = "/offer_letter/download/" + paymentId;
        });
    });
</script>
