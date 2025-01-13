<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generatePDF($studentID, $classID)
    {
        try {
            // Kiểm tra xem chứng chỉ đã được cấp cho sinh viên trong lớp này chưa
            $existingCertificate = Certificate::where('student_id', $studentID)
                ->where('class_id', $classID)
                ->first();

            if ($existingCertificate) {
                return redirect()->back()->with('error', 'Chứng chỉ đã được cấp cho học sinh này trong lớp này.');
            }

            // Tạo chứng chỉ mới
            $certificate = Certificate::create([
                'student_id' => $studentID,
                'class_id' => $classID,
            ]);

            // Lấy thông tin sinh viên và lớp
            $student = $certificate->student;
            $class = $certificate->class;

            // Dữ liệu cho chứng chỉ
            $data = [
                'name_cty' => 'Trung tâm gia sư NEGA',
                'description_cty' => 'Đã tham gia và hoàn thành khóa học',
                'issue_date' => Carbon::parse($certificate->created_at)->format('d/m/Y'),
                'name_student' => $student->name,
                'name_class' => $class->name,
            ];

            // Render view thành HTML
            $html = view('certificate.pdf', $data)->render();

            // Tạo file PDF
            $pdf = Pdf::loadHTML($html);
            $pdf->set_option('font', 'Arial');
            $pdf->setPaper([0, 0, 850, 450], 'portrait');
            
            // Lưu file PDF
            $path = 'img/pdfs/my_pdf_file_' . uniqid() . '.pdf';
            $filePath = public_path($path);
            $certificate->link_certificate = $path;
            $certificate->save();
            $pdf->save($filePath);
            return $pdf->download('certificate.pdf');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi trong quá trình tạo chứng chỉ.');
        }
    }

}
