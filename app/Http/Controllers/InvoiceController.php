<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCPDF;

class InvoiceController extends Controller
{
    //

    public function generateInvoice($pdfDirectory, $invoiceData, $name_append = '')
    {
        $userId = Auth::id();
        $bg_color = "#015a85";
        require_once base_path('vendor/tcpdf/tcpdf.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Company');
        $pdf->SetTitle('Payment Voucher');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('TCPDF, PDF, invoice, voucher');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('dejavusans', '', 12);

        // Add a page
        $pdf->AddPage('P', 'A4');
        $imagePath = public_path('images/logo-gray.jpg');
        $pdf->SetAlpha(0.1);
        $pdf->Image($imagePath, 55, 80, 100, 90, '', '', '', false, 300);
        $pdf->SetAlpha(1);




        $html_data = view('payments.pdfs.invoice_data', [
            'bg_color' => $bg_color,
            'invoiceData' => $invoiceData
        ])->render();
        $pdf->writeHTML($html_data, true, false, false, false, '');
        $file_name = strtolower(str_replace("/", "_", str_replace(" ", "", $invoiceData['student_track_id'])));
        $fileNamePrefix = "invoice_{$userId}"; // the prefix to match files
        if (File::exists($pdfDirectory)) {
            $files = File::allFiles($pdfDirectory);

            foreach ($files as $file) {

                if (str_starts_with($file->getFilename(), $fileNamePrefix)) {
                    File::delete($file);
                }
            }
        }
        $file_name = "/{$fileNamePrefix}_{$file_name}.pdf";
        $pdf_path = $pdfDirectory . $file_name;
        $pdf->Output($pdf_path, 'F');
        return asset("storage/invoices" . $file_name);;
    }

   
}
