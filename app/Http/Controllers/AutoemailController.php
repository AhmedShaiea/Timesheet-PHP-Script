<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Typecategory;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\Constant;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers\UserHelpers;
use Config;
use Log;

class AutoemailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $autoemail = Constant::where('name', UserHelpers::getConstants('AUTOEMAIL_NAME'))
                ->get()->first();
            $result = array();
            if(!empty($autoemail->description)) {
                $temp = json_decode($autoemail->description, true);
                foreach($temp as $k => $v) {
                    if($k === 'create' || $k === 'edit' || $k === 'review') {
                        $temp1 = explode('|', $v);
                        $data = array();
                        foreach($temp1 as $val) {
                            $data[] = $val;
                        }
                        $result[$k] = $data;
                    } else {
                        $result[$k] = $v;
                    }
                }
                $result['status'] = $autoemail->status;
                $result['created'] = $autoemail->created;
            } else {
                $data = array();
                $amount = intval(UserHelpers::getConstants('WEBHOOK_AMOUNT'));
                for($i=0; $i < $amount; $i++) {
                    $data[] = '';
                }
                $result['create'] = $data;
				$result['edit'] = $data;
                $result['review'] = $data;
            }
            return view('autoemail.index', ['autoemail' => $result]);
        } else if ($request->isMethod('post')) {
			if(UserHelpers::isAdmin()) {
				$input = $request->all();   
				$autoemail = Constant::where('name', UserHelpers::getConstants('AUTOEMAIL_NAME'))
					->get()->first();
				$status = '';
				if($autoemail) {
					$autoemail->status = $input["status"];
					if(empty($autoemail->description)) {
						$autoemail->created = Date('Y-m-d H:i:s');
					}
					$create = "";
					if(isset($_POST['create'])) {
						foreach ($_POST['create'] as $v) {
							$create .= "|" . trim($v);
						}
						$create = substr($create, 1);
					}

					$edit = "";
					if(isset($_POST['edit'])) {
						foreach ($_POST['edit'] as $v) {
							$edit .= "|" . trim($v);
						}
						$edit = substr($edit, 1);
					}
					
					$review = "";
					if(isset($_POST['review'])) {
						foreach ($_POST['review'] as $v) {
							$review .= "|" . trim($v);
						}
						$review = substr($review, 1);
					}

					$autoemail->description = '{"create":"' . $create . '","edit":"' . $edit . '","review":"' . $review . '"}';
					$autoemail->save();
					$status = 'Saved';
				} else {
					$status = 'Record not found';
				}
			}
            return redirect('autoemail')->with('status', $status);
        }
    }
}
