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

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $webhook = Constant::where('name', UserHelpers::getConstants('WEBHOOK_NAME'))
                ->get()->first();
            $result = array();
            if(!empty($webhook->description)) {
                $temp = json_decode($webhook->description, true);
                $result = array();
                foreach($temp as $k => $v) {
                    if($k === 'create' || $k === 'edit' || $k === 'review') {
                        $temp1 = explode('|', $v);
                        $data = array();
                        $counter = 0;
                        foreach($temp1 as $val) {
                            $data[] = $val;
                            $counter++;
                        }
                        $amount = intval(UserHelpers::getConstants('WEBHOOK_AMOUNT'));
                        if(($amount - $counter) > 0) {
                            for($i=0; $i < ($amount - $counter); $i++) {
                                $data[] = '';
                            }
                        }
                        $result[$k] = $data;
                    } else {
                        $result[$k] = $v;
                    }
                }
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
            $result['status'] = $webhook->status;
            $result['created'] = $webhook->created;    
            return view('webhook.index', ['webhook' => $result]);
        } else if ($request->isMethod('post')) {
			if(UserHelpers::isAdmin()) {
				$input = $request->all();   
				$webhook = Constant::where('name', UserHelpers::getConstants('WEBHOOK_NAME'))
					->get()->first();
				$status = '';
				if($webhook) {
					$webhook->status = $input["status"];
					if(empty($webhook->description)) {
						$webhook->created = Date('Y-m-d H:i:s');
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

					$webhook->description = '{"create":"' . $create . '","edit":"' . $edit . '","review":"' . $review . '"}';
					$webhook->save();
					$status = 'Saved';
				} else {
					$status = 'Record not found';
				}
			}
            return redirect('webhook')->with('status', $status);
        }
    }

}
