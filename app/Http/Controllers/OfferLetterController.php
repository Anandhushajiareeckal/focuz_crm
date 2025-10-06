<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Students;

class OfferLetterController extends Controller
{
    public function download($id)
    {
        $student = Students::find($id);
        if (!$student) {
            abort(404, 'Student not found');
        }
        $pdf = Pdf::setOption(['isRemoteEnabled' => true])
          ->loadView('payments.pdfs.offer_letter',compact('student'));
        return $pdf->download('offerletter_'.$id.'.pdf');
    }


    // public function show()
    // {
    //     return view('payments.pdfs.offer_letter');
    // }
}


