
   <?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class OfferLetterController extends Controller
{
   
     

   <?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCPDF;

class OfferLetterController extends Controller
{
    public function generateOfferLetter($pdfDirectory, $offerData, $name_append = '')
    {
        $userId = Auth::id();
        $bg_color = "#015a85";

        require_once base_path('vendor/tcpdf/tcpdf.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Institution');
        $pdf->SetTitle('Offer Letter');
        $pdf->SetSubject('Offer Letter');
        $pdf->SetKeywords('TCPDF, PDF, Offer Letter');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('dejavusans', '', 12);

        // Add a page
        $pdf->AddPage('P', 'A4');

        // Add watermark/logo
        $imagePath = public_path('images/logo-gray.jpg'); // Optional watermark/logo
        $pdf->SetAlpha(0.1);
        $pdf->Image($imagePath, 55, 80, 100, 90, '', '', '', false, 300);
        $pdf->SetAlpha(1);

        // Load blade view for offer letter content
        $html_data = view('payments.pdfs.offer_letter', [
            'bg_color' => $bg_color,
            'offerData' => $offerData
        ])->render();

        $pdf->writeHTML($html_data, true, false, false, false, '');

        // Create file name
        $file_name = strtolower(str_replace(" ", "_", $offerData['student_track_id']));
        $fileNamePrefix = "offer_letter_{$userId}{$name_append}";

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

        // Save PDF
        $pdf->Output($pdf_path, 'F');

        // Return full URL
        return asset("storage/offer_letters" . $file_name);
    }

    // Optional: Download endpoint
    public function download($filename)
    {
        $file = storage_path('app/public/offer_letters/' . $filename);

        if (!File::exists($file)) {
            abort(404);
        }

        return response()->download($file, $filename);
    }
}


public function previewOfferLetter()
{
   
    // Return the Blade view for preview
    return view('payments.pdfs.offer_letter', $offerData);
}
