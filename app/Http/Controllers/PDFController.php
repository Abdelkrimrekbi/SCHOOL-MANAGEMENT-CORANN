<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Configuration;
use App\Models\Income;
use Illuminate\Support\Facades\Log;
// use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
// use TCPDF;
use Illuminate\Support\Facades\View;
class PDFController extends Controller
{
    // public function generatePDF($id,$ids)
    // { 
    //   //  Log::debug('path', [$id]);
    //    // Log::debug('path', [$ids]);
      
    //     $configirations = Configuration::orderBy('created_at', 'desc')->get();
    //     $items = Student::where('id',$ids)->get()->first(); 
    //     $payments = Payment::where('id',$id)->get()->first();
    //    // $groups = group::where("id", $items->class_id)->get()->first();
    //     $levels = Level::where("id", $items->level_id)->get()->first();

    //     $path = public_path("storage/" . $configirations->first()->logo);
    //    // 
    //    if (file_exists($path)) {
    //     $type = pathinfo($path, PATHINFO_EXTENSION);
    //     $data = file_get_contents($path);
    
    //     if ($data) {
    //         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    //         $pdf = Pdf::loadView('pdf_template', ['payments' => $payments,'items'=>$items,'configirations'=>$configirations,
    //         'image' => $base64,"levels"=>$levels]);
    
    //         return $pdf->download('document.pdf');
    //     }else{
    //         $base64 =null;
    //         $pdf = PDF::loadView('pdf_template', [
    //             'payments' => $payments,
    //             'items' => $items,
    //             'configirations' => $configirations,
    //             'image' => $base64,
    //             'levels' => $levels
    //         ]);
            
    //         // Set the options to embed the font
    //         $options = [
    //             'dpi' => 300, // Set the DPI (dots per inch) if needed
    //             'enable-javascript' => true, // Enable JavaScript if needed
    //             'no-outline' => true, // Disable the outline if needed
    //             'enable-local-file-access' => true, 
    //             'margin-top' => 10, 
    //             'margin-right' => 10,
    //             'margin-bottom' => 10,
    //             'margin-left' => 10,
    //             'encoding' => 'utf-8', 
    //             'disable-smart-shrinking' => true, 
    //             'print-media-type' => true, 
    //             'embed-font' => true, 
    //             'font-family' => 'Droid-Naskh-Regular', 
    //         ];
    //         // Pass options to the PDF generator
    //         $pdf->setOption($options);
    //         $pdf->setRTL(true);
    //        // $pdf->Window().print('document.pdf');
              
    //         return $pdf->download('document.pdf');
    //        // return $pdf->stream('document.pdf');
           
           
    //     }
      
        
    //  }    
    
    // }
    public function generatePDF_inscription($id){

        $configirations = Configuration::orderBy('created_at', 'desc')->get();
        $student = Student::where('id', $id)->first();
        $levels = Level::where("id", $student->level_id)->first();
        $path = public_path("storage/" . $configirations->first()->logo);

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);

            if ($data) {
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
               $data = [
                'student' => $student,
                'configirations' => $configirations,
                'image' => $base64,
                'levels' => $levels];
                $mpdf = new Mpdf([
                    'mode' => 'utf-8', 
                    'default_font' => 'arial', 
                    'autoScriptToLang' => true, 
                    'autoLangToFont' => true, 
                    'direction' => 'rtl',
                    // 'format' => [80, 297]
                ]);

                $html = View::make('pdf_inscreption',$data
                )->render();

                $mpdf->WriteHTML($html);

                $mpdf->Output('output.pdf', 'D');
              
            } else {
                return response()->json(['error' => 'Unable to retrieve data.'], 500);
            }
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function generatePDF_Serie($array, $id)
    {
        $nbrArray = array_map('intval', explode(',', $array));
        // Log::debug('array ids ::', [$array]);
        // Log::debug('array ids ::', [$nbrArray]);

        $payments = [];

        foreach ($nbrArray as $item) {
            $payment = Payment::whereIn('id', [$item])->first();
            if ($payment) {
                $payments[] = $payment;
            }
        }

        $configirations = Configuration::orderBy('created_at', 'desc')->get();
        $student = Student::where('id', $id)->first();
        $levels = Level::where("id", $student->level_id)->first();
        $path = public_path("storage/" . $configirations->first()->logo);

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);

            if ($data) {
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
               $data = [
                'payments' => $payments,
                'student' => $student,
                'configirations' => $configirations,
                'image' => $base64,
                'levels' => $levels];
                $mpdf = new Mpdf([
                    'mode' => 'utf-8', 
                    'default_font' => 'arial', 
                    'autoScriptToLang' => true, 
                    'autoLangToFont' => true, 
                    'direction' => 'rtl',
                    'format' => [80, 297]
                ]);

                $html = View::make('pdf_template',$data
                )->render();

                $mpdf->WriteHTML($html);

                $mpdf->Output('output.pdf', 'D');
              
            } else {
                return response()->json(['error' => 'Unable to retrieve data.'], 500);
            }
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
    public function generatePDF_Revenu( $id){

        $configirations = Configuration::orderBy('created_at', 'desc')->get();
        $Revenu = Income::where('id', $id)->first();
        log::debug("print",[ $Revenu ]);
        $path = public_path("storage/" . $configirations->first()->logo);

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);

            if ($data) {
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
               $data = [
                'Revenu' => $Revenu,
                'configirations' => $configirations,
                'image' => $base64];
                $mpdf = new Mpdf([
                    'mode' => 'utf-8', 
                    'default_font' => 'arial', 
                    'autoScriptToLang' => true, 
                    'autoLangToFont' => true, 
                    'direction' => 'rtl',
                    'format' => [80, 80]
                ]);

                $html = View::make('pdf_revenu',$data
                )->render();

                $mpdf->WriteHTML($html);

                $mpdf->Output('output.pdf', 'D');
              
            } else {
                return response()->json(['error' => 'Unable to retrieve data.'], 500);
            }
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }

    }
}