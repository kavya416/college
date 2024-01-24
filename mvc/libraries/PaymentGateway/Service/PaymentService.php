<?php

class PaymentService
{
    public $ci;
    public $transaction_id;
    public $invoice_info;
    public $data;

    public function __construct( $transaction_id )
    {
        $this->ci             =& get_instance();
        $this->transaction_id = $transaction_id;
        $this->ci->load->model('maininvoice_m');
        $this->ci->load->model('invoice_m');
        $this->ci->load->model('payment_m');
        $this->ci->load->model('studentrelation_m');
        $this->ci->load->model('globalpayment_m');
        $this->ci->load->model('weaverandfine_m');
        $this->ci->load->library("msg91");
    }

    public function add_transaction( $invoiceInfo = [] )
    { 
        $transaction = $this->ci->payment_m->get_single_payment(['transactionID' => $this->transaction_id]);
    
        if(empty($transaction)) {
            $global_payment_last_id     = 0;
            $school_year_id             = $this->ci->session->userdata('defaultschoolyearID');
            $main_invoice               = $this->ci->maininvoice_m->get_single_maininvoice(['maininvoiceID' => $invoiceInfo['main_invoice_id']]);
            $invoices                   = $this->ci->invoice_m->get_order_by_invoice([
                'maininvoiceID' => $invoiceInfo['main_invoice_id'],
                'deleted_at'    => 1
            ]);
            $student                    = $this->ci->studentrelation_m->get_single_studentrelation([
                'srstudentID'    => $main_invoice->maininvoicestudentID,
                'srschoolyearID' => $school_year_id
            ]);
            
            $student_info = $this->ci->studentrelation_m->general_get_single_student([
                'srstudentID'    => $main_invoice->maininvoicestudentID,
                'srschoolyearID' => $school_year_id
            ],TRUE);
            $invoice_payment_and_weaver = $this->payment_due($main_invoice, $school_year_id, $student->srstudentID);
          
            if(!empty($invoices)) {
                $global_payment = [
                    'classesID'          => $student->srclassesID,
                    'sectionID'          => $student->srsectionID,
                    'studentID'          => $main_invoice->maininvoicestudentID,
                    'clearancetype'      => 'partial',
                    'invoicename'        => $student->srregisterNO . '-' . $student->srname,
                    'invoicedescription' => '',
                    'paymentyear'        => date('Y'),
                    'schoolyearID'       => $school_year_id,
                ];

                $this->ci->globalpayment_m->insert_globalpayment($global_payment);
                $global_payment_last_id = $this->ci->db->insert_id();

                $due           = 0;
                $global_status = [];
                
                foreach($invoices as $invoice) {
                    if($invoice->paidstatus != 2) {
                        if(((float)$invoiceInfo['payment']['paidamount_' . $invoice->invoiceID] > (float)0) || ((float)$invoiceInfo['payment']['weaver_' . $invoice->invoiceID] > (float)0) || ((float)$invoiceInfo['payment']['fine_' . $invoice->invoiceID] > (float)0)) {
                            if(isset($invoice_payment_and_weaver['total_amount'][$invoice->invoiceID])) {
                                $due = (float)$invoice_payment_and_weaver['total_amount'][$invoice->invoiceID];

                                if(isset($invoice_payment_and_weaver['total_discount'][$invoice->invoiceID])) {
                                    $due = (float)($due - $invoice_payment_and_weaver['total_discount'][$invoice->invoiceID]);
                                }

                                if(isset($invoice_payment_and_weaver['total_payment'][$invoice->invoiceID])) {
                                    $due = (float)($due - $invoice_payment_and_weaver['total_payment'][$invoice->invoiceID]);
                                }

                                if(isset($invoice_payment_and_weaver['total_weaver'][$invoice->invoiceID])) {
                                    $due = (float)($due - $invoice_payment_and_weaver['total_weaver'][$invoice->invoiceID]);
                                }
                            }

                            $total_payment = 0;
                            if($invoiceInfo['payment']['paidamount_' . $invoice->invoiceID] > 0) {
                                $total_payment += (float)$invoiceInfo['payment']['paidamount_' . $invoice->invoiceID];
                            }

                            if($invoiceInfo['payment']['weaver_' . $invoice->invoiceID] > 0) {
                                $total_payment += (float)$invoiceInfo['payment']['weaver_' . $invoice->invoiceID];
                            }

                            $due           = number_format($due, 2, '.', '');
                            $total_payment = number_format($total_payment, 2, '.', '');

                            if($due <= $total_payment) {
                                $paid_status     = 2;
                                $global_status[] = TRUE;
                            } else {
                                $global_status[] = FALSE;
                                $paid_status     = 1;
                            }
                            $params = $this->ci->session->userdata('params');
                            $time = strtotime($invoiceInfo['payment']['date']);
                            $payment_array = [
                                'invoiceID'       => $invoice->invoiceID,
                                'schoolyearID'    => $school_year_id,
                                'studentID'       => $invoice->studentID,
                                'paymentamount'   => (($invoiceInfo['payment']['paidamount_' . $invoice->invoiceID] == '') ? NULL : $invoiceInfo['payment']['paidamount_' . $invoice->invoiceID]),
                                'paymenttype'     => ucfirst($invoiceInfo['payment_method']),
                                'paymentdate'     => date('Y-m-d',$time),
                                'paymentday'      => date('d', $time),
                                'paymentmonth'    => date('m', $time),
                                'paymentyear'     => date('Y', $time),
                                'userID'          => $this->ci->session->userdata('loginuserID'),
                                'usertypeID'      => $this->ci->session->userdata('usertypeID'),
                                'uname'           => $this->ci->session->userdata('name'),
                                'transactionID'   => $this->transaction_id,
                                'globalpaymentID' => $global_payment_last_id,
                                'comment' => isset($params['payment']['comment']) ? $params['payment']['comment'] : '',
                            ];
                            $this->ci->payment_m->insert_payment($payment_array);
                            $payment_last_id = $this->ci->db->insert_id();
                            $this->ci->invoice_m->update_invoice(['paidstatus' => $paid_status], $invoice->invoiceID);
                            if(((float)$invoiceInfo['payment']['weaver_' . $invoice->invoiceID] > (float)0) || ((float)$invoiceInfo['payment']['fine_' . $invoice->invoiceID] > (float)0)) {
                                $weaver_and_fine_array = [
                                    'globalpaymentID' => $global_payment_last_id,
                                    'invoiceID'       => $invoice->invoiceID,
                                    'paymentID'       => $payment_last_id,
                                    'studentID'       => $invoice->studentID,
                                    'schoolyearID'    => $school_year_id,
                                    'weaver'          => (($invoiceInfo['payment']['weaver_' . $invoice->invoiceID] == '') ? 0 : $invoiceInfo['payment']['weaver_' . $invoice->invoiceID]),
                                    'fine'            => (($invoiceInfo['payment']['fine_' . $invoice->invoiceID] == '') ? 0 : $invoiceInfo['payment']['fine_' . $invoice->invoiceID]),
                                ];
                                $this->ci->weaverandfine_m->insert_weaverandfine($weaver_and_fine_array);
                            }
                            $student->paidamount = $payment_array['paymentamount'];
                            $student->category = $invoice->feetype;
                            $student->phone = $student_info->phone;
                            $status = $this->userConfigSMS($student, $getway='msg91');
                            
                        }
                    }
                }

                if(in_array(FALSE, $global_status)) {
                    $this->ci->globalpayment_m->update_globalpayment(['clearancetype' => 'partial'], $global_payment_last_id);
                    $this->ci->maininvoice_m->update_maininvoice(['maininvoicestatus' => 1], $invoiceInfo['main_invoice_id']);
                } else {
                    $this->ci->globalpayment_m->update_globalpayment(['clearancetype' => 'paid'], $global_payment_last_id);
                    $this->ci->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2], $invoiceInfo['main_invoice_id']);
                }
                $this->ci->session->set_flashdata('success', 'Payment successful');

                return $global_payment_last_id;
            } else {
                $this->ci->session->set_flashdata('error', 'invoice not found');
            }
        } else {
            $this->ci->session->set_flashdata('error', 'Transaction ID already exist!');
        }
    }

    private function payment_due( $main_invoice, $school_year_id, $studentID = null )
    {
        $response = [];
        if(!empty($main_invoice)) {
            if((int)$studentID && $studentID != null) {
                $invoice_items         = pluck_multi_array_key($this->ci->invoice_m->get_order_by_invoice([
                    'studentID'     => $studentID,
                    'maininvoiceID' => $main_invoice->maininvoiceID,
                    'schoolyearID'  => $school_year_id
                ]), 'obj', 'maininvoiceID', 'invoiceID');
                $payment_items         = pluck_multi_array($this->ci->payment_m->get_order_by_payment([
                    'schoolyearID'     => $school_year_id,
                    'paymentamount !=' => null
                ]), 'obj', 'invoiceID');
                $weaver_and_fine_items = pluck_multi_array($this->ci->weaverandfine_m->get_order_by_weaverandfine(['schoolyearID' => $school_year_id]), 'obj', 'invoiceID');
            } else {
                $invoice_items         = [];
                $payment_items         = [];
                $weaver_and_fine_items = [];
            }

            if(isset($invoice_items[$main_invoice->maininvoiceID])) {
                if(!empty($invoice_items[$main_invoice->maininvoiceID])) {
                    foreach($invoice_items[$main_invoice->maininvoiceID] as $invoice_item) {
                        $amount = $invoice_item->amount;
                        if($invoice_item->discount > 0) {
                            //$amount = ($invoice_item->amount - (($invoice_item->amount / 100) * $invoice_item->discount));
                            $amount = ($invoice_item->amount - $invoice_item->discount);
                        }

                        if(isset($response['total_amount'][$invoice_item->invoiceID])) {
                            $response['total_amount'][$invoice_item->invoiceID] = ($response['total_amount'][$invoice_item->invoiceID] + $invoice_item->amount);
                        } else {
                            $response['total_amount'][$invoice_item->invoiceID] = $invoice_item->amount;
                        }

                        if(isset($response['total_discount'][$invoice_item->invoiceID])) {
                            // $response['total_discount'][$invoice_item->invoiceID] = ($response['total_discount'][$invoice_item->invoiceID] + (($invoice_item->amount / 100) * $invoice_item->discount));
                            $response['total_discount'][$invoice_item->invoiceID] = ($response['total_discount'][$invoice_item->invoiceID] + ($invoice_item->discount));
                        } else {
                            // $response['total_discount'][$invoice_item->invoiceID] = (($invoice_item->amount / 100) * $invoice_item->discount);
                            $response['total_discount'][$invoice_item->invoiceID] = ($invoice_item->discount);
                        }

                        if(isset($payment_items[$invoice_item->invoiceID])) {
                            if(!empty($payment_items[$invoice_item->invoiceID])) {
                                foreach($payment_items[$invoice_item->invoiceID] as $payment_item) {
                                    if(isset($response['total_payment'][$payment_item->invoiceID])) {
                                        $response['total_payment'][$payment_item->invoiceID] = ($response['total_payment'][$payment_item->invoiceID] + $payment_item->paymentamount);
                                    } else {
                                        $response['total_payment'][$payment_item->invoiceID] = $payment_item->paymentamount;
                                    }
                                }
                            }
                        }

                        if(isset($weaver_and_fine_items[$invoice_item->invoiceID])) {
                            if(!empty($weaver_and_fine_items[$invoice_item->invoiceID])) {
                                foreach($weaver_and_fine_items[$invoice_item->invoiceID] as $weaver_and_fine_item) {
                                    if(isset($response['total_weaver'][$weaver_and_fine_item->invoiceID])) {
                                        $response['total_weaver'][$weaver_and_fine_item->invoiceID] = ($response['total_weaver'][$weaver_and_fine_item->invoiceID] + $weaver_and_fine_item->weaver);
                                    } else {
                                        $response['total_weaver'][$weaver_and_fine_item->invoiceID] = $weaver_and_fine_item->weaver;
                                    }

                                    if(isset($response['total_fine'][$weaver_and_fine_item->invoiceID])) {
                                        $response['total_fine'][$weaver_and_fine_item->invoiceID] = ($response['total_fine'][$weaver_and_fine_item->invoiceID] + $weaver_and_fine_item->fine);
                                    } else {
                                        $response['total_fine'][$weaver_and_fine_item->invoiceID] = $weaver_and_fine_item->fine;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
    
    private function userConfigSMS($user, $getway='msg91') { 
	    $this->ci->load->model('mailandsmstemplate_m');
	    $this->ci->load->model('mailandsmstemplatetag_m');
        $cnt = $this->ci->db->query("select * from setting where fieldoption ='is_fee_sms' and value='1' ")->num_rows();
        // echo $cnt;die;
        if($cnt > 0){
	    $template_id = 0;
        $template = $this->ci->mailandsmstemplate_m->get_mailandsmstemplate(5);
        $template_id = $template->templ_id;
        $message = $template->template;
		if($user) {
			$userTags = $this->ci->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
			$message = $this->tagConvertor($userTags, $user, $message, 'SMS');
			if($user->phone) {
				$send = $this->allgetway_send_message($getway, $user->phone, $message, $template_id);
			} else {
				// $send = array('check' => TRUE);
				// return $send;
			}
		}
    }
	}
	
	private function tagConvertor($userTags, $user, $message, $sendType) {
		if(customCompute($userTags)) {
		    $this->ci->load->model('setting_m');
		    $this->data['setting'] = $this->ci->setting_m->get_setting();
		    $school_name = (isset($this->data['setting']->sname)) ? $this->data['setting']->sname : "";
			foreach ($userTags as $key => $userTag) {
				if($userTag->tagname == '{{paid_amount}}') {
					if($user->paidamount) {
						$message = str_replace('{{paid_amount}}', $user->paidamount, $message);
					} else {
						$message = str_replace('{{paid_amount}}', ' ', $message);
					}
				} elseif($userTag->tagname == '{{category}}') {
					if($user->category) {
						$message = str_replace('{{category}}', $user->category, $message);
					} else {
						$message = str_replace('{{category}}', ' ', $message);
					}
				}
				elseif($userTag->tagname == '{{school_name}}') {
					$message = str_replace("{{school_name}}", $school_name, $message);
				}
			}
		}
		return $message;
	}
	
	private function allgetway_send_message($getway, $to, $message, $template_id=0) {
		$res = $this->ci->msg91->send($to, $message, $template_id);
	}
}