<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: bottom;
        }

        .table_border {
            border: 1px solid black;
        }

        .table_border tr {
            border: 1px solid black;
        }

        .table_border tr td,
        {
        border: 1px solid black;
        }

        .table_border_tr {
            border: 1px solid black;
        }

        .table_border_tr td {
            border: 1px solid black;
        }

        .border_bottom {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>

    <table class="header-table" cellpadding="2">
        <tr>
            <td width="58%">
                <img src="{{ public_path('images/logo.jpg') }}" width="80" alt="Logo" class="logo">
            </td>
            <td width="42%" style="font-size: 8px;" class="company-address">
                <table>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td width="12%"> <!-- Second image updated to location_icon.png -->
                            <img src="{{ public_path('images/home_icon.jpg') }}" width="12" alt="Location Icon"
                                style="vertical-align: middle;">
                        </td>
                        <td width="85%">Head Office: Plot No. 57, 5th Cross Road</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Girinagar, Kadavantra, Kochi - PIN - 682 020</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td> <img src="{{ public_path('images/phone_icon.jpg') }}" width="11" alt="Location Icon"
                                style="vertical-align: middle;"></td>
                        <td>Phone: 0484 4025606</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
    </table>

    <table cellpadding="4">
        <tr>
            <td
                style="font-size: 13px; font-weight: bold; text-align: center; background-color: #015a85; color: white;">
                RECEIPT VOUCHER
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
    </table>

    <table style="font-size: 10pt">
        <tr>
            <td>Invoice Number : {{ $invoiceData['invoice_number'] }}</td>
            <td style="text-align: right">Date : {{ $invoiceData['invoice_date'] }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td width="38%">Track ID : <strong>{{ $invoiceData['student_track_id'] }}</strong></td>
            <td width="62%">
                <table class="table_border" cellpadding="3">
                    <tr style="text-align: center">
                        <td width="26%" style="background-color: {{ $bg_color }};color:white;font-size: 9pt">New
                            Admission
                        </td>
                        <td width="7.33%">
                            @if ($invoiceData['new_payment'] == true)
                                ✓
                            @endif
                        </td>
                        <td width="26%" style="background-color: {{ $bg_color }};color:white;font-size: 9pt">
                            Re-Payment</td>
                        <td width="7.33%">
                            @if ($invoiceData['new_payment'] == false)
                                ✓
                            @endif
                        </td>
                        <td width="26%" style="background-color: {{ $bg_color }};color:white;font-size: 9pt;">
                            Others</td>
                        <td width="7.33%"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
    </table>

    <table style="font-size: 9pt" cellpadding="4">
        <tr>
            <td colspan="2"><strong>RECEIVER DETAILS</strong></td>
        </tr>
        <tr>
            <td width="20%">Candidate Name</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['receiver_name'] }}</td>
        </tr>
        <tr>
            <td width="20%">Institute</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['institution'] }}</td>
        </tr>
        <tr>
            <td width="20%">Course</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['course_name'] }}</td>
        </tr>
        <tr>
            <td width="20%">Branch</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['branch'] }}</td>
        </tr>
        <tr>
            <td width="20%">Mobile Number</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['receiver_mobile'] }}</td>
        </tr>
        <tr>
            <td width="20%">Email</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['receiver_email'] }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"><strong>INVOICE DETAILS</strong></td>
        </tr>

        <tr>
            <td width="20%">Payed Amount</td>
            <td width="80%" class="border_bottom">{{ number_format($invoiceData['course_fee_paid_now'], 2) }}</td>
        </tr>
        <tr>
            <td width="20%">Discount Amount</td>
            <td width="80%" class="border_bottom">
                @if ($invoiceData['course_fee_discount_paid_now'] != 0)
                    {{ number_format($invoiceData['course_fee_discount_paid_now'], 2) }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td width="20%">Amount In Words</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['amount_in_words'] }}</td>
        </tr>
        <tr>
            <td width="20%">Payment Method</td>
            <td width="80%" class="border_bottom">{{ $invoiceData['payment_method'] }}
                @if ($invoiceData['transaction_ref'] !== null && $invoiceData['transaction_ref'] != '')
                    (Ref - {{ $invoiceData['transaction_ref'] }})
                @endif
            </td>
        </tr>
        @if ($invoiceData['bank_name'] != '')
            <tr>
                <td width="20%">Bank Name</td>
                <td width="80%" class="border_bottom">{{ $invoiceData['bank_name'] }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="2"></td>
        </tr>
    </table>

    <table class="table_border" cellpadding="4">
        <tr style="font-size: 10pt;background-color: {{ $bg_color }};text-align:center;color:white;">
            <td colspan="8"><strong>COURSE PAYMENTS</strong></td>
        </tr>
        <tr style="font-size: 8pt">
            <td width="12%" style="font-weight: bold;">Course Fee</td>
            <td width="16%" style="text-align: right;">{{ number_format($invoiceData['course_fee'], 2) }}</td>
            {{-- 26 --}}
            <td width="6%" style="font-weight: bold;">Paid</td>
            <td width="16%" style="text-align: right;">
                {{ number_format($invoiceData['course_fee_paid'], 2) }}</td>
            {{-- 49 --}}
            <td width="9%" style="font-weight: bold;">Balance</td>
            <td width="16%" style="text-align: right;">{{ number_format($invoiceData['course_fee_balance'], 2) }}
            </td>
            {{-- 73 --}}
            <td width="9%" style="font-weight: bold;">Discount</td>
            <td width="16%" style="text-align: right;">{{ number_format($invoiceData['course_fee_discount'], 2) }}
            </td>
        </tr>
    </table>

    <table style="font-size: 10pt" cellpadding="4">
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr class="table_border_tr">
            <td width="16%" style="background-color: {{ $bg_color }};color:white; ">Payees Name
            </td>
            <td width="35%" style="font-size: 7pt">{{ $invoiceData['created_by'] }}</td>
            <td width="14%" style="background-color: {{ $bg_color }};color:white; ">Verified By</td>
            <td width="35%" style="font-size: 7pt"> {{ $invoiceData['verified_by'] }}</td>
        </tr>
    </table>


    <table style="font-size: 8pt;text-align:center" cellpadding="4">
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr style="background-color: {{ $bg_color }};color:white; ">
            <td>HEAD OFFICE - KOCHI, KERALA, INDIA</td>
        </tr>
        <tr style="background-color: {{ $bg_color }};color:white; ">
            <td>E-mail : <a href="mailto:info@focuzacademy.com"
                    style="color: white;text-decoration:none;">info@focuzacademy.com</a> | <a
                    href="www.focuzacademy.com" style="color: white;text-decoration:none;">www.focuzacademy.com</a> |
                facebook.com/focuzacademy</td>
        </tr>
    </table>

    <table style="font-size: 7pt;">

        <tr>
            <td>
                <p><strong>NOTES :</strong></p>
                <p><strong>No Refunds:</strong> We kindly inform you that refunds are not available for this purchase
                </p>
                <p><strong>Keep Your Receipt:</strong> Please keep this receipt for any future reference or questions
                </p>
            </td>
        </tr>
    </table>

    <!-- Add more content for the invoice as needed -->
</body>

</html>
