<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CourseScheduleApiController extends Controller
{
    public function downloadCourseSchedule(Request $request)
    {
        try {
            $validated = $request->validate([
                'fname' => 'required|string',
                'lname' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'phoneCode' => 'nullable|string',
                'company' => 'nullable|string',
                'jobtitle' => 'nullable|string',
                'pagetype' => 'nullable|string',
                'searchdata' => 'nullable|string',
                'downloadtype' => 'nullable|string',
            ]);

            $name = trim(($validated['fname'] ?? '') . ' ' . ($validated['lname'] ?? ''));
            $email = $validated['email'];
            $phone = $validated['phone'];
            $company = $validated['company'] ?? '';
            $jobTitle = $validated['jobtitle'] ?? '';
            $pagetype = $validated['pagetype'] ?? '';

            // Log to user_log
            $dtArr = [
                "log_type" => "crssch",
                "name" => $name,
                "email" => $email,
                "phone_no" => $phone,
                "company_name" => $company,
                "job_title" => $jobTitle,
                "ip" => $request->ip(),
                "country" => session('country_name', ''),
                "course_url" => url()->current(),
                "coursename" => null,
                "categoryname" => null,
                "coursevenue" => null,
                "quantity" => null,
                "coursestartdt" => null,
                "currency" => null,
                "price" => null,
                "status" => null,
                "created_dt" => now()
            ];

            DB::table('user_log')->insert($dtArr);

            // Send admin email
            $this->sendAdminEmail($validated);

            // ---- listpage flow: generate a secure download link ----
            if ($pagetype === 'listpage') {
                $downtype = $validated['downloadtype'] ?? '';
                $searchdata = $validated['searchdata'] ?? '';

                // Parse searchdata query string into JSON
                parse_str($searchdata, $parsedSearch);
                $searchdataJson = json_encode($parsedSearch);

                $unqid = md5(uniqid());
                $operators = ['+', '-'];
                $operator = $operators[array_rand($operators)];
                $expireAt = now()->addDays(30)->format('Y-m-d H:i:s');

                if ($operator === '-') {
                    $firstNo = rand(2, 99);
                    $secondNo = rand(1, $firstNo - 1);
                } else {
                    $firstNo = rand(1, 99);
                    $secondNo = rand(1, 99);
                }

                DB::table('schedule_data')->insert([
                    'name' => $name,
                    'email' => $email,
                    'type' => $pagetype,
                    'downtype' => $downtype,
                    'querytxt' => $searchdataJson,
                    'unqid' => $unqid,
                    'first_no' => $firstNo,
                    'second_no' => $secondNo,
                    'operator' => $operator,
                    'status' => 0,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'expire_at' => $expireAt,
                ]);

                $frontendBase = env('FRONTEND_URL', 'https://www.londontfe.com');
                $downloadLink = $frontendBase . '/course-schedule/verify/' . $unqid;

                $downtypeLabel = match ($downtype) {
                    'documentexcel' => 'Excel',
                    'documentpdf' => 'PDF',
                    default => $downtype,
                };

                $expireAtFormatted = date('d-m-Y', strtotime($expireAt));

                $this->autoMailCourseDirectoryDwnld($name, $email, $downtypeLabel, $expireAtFormatted, $downloadLink);

                return response()->json([
                    'success' => true,
                    'message' => 'Success',
                    'listpage' => true,
                ]);
            }

            // Non-listpage: frontend will redirect to /course-schedule
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'listpage' => false,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: Please ensure all required fields (like email) are provided.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Course Schedule Download Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    /**
     * Separate endpoint for Excel/PDF direct button clicks to match legacy code
     */
    public function requestDocumentDownload(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'downloadtype' => 'required|string',
            ]);

            $email = $validated['email'];
            $name = trim($request->input('fname', '') . ' ' . $request->input('lname', ''));
            $downtype = $validated['downloadtype'];
            $pagetype = 'schdlpage';

            $unqid = md5(uniqid());
            $operators = ['+', '-'];
            $operator = $operators[array_rand($operators)];
            $expireAt = now()->addDays(30)->format('Y-m-d H:i:s');

            if ($operator === '-') {
                $firstNo = rand(2, 99);
                $secondNo = rand(1, $firstNo - 1);
            } else {
                $firstNo = rand(1, 99);
                $secondNo = rand(1, 99);
            }

            DB::table('schedule_data')->insert([
                'name' => $name,
                'email' => $email,
                'type' => $pagetype,
                'downtype' => $downtype,
                'querytxt' => json_encode([]),
                'unqid' => $unqid,
                'first_no' => $firstNo,
                'second_no' => $secondNo,
                'operator' => $operator,
                'status' => 0,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'expire_at' => $expireAt,
            ]);

            $frontendBase = env('FRONTEND_URL', 'https://www.londontfe.com');
            $downloadLink = $frontendBase . '/course-schedule/verify/' . $unqid;

            $downtypeLabel = match ($downtype) {
                'documentexcel' => 'Excel',
                'documentpdf' => 'PDF',
                default => $downtype,
            };

            $expireAtFormatted = date('d-m-Y', strtotime($expireAt));

            $this->autoMailCourseDirectoryDwnld($name, $email, $downtypeLabel, $expireAtFormatted, $downloadLink);

            return response()->json([
                'success' => true,
                'message' => 'Success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired or invalid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Course Schedule Document Download Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    /**
     * Verify a schedule download request via unique ID and serve the file.
     */
    public function verifyScheduleRequest(Request $request, $unqid)
    {
        try {
            $record = DB::table('schedule_data')->where('unqid', $unqid)->first();

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired link.'], 404);
            }

            if ($record->status == 1) {
                return response()->json(['success' => false, 'message' => 'This link has already been used.'], 410);
            }

            if (now()->gt($record->expire_at)) {
                return response()->json(['success' => false, 'message' => 'This link has expired.'], 410);
            }

            return response()->json([
                'success' => true,
                'downtype' => $record->downtype ?? 'documentpdf',
                'name' => $record->name,
                'email' => $record->email,
                'first_no' => $record->first_no,
                'second_no' => $record->second_no,
                'operator' => $record->operator,
                'unqid' => $record->unqid,
            ]);

        } catch (\Exception $e) {
            Log::error('Verify Schedule Request Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    public function verifySchedulemail(Request $request)
    {
        try {
            $validated = $request->validate([
                'unqid' => 'required|string',
                'email' => 'required|email',
                'captcha' => 'required|numeric',
            ]);

            $unqid = $validated['unqid'];
            $email = $validated['email'];
            $answer = $validated['captcha'];

            $row = DB::table('schedule_data')
                ->where('unqid', $unqid)
                ->where('email', $email)
                ->where('status', 0)
                ->first();

            if (!$row) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wrong email address or link expired'
                ]);
            }

            if (now()->gt($row->expire_at)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This link has expired.'
                ]);
            }

            $correct = ($row->operator === '+')
                ? ($row->first_no + $row->second_no)
                : ($row->first_no - $row->second_no);

            if ((int) $answer !== (int) $correct) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wrong captcha'
                ]);
            }

            $operators = ['+', '-'];
            $operator = $operators[array_rand($operators)];

            if ($operator === '-') {
                $first_no = rand(2, 99);
                $second_no = rand(1, $first_no - 1);
            } else {
                $first_no = rand(1, 99);
                $second_no = rand(1, 99);
            }

            DB::table('schedule_data')->where('id', $row->id)->update([
                'status' => 1,
                'first_no' => $first_no,
                'second_no' => $second_no,
                'operator' => $operator
            ]);

            $backendBase = env('APP_URL', 'http://localhost:8000');
            $downloadUrl = $backendBase . '/api/v1/course-schedule/download/' . $unqid;

            return response()->json([
                'status' => 'success',
                'download_url' => $downloadUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Verify Schedule Mail Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'An error occurred.']);
        }
    }

    /**
     * Get course schedule data for display on the /course-schedule page.
     */
    public function getCourseScheduleData(Request $request)
    {
        try {
            $courses = $this->fetchCoursesData();

            $result = [];
            foreach ($courses as $row) {
                if (empty($row->start_date))
                    continue;

                $gbpPrice = $this->calculateGbpPrice($row);

                $cateSlugs = explode('|', $row->course_category_slug ?? '');
                $cateSlug = $cateSlugs[0] ?? '';
                $link = '/course/' . $cateSlug . '/' . $row->seo_name;

                $result[] = [
                    'course_name' => $row->course_name,
                    'venue' => $row->venue,
                    'start_date' => !empty($row->start_date) ? date('d-m-Y', strtotime($row->start_date)) : '',
                    'end_date' => !empty($row->end_date) ? date('d-m-Y', strtotime($row->end_date)) : '',
                    'price' => $gbpPrice > 0 ? '£' . number_format($gbpPrice, 0, '.', ',') : 'On Request',
                    'category' => $row->course_category,
                    'link' => $link,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('Get Course Schedule Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load course schedule.'], 500);
        }
    }

    // -----------------------------------------------------------------------
    // Download File Handler (Excel/PDF)
    // -----------------------------------------------------------------------
    public function downloadFile(Request $request, $unqid)
    {
        try {
            $record = DB::table('schedule_data')->where('unqid', $unqid)->first();
            if (!$record) {
                return response('Invalid or expired link.', 404);
            }

            if ($request->query('format') === 'excel' || $record->downtype === 'documentexcel') {
                return $this->exportExcel($record);
            } else {
                return $this->exportPdf($record);
            }
        } catch (\Exception $e) {
            Log::error('Download File Error: ' . $e->getMessage());
            return response('Failed to generate file.', 500);
        }
    }

    private function exportPdf($record)
    {
        ini_set('pcre.backtrack_limit', '100000000');
        ini_set('pcre.recursion_limit', '100000000');

        $coursesRaw = $this->fetchCoursesData($record);
        $courses = [];
        $frontendBase = rtrim(env('FRONTEND_URL', 'https://www.londontfe.com'), '/');
        // Mpdf explicitly blocks "localhost" URLs for security reasons, so we map it to 127.0.0.1 for local testing to work.
        $frontendBase = str_replace('localhost', '127.0.0.1', $frontendBase);

        foreach ($coursesRaw as $row) {
            if (empty($row->start_date))
                continue;

            $gbpPrice = $this->calculateGbpPrice($row);
            $usdPrice = $row->price_corporate_dollar ?? 0;

            $cateSlugs = explode('|', $row->course_category_slug ?? '');
            $cateSlug = $cateSlugs[0] ?? '';
            $link = $frontendBase . '/course/' . $cateSlug . '/' . $row->seo_name . '/' . $row->date_venue_id;

            $categories = explode('|', $row->course_category ?? '');

            $startTs = strtotime($row->start_date);
            $endTs = !empty($row->end_date) ? strtotime($row->end_date) : null;

            if ($endTs) {
                if (date('m Y', $startTs) === date('m Y', $endTs)) {
                    $dateFormatted = date('d', $startTs) . ' - ' . date('d M Y', $endTs);
                } elseif (date('Y', $startTs) === date('Y', $endTs)) {
                    $dateFormatted = date('d M', $startTs) . ' - ' . date('d M Y', $endTs);
                } else {
                    $dateFormatted = date('d M Y', $startTs) . ' - ' . date('d M Y', $endTs);
                }
            } else {
                $dateFormatted = date('d M Y', $startTs);
            }

            $courses[] = [
                'course_name' => $row->course_name,
                'category' => $categories[0] ?? "",
                'date_formatted' => $dateFormatted,
                'venue' => $row->venue,
                'usd_price' => $usdPrice,
                'price' => $gbpPrice,
                'link' => $link
            ];
        }

        $html = view('pdf.schedule', compact('courses'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 55,
            'margin_bottom' => 20,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);

        // Write the HTML in chunks if it's very large, or just write it all at once
        $mpdf->WriteHTML($html);

        $filename = 'LondonTFE-Course-Schedule-' . date('d-m-Y') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'I'); // Output as string directly into the stream
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function exportExcel($record)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === LOGO (A1:A3) ===
        $sheet->getRowDimension(1)->setRowHeight(35);
        $sheet->getRowDimension(2)->setRowHeight(35);
        $sheet->getRowDimension(3)->setRowHeight(35);
        $sheet->getRowDimension(4)->setRowHeight(25);

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(28);

        $sheet->mergeCells('A1:A3');
        // If logo exists locally, we'd add it here using PhpOffice\PhpSpreadsheet\Worksheet\Drawing

        $sheet->mergeCells('B1:E3');
        $sheet->setCellValue('B1', 'Contact us with WhatsApp');
        $sheet->getCell('B1')->getHyperlink()->setUrl('https://wa.me/442071836657');
        $sheet->getCell('B1')->getHyperlink()->setTooltip('Chat with us on WhatsApp');

        $sheet->getStyle('B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '25D366'], // WhatsApp green
                'underline' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // === COMPANY INFO (F1:G2) ===
        $sheet->mergeCells('F1:G2');
        $info = "London Training for Excellence, United Kingdom\n" .
            "General Inquiries: info@londontfe.com\n" .
            "Registrations: sales@londontfe.com\n" .
            "Call Us: +44 (0) 207 183 5567";

        $sheet->setCellValue('F1', $info);
        $sheet->getStyle('F1')->applyFromArray([
            'font' => ['size' => 14, 'bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
                'shrinkToFit' => true,
            ]
        ]);

        // === WEBSITE LINK (F3:G3) ===
        $sheet->mergeCells('F3:G3');
        $sheet->setCellValue('F3', 'www.londontfe.com');
        $sheet->getCell('F3')->getHyperlink()->setUrl('https://www.londontfe.com');
        $sheet->getStyle('F3')->applyFromArray([
            'font' => [
                'size' => 10,
                'bold' => true,
                'underline' => true,
                'color' => ['rgb' => '0563C1']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // === GOLD HEADER BAR (A4:G4) ===
        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'Filter the columns to find your course to match your training and development requirements');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'B8903C']
            ]
        ]);

        // === Table Header (Row 5) ===
        $headers = ['Name of course', 'Venue', 'Start Date', 'End Date', 'Fee (£)', 'Category'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }

        $sheet->getStyle('A5:F5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B8903C']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $courses = $this->fetchCoursesData($record);
        $excel_array = [];
        $frontendBase = rtrim(env('FRONTEND_URL', 'https://www.londontfe.com'), '/');
        // Excel/PDF parsers often block "localhost" URLs for security reasons, so we map it to 127.0.0.1 for local testing.
        $frontendBase = str_replace('localhost', '127.0.0.1', $frontendBase);

        foreach ($courses as $excel) {
            if (empty($excel->start_date))
                continue;

            $gbpPrice = $this->calculateGbpPrice($excel);
            $cateSlugs = explode('|', $excel->course_category_slug ?? '');
            $cateSlug = $cateSlugs[0] ?? '';
            $link = $frontendBase . "/course/" . $cateSlug . '/' . $excel->seo_name . '/' . $excel->date_venue_id;

            $dtStart = new \DateTime($excel->start_date);
            $dtStart->setTime(0, 0, 0);
            $start_date = (int) Date::dateTimeToExcel($dtStart);

            $end_date = null;
            if (!empty($excel->end_date)) {
                $dtEnd = new \DateTime($excel->end_date);
                $dtEnd->setTime(0, 0, 0);
                $end_date = (int) Date::dateTimeToExcel($dtEnd);
            }

            $categories = explode('|', $excel->course_category ?? '');

            $excel_array[] = [
                $excel->course_name,
                $excel->venue,
                $start_date,
                $end_date,
                $gbpPrice > 0 ? '£' . $gbpPrice : 'On Request',
                $categories[0] ?? "",
                $link
            ];
        }

        // Insert into sheet
        $rowNumber = 6;
        foreach ($excel_array as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row[0]);
            $sheet->setCellValue('B' . $rowNumber, $row[1]);
            $sheet->setCellValue('C' . $rowNumber, $row[2]);
            $sheet->setCellValue('D' . $rowNumber, $row[3]);
            $sheet->setCellValue('E' . $rowNumber, $row[4]);
            $sheet->setCellValue('F' . $rowNumber, $row[5]);

            // Link
            $sheet->getCell('A' . $rowNumber)->getHyperlink()->setUrl($row[6]);
            $sheet->getCell('A' . $rowNumber)->getHyperlink()->setTooltip('View course details');
            $sheet->getStyle('A' . $rowNumber)->applyFromArray([
                'font' => [
                    'color' => ['rgb' => '0563C1'],
                    'underline' => true,
                ],
            ]);

            $rowNumber++;
        }

        $lastRow = $rowNumber - 1;
        $sheet->getStyle("C6:C{$lastRow}")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        $sheet->getStyle("D6:D{$lastRow}")->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->setShowGridlines(false);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'LondonTFE-Course-Schedule-' . date('d-m-Y') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function fetchCoursesData($record = null)
    {
        $query = DB::table('course')
            ->select(
                'course.id as course_id',
                'course.course_name',
                'course.seo_name',
                'course.course_duration',
                'course.course_duration_type',
                'course_date_venue.id as date_venue_id',
                'course_date_venue.venue',
                'course_date_venue.venue_id',
                'course_date_venue.start_date',
                DB::raw('DATE_ADD(course_date_venue.start_date, INTERVAL ((course.course_duration * course.course_duration_type) - 1) DAY) AS end_date'),
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT category.category_name SEPARATOR '|')
                    FROM category
                    JOIN course_category_assoc ON course_category_assoc.category_id = category.id
                    WHERE course_category_assoc.course_id = course.id
                ) AS course_category"),
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT category.category_seo_name SEPARATOR '|')
                    FROM category
                    JOIN course_category_assoc ON course_category_assoc.category_id = category.id
                    WHERE course_category_assoc.course_id = course.id
                ) AS course_category_slug"),
                'course.price_corporate_dollar',
                'course.price_individual_dollar',
                'course.price_tier_id',
                'price_tier.base_rate',
                'price_tier.daily_rate'
            )
            ->leftJoin('course_date_venue', 'course_date_venue.course_id', '=', 'course.id')
            ->leftJoin('price_tier', 'price_tier.id', '=', 'course.price_tier_id')
            ->where('course.status', '1')
            ->where('course.course_type', '1');

        if ($record && $record->type === 'listpage') {
            $data = json_decode($record->querytxt, true);
            if ($data) {
                // Category Filter
                $categories = null;
                if (!empty($data['category'])) {
                    $categories = is_array($data['category']) ? $data['category'] : explode(',', $data['category']);
                }

                if (!empty($categories)) {
                    $query->join('course_category_assoc as CCA', 'CCA.course_id', '=', 'course.id')
                        ->join('category as cat', 'CCA.category_id', '=', 'cat.id')
                        ->where(function ($q) use ($categories) {
                            $q->whereIn('cat.category_seo_name', $categories)
                                ->orWhereIn('CCA.category_id', $categories);
                        });
                }

                // Venue / Location Filter
                $venuesInput = null;
                if (!empty($data['location'])) {
                    $venuesInput = is_array($data['location']) ? $data['location'] : explode(',', $data['location']);
                } elseif (!empty($data['venue'])) {
                    $venuesInput = is_array($data['venue']) ? $data['venue'] : explode(',', $data['venue']);
                }

                if (!empty($venuesInput)) {
                    $query->join('venue as v', 'course_date_venue.venue_id', '=', 'v.id')
                        ->where(function ($q) use ($venuesInput) {
                            $q->whereIn('v.venue_seo_name', $venuesInput)
                                ->orWhereIn('course_date_venue.venue', $venuesInput);
                        });
                }

                // Certification filter
                if (!empty($data['certification']) && $data['certification'] === 'yes') {
                    $query->whereExists(function ($q) {
                        $q->select(DB::raw(1))
                            ->from('course_accreditation_assoc as caa')
                            ->whereColumn('caa.course_id', 'course.id');
                    });
                }

                // Date & Sort Filters
                $sort = $data['sort'] ?? $data['orderby'] ?? '';
                if ($sort === 'thisweek' || $sort === 'this_week') {
                    $query->whereBetween('course_date_venue.start_date', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')]);
                } elseif ($sort === 'this_month') {
                    $query->whereMonth('course_date_venue.start_date', now()->month)
                        ->whereYear('course_date_venue.start_date', now()->year);
                } elseif ($sort === 'upcoming_month') {
                    $nextMonth = now()->addMonth();
                    $query->whereMonth('course_date_venue.start_date', $nextMonth->month)
                        ->whereYear('course_date_venue.start_date', $nextMonth->year);
                }

                if (!empty($data['month']) && !empty($data['year'])) {
                    $query->whereIn(DB::raw('DATE_FORMAT(course_date_venue.start_date, "%Y")'), (array) $data['year']);
                    $query->whereIn(DB::raw('DATE_FORMAT(course_date_venue.start_date, "%m")'), (array) $data['month']);
                } elseif (!empty($data['month'])) {
                    $query->whereIn(DB::raw('DATE_FORMAT(course_date_venue.start_date, "%m")'), (array) $data['month']);
                } elseif (!empty($data['year'])) {
                    $query->whereIn(DB::raw('DATE_FORMAT(course_date_venue.start_date, "%Y")'), (array) $data['year']);
                }

                $query->where('course_date_venue.status', '1');
                $query->where('course_date_venue.start_date', '>', date('Y-m-d'));
                $query->groupBy('course.id', 'course.course_name', 'course.seo_name', 'course.course_duration', 'course.course_duration_type', 'course_date_venue.id', 'course_date_venue.venue', 'course_date_venue.venue_id', 'course_date_venue.start_date', 'course.price_corporate_dollar', 'course.price_individual_dollar', 'course.price_tier_id', 'price_tier.base_rate', 'price_tier.daily_rate');

                // Order by logic
                if ($sort == 'atozsort' || $sort == 'alpha_asc') {
                    $query->orderBy('course.course_name', 'asc');
                } elseif ($sort == 'upcomingdt' || $sort == 'date_asc') {
                    $query->orderBy('course_date_venue.start_date', 'asc');
                } elseif ($sort == 'ztoasort' || $sort == 'alpha_desc') {
                    $query->orderBy('course.course_name', 'desc');
                } else {
                    $query->orderBy('course.course_name', 'asc');
                }
            } else {
                $query->orderBy('course_date_venue.start_date', 'asc');
            }
        } else {
            $query->orderBy('course_date_venue.start_date', 'asc');
        }

        return $query->get();
    }

    private function calculateGbpPrice($row)
    {
        $gbpPrice = 0;
        if (!empty($row->base_rate)) {
            $days = (int) ($row->course_duration ?? 0);
            $baseRate = (float) $row->base_rate * (round($days / 5));
            $dailyRate = (float) $row->daily_rate * $days;
            $gbpPrice = round(($baseRate + $dailyRate) / 100) * 100;
        }
        return $gbpPrice;
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------

    private function sendAdminEmail($data)
    {
        try {
            $result = DB::table('auto_responce_content')->where('form_name', 'admin_sch_email')->first();
            if (!$result)
                return;

            $replaceArray = [
                '{FIRSTNAME}' => $data['fname'] ?? '',
                '{LASTNAME}' => $data['lname'] ?? '',
                '{EMAIL}' => $data['email'] ?? '',
                '{PHONECODE}' => $data['phoneCode'] ?? '',
                '{PHONENO}' => $data['phone'] ?? '',
                '{COMPANYNAME}' => $data['company'] ?? '',
                '{JOBTITLE}' => $data['jobtitle'] ?? '',
            ];

            $adminsubscrtionContent = str_replace(
                array_keys($replaceArray),
                array_values($replaceArray),
                $result->mail_content
            );

            $apiKey = env('MANDRILL_API_KEY', '');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $response = \Illuminate\Support\Facades\Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $adminsubscrtionContent,
                    'text' => strip_tags($adminsubscrtionContent),
                    'subject' => $result->mail_subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => [
                        ['email' => env('TO_MAIL', 'info@londontfe.com'), 'type' => 'to'],
                        ['email' => env('MANDRIL_MAIL_CC', ''), 'type' => 'to'],
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Mandrill Admin Course Schedule Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send admin course schedule email: ' . $e->getMessage());
        }
    }

    /**
     * Send the verification download link email to the user (mirrors legacy autoMailCourseDirectoryDwnld).
     */
    private function autoMailCourseDirectoryDwnld($name, $email, $downtype, $expireAt, $downloadLink)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            // Try to find the template; fall back to a sensible default
            $result = DB::table('auto_responce_content')->where('form_name', 'document_download_mail')->first();

            if ($result && !empty($result->mail_content)) {
                $replaceArray = [
                    '{NAME}' => $name,
                    '{DOWNTYPE}' => $downtype,
                    '{EXPDATE}' => $expireAt,
                    '{COURSE_OUTLINE_URL}' => $downloadLink,
                    '{DOWNLOAD_LINK}' => $downloadLink,
                ];
                $body = str_replace(array_keys($replaceArray), array_values($replaceArray), $result->mail_content);
                $subject = $result->mail_subject ?? 'Your Course Schedule Download Link';
            } else {
                $subject = 'Your Course Schedule Download Link';
                $body = "
                    <p>Dear {$name},</p>
                    <p>Thank you for requesting our Course Schedule ({$downtype} format).</p>
                    <p>Please use the link below to download it. The link will expire on <strong>{$expireAt}</strong>.</p>
                    <p><a href=\"{$downloadLink}\">Download Course Schedule</a></p>
                    <p>Kind regards,<br>London Training for Excellence</p>
                ";
            }

            $response = \Illuminate\Support\Facades\Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $body,
                    'text' => strip_tags($body),
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => [
                        ['email' => $email, 'name' => $name, 'type' => 'to']
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Mandrill Schedule Download Link Email Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send schedule download link email: ' . $e->getMessage());
        }
    }

    /**
     * Simple price lookup matching the legacy calculate_tier_price helper.
     */
    private function calculateTierPrice($courseId, $venueId, $currency = 'usd')
    {
        try {
            $price = DB::table('course_price')
                ->where('course_id', $courseId)
                ->where('venue_id', $venueId)
                ->where('status', 1)
                ->first();

            if (!$price) {
                $price = DB::table('course_price')
                    ->where('course_id', $courseId)
                    ->where('status', 1)
                    ->first();
            }

            $usdPrice = $price ? ($price->usd_price ?? $price->price ?? 0) : 0;

            return ['usd_price' => $usdPrice];
        } catch (\Exception $e) {
            return ['usd_price' => 0];
        }
    }
}
