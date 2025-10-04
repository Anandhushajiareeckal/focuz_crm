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
                            <td>Offer Letters</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursePaymentsAr as $coursePayment)
                            @php
                                $course_id = $coursePayment->course_id;

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

                                $paymentsDataAr = App\Models\Payments::with([
                                    'payment_methods:id,method_name',
                                    'discounts:id,promocode',
                                    'card_types:id,type_name',
                                    'banks:id,bank_name',
                                ])
                                ->where('student_id', $student_id)
                                ->where('course_id', $course_id)
                                ->get();

                                $course_fee = App\Models\CourseSchedules::where('id', $course_schedule_id)->value('course_fee');
                            @endphp

                            @if ($paymentsDataAr->count() > 0)
                                @php
                                    $balance_amount = $course_fee;
                                    $total_paid_amount = 0;
                                @endphp

                                @foreach ($paymentsDataAr as $key => $paymentsData)
                                    @php
                                        $amount = $paymentsData->amount ?? 0;
                                        $discount_amount = $paymentsData->discount_amount ?? 0;
                                        $payed_amount = $amount + $discount_amount;

                                        // Update totals even for pending payments
                                        if (in_array($paymentsData->status, ['active', 'pending'])) {
                                            $total_paid_amount += $payed_amount;
                                            $balance_amount = max(0, $course_fee - $total_paid_amount);
                                        }

                                        $table_class = ($paymentsData->status == 'active') ? 'table-primary' : 'table-warning';
                                    @endphp

                                    <tr class="{{ $table_class }}">
                                        <td style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <span style="display:inline-block; max-width:inherit; overflow:hidden; text-overflow:ellipsis; vertical-align:bottom;">
                                                {{ $coursePayment->courses->universities->university_code ?? '' }} {{ $course_name }}
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
                                            @if (in_array($paymentsData->status, ['pending','reversed']))
                                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                            @elseif ($paymentsData->verified_by)
                                                <i class="fa fa-file-pdf text-danger download_invoice" data-id="{{ $paymentsData->id }}" style="font-size:12pt;cursor:pointer"></i>
                                            @else
                                                CONTACT IT
                                            @endif
                                        </td>

                                        {{-- Offer Letter --}}
                                        <!--<td>-->
                                        <!--    @if ($paymentsData->status == 'active' && $paymentsData->verified_by)-->
                                                <!-- Active and verified: Green, clickable -->
                                        <!--        <i class="fa fa-envelope text-success download_offer_letter" -->
                                        <!--           data-id="{{ $paymentsData->id }}" -->
                                        <!--           style="font-size:12pt;cursor:pointer"></i>-->
                                        <!--    @elseif ($paymentsData->status == 'pending')-->
                                                <!-- Pending: Gray, not clickable -->
                                        <!--        <i class="fa fa-envelope text-secondary" -->
                                        <!--           style="font-size:12pt;cursor:not-allowed" -->
                                        <!--           title="Offer letter will be available after payment verification"></i>-->
                                        <!--    @else-->
                                        <!--        CONTACT IT-->
                                        <!--    @endif-->
                                        <!--</td>-->
                                            <td>
                                            @if (in_array($paymentsData->status, ['pending','reversed']))
                                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                            @elseif ($paymentsData->verified_by)
                                                <i class="fa fa-envelope text-success download_offer_letter" data-id="{{ $paymentsData->id }}" style="font-size:12pt;cursor:pointer"></i>
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

    $(document).on("click", ".download_offer_letter", function (e) {
        e.preventDefault();
        let paymentId = $(this).data("id");
        let link = document.createElement('a');
        link.href = "/offer_letter/download/" + paymentId;
        link.download = "offer_letter_" + paymentId + ".pdf";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
