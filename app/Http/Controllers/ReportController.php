<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Timesheet;
use App\Models\Timesheet_timerange;
use App\Models\Type;
use App\Models\Typecategory;
use App\Models\Timesheet_type_user;
use App\Models\user;
use Config;
use App\Libraries\Helpers\UserHelpers;
use Log;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel2007;
use ZipArchive;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $types = DB::table('type AS t')
                        ->leftJoin('typecategory AS c', 'c.id', '=', 't.typecategoryid')
                        ->where('t.status', 1)
                        ->where('c.status', 1)
                        ->select(DB::raw("min(t.typecategoryid) as mykey"), DB::raw("group_concat(concat(t.id,'_',t.name) SEPARATOR '|') as list"))
                        ->groupBy('t.typecategoryid')
                        ->orderBy('mykey', 'asc')
                        ->get();

            $users = User::where('status', 1)
              ->orderBy('id', 'asc')
              ->get();

            $typecategories = Typecategory::where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            return view('report.index', ['types' => $types, 'users'=> $users, 'typecategories' => $typecategories]);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->all();
            if(!empty($input['mytypes'])) {
                $mytypes = array();
                if(!empty($input['mytypes'])) {
                    $mytypes = explode(',', $input['mytypes']);
                }
                $result = array();
                $result2 = array();
                foreach($mytypes as $type) {
                    $tempArr = array();
                    $temp = explode('_', $type);
                    $theid = $temp[1];
                    $c = $temp[0];
                    $myCategoryAndTypeName = DB::table('type')
                                              ->join('typecategory', 'typecategory.id', '=', 'type.typecategoryid')
                                              ->select('typecategory.name', 'type.name as typename')->where('type.id', '=', $theid)->get();
                    $myCategoryAndTypeName = collect($myCategoryAndTypeName)->map(function($x){ return (array) $x; })->toArray();
                    
                    $fromtime = $input['daterange_1'];
                    $totime = date('Y-m-d', strtotime('+1 day', strtotime($input['daterange_2'])));
                    
                    $mytemp1 = DB::table('timesheet_type_user AS t')
                                    ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                    ->join('users', 'users.id', '=', 't.userid')            
                                    ->select(DB::raw("concat(users.first_name, ' ', users.last_name) as name"), 't.starttime', 't.endtime', DB::raw('FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2) as hours'))
                                    ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['t.typeid', '=', $theid],['t.starttime', '>=', $fromtime],['t.endtime', '<', $totime]])
                                    ->orderBy('users.id', 'asc')->orderBy('t.starttime', 'asc')->get();

                    $mytemp1 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray(); 
                    foreach($mytemp1 as $value) {
                        //Log::info("at 81, value: " . json_encode($value));
                        $result[]  = array_merge($value, array('typename' => $myCategoryAndTypeName[0]['name'] . '_' . ($myCategoryAndTypeName[0]['typename'])));
                        $tempArr[] = array_merge($value, array('typename' => $myCategoryAndTypeName[0]['name'] . '_' . ($myCategoryAndTypeName[0]['typename'])));
                    }
                    $mytemp = $myCategoryAndTypeName[0]['typename'];
                    $mytemp = str_replace(" ","",$mytemp);
                    $result2[$mytemp] = $tempArr;
                }
                $zipfilename = '';
                $header = array('User Name','From','To','Hours','Type Name');

                if($input['file'] === 'excel') {
                    if($input['separate'] === '0') {//excel, all in one file
                        $objPHPExcel = new PHPExcel();
                        
                        $objPHPExcel->getProperties()->setCreator("Timesheet");
                        $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                        $objPHPExcel->setActiveSheetIndex(0);
                        $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $input['daterange_1'] . '_'. $input['daterange_2'];
                        $temp = 'timesheet';
                        $objPHPExcel->getActiveSheet()->setTitle($temp);

                        $r = 1;
                        $column = 'A';
                        foreach ($header as $key) {
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $column . $r,
                                $key
                            );
                            $column++;
                        }
                        $r++;
                        foreach ($result as $v) {
                            $column = 'A';
                            foreach ($v as $value) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $value
                                );
                                $column++;
                            }
                            $r++;
                        }
                        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                        $string='Content-Disposition: attachment; filename="'.$filename.'.xlsx"';
                        header($string);
                        header("Cache-Control: max-age=0");
                        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                        $objWriter->save("php://output");
                    } else if($input['separate'] === '1') {//excel, each row in a separate file
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 142, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $objPHPExcel = new PHPExcel();
                            $objPHPExcel->getProperties()->setCreator("Timesheet");
                            $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                            $objPHPExcel->setActiveSheetIndex(0);
                            $temp = 'timesheet';
                            $objPHPExcel->getActiveSheet()->setTitle($temp);

                            $r = 1;
                            $column = 'A';
                            foreach ($header as $key) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $key
                                );
                                $column++;
                            }
                            $r++;
                            foreach ($myresult as $v) {
                                $column = 'A';
                                foreach ($v as $k => $value) {
                                    $objPHPExcel->getActiveSheet()->setCellValue(
                                        $column . $r,
                                        $value
                                    );
                                    $column++;
                                }
                                $r++;
                            }
                            $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_1'] . '_' . $input['daterange_2'];
                            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                            $zipfilename = $input['filename'] ? $input['filename'] : ('timesheet_xls_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $objWriter->save($excel_file_tmp);
                            $zip->addFile($excel_file_tmp, $filename . '.xlsx');
                            $zipcounter++;
                        }
                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'csv') {
                    if($input['separate'] === '0') {//csv, all in one file
                        $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $input['daterange_1'] . '_'. $input['daterange_2'];
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename='.$filename.'.csv');
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $header);

                        foreach($result as $v) {
                            fputcsv($file, array($v['name'], $v['starttime'], $v['endtime'], $v['hours'], $v['typename']));
                        }
                        fclose($file);
                    } else if($input['separate'] === '1') {//csv, each row in a separate file
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 219, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_1'] . '_' . $input['daterange_2'];
                            $zipfilename = $input['filename'] ? $input['filename'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $file = fopen($excel_file_tmp, 'w');
                            fputcsv($file, $header);
                            foreach($myresult as $v) {
                                fputcsv($file, array($v['name'], $v['starttime'], $v['endtime'], $v['hours'], $v['typename']));
                            }
                            fclose($file);
                            $zip->addFile($excel_file_tmp, $filename . '.csv');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'pdf') {
                    if($input['separate'] === '0') {//pdf, all in one file
                        require_once base_path() . '/vendor/autoload.php';
                        $pdf = new \Pdf\Pdf;
                        $pdf->AliasNbPages("%p7%a3*s9#t58@v61!k20");
                        $rowsPerPage = intval(UserHelpers::getConstants('REPORT_DATAROW_ONEPAGE_INT'));  // fixed rows of data
                        $pdf->SetHeaderText(($input['filename'] ? $input['filename'] : 'Report by types') . ' : ' . $input['daterange_1'] . ' to '. $input['daterange_2']);
                        $page_counter = 1;
                        $row_counter = 0;
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',12);
                        $pdf->cell(30,8,$header[0],1,"","C");
                        $pdf->cell(45,8,$header[1],1,"","C");
                        $pdf->cell(45,8,$header[2],1,"","C");
                        $pdf->cell(25,8,$header[3],1,"","C");
                        $pdf->cell(55,8,$header[4],1,"","C");
                        $pdf->Ln();

                        $pdf->SetFont("Arial","",10);

                        //fixed rows of data in one page
                        foreach($result as $v) {
                            if($row_counter === $rowsPerPage) {
                                $pdf->AddPage();
                                $pdf->SetFont('Arial','B',12);
                                $pdf->cell(30,8,$header[0],1,"","C");
                                $pdf->cell(45,8,$header[1],1,"","C");
                                $pdf->cell(45,8,$header[2],1,"","C");
                                $pdf->cell(25,8,$header[3],1,"","C");
                                $pdf->cell(55,8,$header[4],1,"","C");
                                $pdf->Ln();
                            }

                            $pdf->cell(30,8,$v['name'],1,"","C");
                            $pdf->cell(45,8,$v['starttime'],1,"","C");
                            $pdf->cell(45,8,$v['endtime'],1,"","C");
                            $pdf->cell(25,8,$v['hours'],1,"","R");
                            $pdf->cell(55,8,$v['typename'],1,"","C");
                            $row_counter++;
                            $pdf->Ln();
                        }

                        $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $input['daterange_1'] . '_'. $input['daterange_2'];
                        $pdf->Output($filename . ".pdf", "D");
                        exit;
                    } else if($input['separate'] === '1') {//pdf, each row in a separate file
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 301, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            require_once base_path() . '/vendor/autoload.php';
                            $pdf = new \Pdf\Pdf;
                            $pdf->AliasNbPages("%p7%a3*s9#t58@v61!k20");
                            $rowsPerPage = intval(UserHelpers::getConstants('REPORT_DATAROW_ONEPAGE_INT'));  // fixed rows of data
                            $pdf->SetHeaderText(($input['filename'] ? $input['filename'] : 'Report by types') . ' : ' . $input['daterange_1'] . ' to '. $input['daterange_2']);

                            $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_1'] . '_' . $input['daterange_2'];
                            $zipfilename = $input['filename'] ? $input['filename'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $page_counter = 1;
                            $row_counter = 0;
                            $pdf->AddPage();
                            $pdf->SetFont('Arial','B',12);
                            $pdf->cell(30,8,$header[0],1,"","C");
                            $pdf->cell(45,8,$header[1],1,"","C");
                            $pdf->cell(45,8,$header[2],1,"","C");
                            $pdf->cell(25,8,$header[3],1,"","C");
                            $pdf->cell(55,8,$header[4],1,"","C");
                            $pdf->Ln();

                            $pdf->SetFont("Arial","",10);

                            //fixed rows of data in one page
                            foreach($myresult as $v) {
                                if($row_counter === $rowsPerPage) {
                                    $pdf->AddPage();
                                    $pdf->SetFont('Arial','B',12);
                                    $pdf->cell(30,8,$header[0],1,"","C");
                                    $pdf->cell(45,8,$header[1],1,"","C");
                                    $pdf->cell(45,8,$header[2],1,"","C");
                                    $pdf->cell(25,8,$header[3],1,"","C");
                                    $pdf->cell(55,8,$header[4],1,"","C");
                                    $pdf->Ln();
                                }

                                $pdf->cell(30,8,$v['name'],1,"","C");
                                $pdf->cell(45,8,$v['starttime'],1,"","C");
                                $pdf->cell(45,8,$v['endtime'],1,"","C");
                                $pdf->cell(25,8,$v['hours'],1,"","R");
                                $pdf->cell(55,8,$v['typename'],1,"","C");
                                $row_counter++;
                                $pdf->Ln();
                            }

                            $pdf->Output($excel_file_tmp, "F");

                            $zip->addFile($excel_file_tmp, $filename . '.pdf');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'json') {
                    if($input['separate'] === '0') {
                        $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $input['daterange_1'] . '_'. $input['daterange_2'];
                        header('Content-Type: application/json; charset=utf-8');
                        header('Content-Disposition: attachment; filename='.$filename.'.json');
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        $file = fopen('php://output', 'w');
                        fwrite($file, json_encode($result));
                        fclose($file);
                    } else if($input['separate'] === '1') {
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 385, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $filename = ($input['filename'] ? $input['filename'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_1'] . '_' . $input['daterange_2'];
                            $zipfilename = $input['filename'] ? $input['filename'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $json_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $json_file_tmp;
                            $file = fopen($json_file_tmp, 'w');
                            fwrite($file, json_encode($myresult));
                            fclose($file);
                            $zip->addFile($json_file_tmp, $filename . '.json');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                }
            } else if(!empty($input['myusers'])) {
                $myusers = explode(',', $input['myusers']);
                $result = array();
                $result2 = array();
                foreach($myusers as $myuser) {
                    $tempArr = array();
                    $temp = explode('_', $myuser);
                    $theid = $temp[1];
                    $fromtime = $input['daterange_3'];
                    $totime = date('Y-m-d', strtotime('+1 day', strtotime($input['daterange_4'])));

                    $username = 'none';
                    $myusername = DB::table('users')->select('first_name', 'last_name')->where('id', '=', $theid)->get();
                    $myusername = collect($myusername)->map(function($x){ return (array) $x; })->toArray();
                    $mytemp1 = null;

                    if($input['file'] !== 'qbiif' && $input['file'] !== 'qbexcel') {
                        $mytemp1 = DB::table('timesheet_type_user AS t')
                                        ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                        ->join('users', 'users.id', '=', 't.userid')
                                        ->join('type AS p', 'p.id', '=', 't.typeid')
                                        ->join('typecategory AS c', 'p.typecategoryid', '=', 'c.id')
                                        ->select(DB::raw("concat(users.first_name, ' ', users.last_name) as name"), 't.starttime', 't.endtime', DB::raw('FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2) as hours'), DB::raw("CONCAT(c.name, '_', p.name) as typename"))
                                        ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['users.id', '=', $theid],['t.starttime', '>=', $fromtime],['t.endtime', '<', $totime]])
                                        ->orderBy('users.id', 'asc')->orderBy('t.starttime', 'asc')->get();

                        $mytemp1 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray(); 

                    } else if($input['file'] === 'qbiif') {
                        $mytemp1 = DB::table('timesheet_type_user AS t')
                                        ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                        ->join('users', 'users.id', '=', 't.userid')
                                        ->join('type AS p', 'p.id', '=', 't.typeid')
                                        ->join('typecategory AS c', 'p.typecategoryid', '=', 'c.id')
                                        ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['users.id', '=', $theid],['t.starttime', '>=', $fromtime],['t.endtime', '<', $totime]])
                                        ->select(DB::raw("min(t.date) as date"), DB::raw("min(concat(users.first_name, ' ', users.last_name)) as name"), DB::raw('FORMAT(sum(time_to_sec(timediff(t.endtime, t.starttime ))) / 3600, 2) as hours'))
                                        ->groupBy('users.id', 't.date')
                                        ->orderBy('users.id', 'asc')->orderBy('t.date', 'asc')->get();

                        $mytemp1 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray();
                    } else if($input['file'] === 'qbexcel') {
                        $mytemp1 = DB::table('timesheet_type_user AS t')
                                        ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                        ->join('users', 'users.id', '=', 't.userid')
                                        ->join('type AS p', 'p.id', '=', 't.typeid')
                                        ->join('typecategory AS c', 'p.typecategoryid', '=', 'c.id')
                                        ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['users.id', '=', $theid],['t.starttime', '>=', $fromtime],['t.endtime', '<', $totime]])
                                        ->select(DB::raw("min(t.date) as date"), DB::raw("min(concat(users.first_name, ' ', users.last_name)) as name"), DB::raw('FORMAT(sum(time_to_sec(timediff(t.endtime, t.starttime ))) / 3600, 2) as hours'))
                                        ->groupBy('users.id', 't.date')
                                        ->orderBy('users.id', 'asc')->orderBy('t.date', 'asc')->get();

                        $mytemp1 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray();
                    }

                    foreach($mytemp1 as $value) {
                        //Log::info("at 466, value: " . json_encode($value));
                        $result[] = $value;
                        $tempArr[] = $value;
                        $username = $value['name'];
                    }
                    //Log::info("at 471, username: " . $username);
                    $username = str_replace(' ', '_', $username);
                    if($username !== 'none') {
                        $result2[$username] = $tempArr;
                    } else {
                        $username = ($myusername[0]['first_name']) . ' ' . ($myusername[0]['last_name']);
                        $username = str_replace(' ', '_', $username);
                        if($username !== '') {
                            $result2[$username] = $tempArr;
                        }
                    }
                }
                //Log::info("at 483, result: " . json_encode($result));
                //Log::info("at 484, result2: " . json_encode($result2));
                $header = array('User Name','From','To','Hours','Type Name');

                if($input['file'] === 'excel') {
                    if($input['separate'] === '0') {//excel, all in one file
                        $objPHPExcel = new PHPExcel();

                        $objPHPExcel->getProperties()->setCreator("Timesheet");
                        $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                        $objPHPExcel->setActiveSheetIndex(0);
                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_'. $input['daterange_3']. '_'. $input['daterange_4'];
                        $temp = 'timesheet';

                        $objPHPExcel->getActiveSheet()->setTitle($temp);

                        $r = 1;
                        $column = 'A';
                        foreach ($header as $key) {
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $column . $r,
                                $key
                            );
                            $column++;
                        }
                        $r++;
                        foreach ($result as $v) {
                            $column = 'A';
                            foreach ($v as $value) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $value
                                );
                                $column++;
                            }
                            $r++;
                        }
                        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                        $string='Content-Disposition: attachment; filename="'.$filename.'.xlsx"';
                        header($string);
                        header("Cache-Control: max-age=0");
                        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                        $objWriter->save("php://output");
                    } else if($input['separate'] === '1') {//excel, each type in separate files
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 538, result2: " . json_encode($result2));

                        foreach($result2 as $username => $myresult) {
                            $objPHPExcel = new PHPExcel();
                            $objPHPExcel->getProperties()->setCreator("Timesheet");
                            $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                            $objPHPExcel->setActiveSheetIndex(0);
                            $temp = 'timesheet';
                            $objPHPExcel->getActiveSheet()->setTitle($temp);

                            $r = 1;
                            $column = 'A';
                            foreach ($header as $key) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $key
                                );
                                $column++;
                            }
                            $r++;
                            foreach ($myresult as $v) {
                                $column = 'A';
                                foreach ($v as $k => $value) {
                                    $objPHPExcel->getActiveSheet()->setCellValue(
                                        $column . $r,
                                        $value
                                    );
                                    $column++;
                                }
                                $r++;
                            }
                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $username . '_' . $zipcounter . '_' . $input['daterange_3']. '_' . $input['daterange_4'];
                            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $objWriter->save($excel_file_tmp);
                            $zip->addFile($excel_file_tmp, $filename . '.xlsx');
                            $zipcounter++;
                        }
                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'csv') {
                    if($input['separate'] === '0') {
                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $input['daterange_3'] . '_'. $input['daterange_4'];
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename='.$filename.'.csv');
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $header);

                        foreach($result as $v) {
                            fputcsv($file, array($v['name'], $v['starttime'], $v['endtime'], $v['hours'], $v['typename']));
                        }
                        fclose($file);
                    } else if($input['separate'] === '1') {
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 615, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_3'] . '_' . $input['daterange_4'];
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $file = fopen($excel_file_tmp, 'w');
                            fputcsv($file, $header);
                            foreach($myresult as $v) {
                                fputcsv($file, array($v['name'], $v['starttime'], $v['endtime'], $v['hours'], $v['typename']));
                            }
                            fclose($file);
                            $zip->addFile($excel_file_tmp, $filename . '.csv');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'qbiif') {
                    $header = array('DATE','EMP','DURATION');
                    if($input['separate'] === '0') {
                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $input['daterange_3'] . '_'. $input['daterange_4'];
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename='.$filename.'.iif');
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        $file = fopen('php://output', 'w');
                        //fputcsv($file, $header, "\t");
                        fputs($file, implode("\t", $header)."\r\n");

                        foreach($result as $v) {
                            $tempValue = (float)$v['hours'];
                            $tempValue1 = floor($tempValue);
                            $tempValue2 = round(($tempValue - $tempValue1) * 60);
                            //fputcsv($file, array($v['name'], $v['date'], strval($tempValue1).":".strval($tempValue2)), "\t",chr(0));
                            fputs($file, implode("\t", array($v['date'], $v['name'], strval($tempValue1).":".strval($tempValue2)))."\r\n");
                        }
                        fclose($file);
                    } else if($input['separate'] === '1') {
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 672, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_3'] . '_' . $input['daterange_4'];
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $file = fopen($excel_file_tmp, 'w');
                            //fputcsv($file, $header, "\t");
                            fputs($file, implode("\t", $header)."\r\n");
                            foreach($myresult as $v) {
                                $tempValue = (float)$v['hours'];
                                $tempValue1 = floor($tempValue);
                                $tempValue2 = round(($tempValue - $tempValue1) * 60);
                                //fputcsv($file, array($v['date'], $v['name'], strval($tempValue1).":".strval($tempValue2)), "\t",chr(0));
                                fputs($file, implode("\t", array($v['date'], $v['name'], strval($tempValue1).":".strval($tempValue2)))."\r\n");
                            }
                            fclose($file);
                            $zip->addFile($excel_file_tmp, $filename . '.iif');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'qbexcel') {
                    $header = array('DATE','EMP','DURATION');
                    if($input['separate'] === '0') {//excel, all in one file
                        $objPHPExcel = new PHPExcel();

                        $objPHPExcel->getProperties()->setCreator("Timesheet");
                        $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                        $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                        $objPHPExcel->setActiveSheetIndex(0);
                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_quickbooks_report') . '_'. $input['daterange_3']. '_'. $input['daterange_4'];
                        $temp = 'timesheet';

                        $objPHPExcel->getActiveSheet()->setTitle($temp);

                        $r = 1;
                        $column = 'A';
                        foreach ($header as $key) {
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $column . $r,
                                $key
                            );
                            $column++;
                        }
                        $r++;
                        foreach ($result as $v) {
                            $column = 'A';
                            foreach ($v as $value) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $value
                                );
                                $column++;
                            }
                            $r++;
                        }
                        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                        $string='Content-Disposition: attachment; filename="'.$filename.'.xlsx"';
                        header($string);
                        header("Cache-Control: max-age=0");
                        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                        $objWriter->save("php://output");
                    } else if($input['separate'] === '1') {//excel, each type in separate files
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 757, result2: " . json_encode($result2));

                        foreach($result2 as $username => $myresult) {
                            $objPHPExcel = new PHPExcel();
                            $objPHPExcel->getProperties()->setCreator("Timesheet");
                            $objPHPExcel->getProperties()->setLastModifiedBy("Timesheet");
                            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Document");
                            $objPHPExcel->getProperties()->setDescription("document for Office 2007 XLSX");
                            $objPHPExcel->setActiveSheetIndex(0);
                            $temp = 'timesheet';
                            $objPHPExcel->getActiveSheet()->setTitle($temp);

                            $r = 1;
                            $column = 'A';
                            foreach ($header as $key) {
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $column . $r,
                                    $key
                                );
                                $column++;
                            }
                            $r++;
                            foreach ($myresult as $v) {
                                $column = 'A';
                                foreach ($v as $k => $value) {
                                    $objPHPExcel->getActiveSheet()->setCellValue(
                                        $column . $r,
                                        $value
                                    );
                                    $column++;
                                }
                                $r++;
                            }
                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_quickbooks_report') . '_' . $username . '_' . $zipcounter . '_' . $input['daterange_3']. '_' . $input['daterange_4'];
                            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_quickbooks_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $objWriter->save($excel_file_tmp);
                            $zip->addFile($excel_file_tmp, $filename . '.xlsx');
                            $zipcounter++;
                        }
                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'pdf') {
                    if($input['separate'] === '0') {
                        require_once base_path() . '/vendor/autoload.php';
                        $pdf = new \Pdf\Pdf;
                        $pdf->AliasNbPages("%p7%a3*s9#t58@v61!k20");
                        $rowsPerPage = intval(UserHelpers::getConstants('REPORT_DATAROW_ONEPAGE_INT'));  // fixed rows of data
                        $pdf->SetHeaderText(($input['filename2'] ? $input['filename2'] : 'Report by types') . ' : ' . $input['daterange_3'] . ' to '. $input['daterange_4']);
                        $page_counter = 1;
                        $row_counter = 0;
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',12);
                        $pdf->cell(30,8,$header[0],1,"","C");
                        $pdf->cell(45,8,$header[1],1,"","C");
                        $pdf->cell(45,8,$header[2],1,"","C");
                        $pdf->cell(25,8,$header[3],1,"","C");
                        $pdf->cell(55,8,$header[4],1,"","C");
                        $pdf->Ln();
                        
                        $pdf->SetFont("Arial","",10);

                        //fixed rows of data in one page
                        foreach($result as $v) {
                            if($row_counter === $rowsPerPage) {
                                $pdf->AddPage();
                                $pdf->SetFont('Arial','B',12);
                                $pdf->cell(30,8,$header[0],1,"","C");
                                $pdf->cell(45,8,$header[1],1,"","C");
                                $pdf->cell(45,8,$header[2],1,"","C");
                                $pdf->cell(25,8,$header[3],1,"","C");
                                $pdf->cell(55,8,$header[4],1,"","C");
                                $pdf->Ln();
                            }

                            $pdf->cell(30,8,$v['name'],1,"","C");
                            $pdf->cell(45,8,$v['starttime'],1,"","C");
                            $pdf->cell(45,8,$v['endtime'],1,"","C");
                            $pdf->cell(25,8,$v['hours'],1,"","R");
                            $pdf->cell(55,8,$v['typename'],1,"","C");
                            $row_counter++;
                            $pdf->Ln();
                        }

                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $input['daterange_3'] . '_'. $input['daterange_4'];
                        $pdf->Output($filename . ".pdf", "D");
                        exit;
                    } else if($input['separate'] === '1') {//save different user's data into different pdf files
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 865, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            require_once base_path() . '/vendor/autoload.php';
                            $pdf = new \Pdf\Pdf;
                            $pdf->AliasNbPages("%p7%a3*s9#t58@v61!k20");
                            $rowsPerPage = intval(UserHelpers::getConstants('REPORT_DATAROW_ONEPAGE_INT'));  // fixed rows of data
                            $pdf->SetHeaderText(($input['filename2'] ? $input['filename2'] : 'Report by types') . ' : ' . $input['daterange_3'] . ' to '. $input['daterange_4']);

                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_3'] . '_' . $input['daterange_4'];
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $excel_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $excel_file_tmp;
                            $page_counter = 1;
                            $row_counter = 0;
                            $pdf->AddPage();
                            $pdf->SetFont('Arial','B',12);
                            $pdf->cell(30,8,$header[0],1,"","C");
                            $pdf->cell(45,8,$header[1],1,"","C");
                            $pdf->cell(45,8,$header[2],1,"","C");
                            $pdf->cell(25,8,$header[3],1,"","C");
                            $pdf->cell(55,8,$header[4],1,"","C");
                            $pdf->Ln();

                            $pdf->SetFont("Arial","",10);

                            //fixed rows of data in one page
                            foreach($myresult as $v) {
                                if($row_counter === $rowsPerPage) {
                                    $pdf->AddPage();
                                    $pdf->SetFont('Arial','B',12);
                                    $pdf->cell(30,8,$header[0],1,"","C");
                                    $pdf->cell(45,8,$header[1],1,"","C");
                                    $pdf->cell(45,8,$header[2],1,"","C");
                                    $pdf->cell(25,8,$header[3],1,"","C");
                                    $pdf->cell(55,8,$header[4],1,"","C");
                                    $pdf->Ln();
                                }

                                $pdf->cell(30,8,$v['name'],1,"","C");
                                $pdf->cell(45,8,$v['starttime'],1,"","C");
                                $pdf->cell(45,8,$v['endtime'],1,"","C");
                                $pdf->cell(25,8,$v['hours'],1,"","R");
                                $pdf->cell(55,8,$v['typename'],1,"","C");
                                $row_counter++;
                                $pdf->Ln();
                            }

                            $pdf->Output($excel_file_tmp, "F");
                            $zip->addFile($excel_file_tmp, $filename . '.pdf');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                } else if($input['file'] === 'json') {
                    if($input['separate'] === '0') {
                        $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $input['daterange_3'] . '_'. $input['daterange_4'];
                        header('Content-Type: application/json; charset=utf-8');
                        header('Content-Disposition: attachment; filename='.$filename.'.json');    
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        $file = fopen('php://output', 'w');
                        fwrite($file, json_encode($result));
                        fclose($file);    
                    } else if($input['separate'] === '1') {
                        $zipcounter = 1;
                        $ini_val = ini_get('upload_tmp_dir');
                        $upload_tmp_dir = $ini_val ? $ini_val : sys_get_temp_dir();
                        //zip
                        $zip_file_tmp = tempnam($upload_tmp_dir, 'zip_timesheet');
                        $tmp_files = array();
                        $zip = new ZipArchive();
                        $zip->open($zip_file_tmp, ZipArchive::OVERWRITE);
                        //Log::info("at 948, result2: " . json_encode($result2));

                        foreach($result2 as $mykey => $myresult) {
                            $filename = ($input['filename2'] ? $input['filename2'] : 'timesheet_report') . '_' . $mykey . '_' . $zipcounter . '_' . $input['daterange_3'] . '_' . $input['daterange_4'];
                            $zipfilename = $input['filename2'] ? $input['filename2'] : ('timesheet_csv_report' . '_' . $zipcounter . '_' . Date('Ymd'));
                            $json_file_tmp = tempnam($upload_tmp_dir, $zipfilename);
                            $tmp_files[] = $json_file_tmp;
                            $file = fopen($json_file_tmp, 'w');
                            fwrite($file, json_encode($myresult));
                            fclose($file);
                            $zip->addFile($json_file_tmp, $filename . '.json');
                            $zipcounter++;
                        }

                        $zip->close();
                        $download_filename = $zipfilename . '.zip'; 
                        header("Content-Type: application/zip");
                        header("Content-Length: " . filesize($zip_file_tmp));
                        header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
                        readfile($zip_file_tmp);
                        foreach($tmp_files as $tmp) {
                            unlink($tmp);
                        }
                        unlink($zip_file_tmp);
                    }
                }
            }
        }
    }

    public function getdata(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->all();
            if(!empty($input['mytypes'])) {
                $mytypes = explode(',', $input['mytypes']);
                $result = array();
                $result2 = array();
                $idArr = array();
                $fromtime = $input['daterange_1'];
                $totime = date('Y-m-d', strtotime('+1 day', strtotime($input['daterange_2'])));

                $amountperpage = empty($input['amountperpage']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['amountperpage']);
                $pagenumber = empty($input['pagenumber']) ? 1 : intval($input['pagenumber']);
                $offset = ($pagenumber - 1) * $amountperpage;
                foreach($mytypes as $type) {
                    $tempArr = array();
                    $temp = explode('_', $type);
                    $idArr[] = $temp[1];
                }
                $mytemp1;
                $tempsql = DB::table('timesheet_type_user AS t')
                                ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                ->join('users', 'users.id', '=', 't.userid')
                                ->join('type AS a', 'a.id', '=', 't.typeid')
                                ->select(DB::raw("concat(users.first_name, ' ', users.last_name) as name"), 't.starttime', 't.endtime', DB::raw('FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2) as hours'), DB::raw("a.name as typename"))
                                ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['t.starttime', '>=', $fromtime],['t.endtime', '<=', $totime]])
                                ->whereIn('t.typeid', $idArr);

                $mytotal = DB::table('timesheet_type_user AS t')
                                ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                ->join('users', 'users.id', '=', 't.userid')
                                ->join('type AS a', 'a.id', '=', 't.typeid')
                                ->select(DB::raw('count(*) as totalamount'))
                                ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['t.starttime', '>=', $fromtime],['t.endtime', '<=', $totime]])
                                ->whereIn('t.typeid', $idArr)
                                ->get();

                if(empty($input['mysortby'])) {
                    $mytemp1 = $tempsql->orderBy('users.id', 'asc')->orderBy('t.starttime', 'asc')->offset($offset)->limit($amountperpage)->get();
                } else if(!empty($input['mysortby'])) {
                    //Log::info("at 1019, order by: " . $input['mysortby']);
                    $tempsortby = 't.typeid';
                    switch(strtolower(trim($input['mysortby']))) {
                        case 'name': $tempsortby = "concat(users.first_name, ' ', users.last_name)" ;break;
                        case 'starttime': $tempsortby = 't.starttime';break;
                        case 'endtime': $tempsortby = 't.endtime';break;
                        case 'hours': $tempsortby = "FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2)";break;
                        case 'typename': $tempsortby = "a.name";break;
                        default: break;
                    }
                    $mytemp1 = $tempsql->orderByRaw($tempsortby . ' ' . (empty($input['myorder']) ? 'asc' : $input['myorder']))->orderBy('users.id', 'asc')->offset($offset)->limit($amountperpage)->get();
                }

                $mytemp2 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray(); 
                $mytotal2 = collect($mytotal)->map(function($x){ return (array) $x; })->toArray(); 
                //Log::info("at 1034, mytotal2: " . json_encode($mytotal2));
                $totalpage = ceil($mytotal2[0]['totalamount'] / (float)$amountperpage);
                $mytotal3 = array();
                $mytotal3['totalpage'] = $totalpage;
                $mytotal4 = array();
                $mytotal4[] = $mytotal3;
                if(!empty($mytemp2)) {$result = array_merge($result, $mytemp2);}
                if(!empty($mytotal4)) {$result = array_merge($result, $mytotal4);}
                return json_encode($result);
            } else if(!empty($input['myusers'])) {
                $myusers = explode(',', $input['myusers']);
                $result = array();
                $result2 = array();
                $idArr = array();
                $fromtime = $input['daterange_1'];
                $totime = date('Y-m-d', strtotime('+1 day', strtotime($input['daterange_2'])));

                $amountperpage = empty($input['amountperpage']) ? intval(UserHelpers::getConstants('ROW_PER_TABLE_INT')) : intval($input['amountperpage']);
                $pagenumber = empty($input['pagenumber']) ? 1 : intval($input['pagenumber']);
                $offset = ($pagenumber - 1) * $amountperpage;
                foreach($myusers as $user) {
                    $tempArr = array();
                    $temp = explode('_', $user);
                    $idArr[] = $temp[1];
                }
                $mytemp1;
                $tempsql = DB::table('timesheet_type_user AS t')
                                ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                ->join('users', 'users.id', '=', 't.userid')
                                ->join('type AS a', 'a.id', '=', 't.typeid')
                                ->select(DB::raw("concat(users.first_name, ' ', users.last_name) as name"), 't.starttime', 't.endtime', DB::raw('FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2) as hours'), DB::raw("a.name as typename"))
                                ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['t.starttime', '>=', $fromtime],['t.endtime', '<=', $totime]])
                                ->whereIn('t.userid', $idArr);

                $mytotal = DB::table('timesheet_type_user AS t')
                                ->join('timesheet', 'timesheet.id', '=', 't.timesheetid')
                                ->join('users', 'users.id', '=', 't.userid')
                                ->join('type AS a', 'a.id', '=', 't.typeid')
                                ->select(DB::raw('count(*) as totalamount'))
                                ->where([['timesheet.approved', '=', 1],['users.status', '=', 1],['t.status', '=', 1],['t.starttime', '>=', $fromtime],['t.endtime', '<=', $totime]])
                                ->whereIn('t.userid', $idArr)
                                ->get();

                if(empty($input['mysortby'])) {
                    $mytemp1 = $tempsql->orderBy('users.id', 'asc')->orderBy('t.starttime', 'asc')->offset($offset)->limit($amountperpage)->get();
                } else if(!empty($input['mysortby'])) {
                    //Log::info("at 1080, order by: " . $input['mysortby']);
                    $tempsortby = 't.typeid';
                    switch(strtolower(trim($input['mysortby']))) {
                        case 'name': $tempsortby = "concat(users.first_name, ' ', users.last_name)" ;break;
                        case 'starttime': $tempsortby = 't.starttime';break;
                        case 'endtime': $tempsortby = 't.endtime';break;
                        case 'hours': $tempsortby = "FORMAT(time_to_sec(timediff(t.endtime, t.starttime )) / 3600, 2)";break;
                        case 'typename': $tempsortby = "a.name";break;
                        default: break;
                    }
                    $mytemp1 = $tempsql->orderByRaw($tempsortby . ' ' . (empty($input['myorder']) ? 'asc' : $input['myorder']))->orderBy('users.id', 'asc')->offset($offset)->limit($amountperpage)->get();
                }

                $mytemp2 = collect($mytemp1)->map(function($x){ return (array) $x; })->toArray();
                $mytotal2 = collect($mytotal)->map(function($x){ return (array) $x; })->toArray();
                //Log::info("at 1095, mytotal2: " . json_encode($mytotal2));
                $totalpage = ceil($mytotal2[0]['totalamount'] / (float)$amountperpage);
                $mytotal3 = array();
                $mytotal3['totalpage'] = $totalpage;
                $mytotal4 = array();
                $mytotal4[] = $mytotal3;
                if(!empty($mytemp2)) {$result = array_merge($result, $mytemp2);}
                if(!empty($mytotal4)) {$result = array_merge($result, $mytotal4);}
                return json_encode($result);
            }
        }
    }
}
