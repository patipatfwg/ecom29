<?php
namespace App\Http\Controllers\EPOS;

use App\Http\Controllers\EPOS\Models\CustomerInformation;
use App\Http\Requests\InvoiceReplaceRequest;
use App\Repositories\PrintCounterRepository;
use App\Repositories\ReplaceInvoiceRepository;
use App\Http\Controllers\EPOS\Helper\NumberThai;
use App\Http\Controllers\TemplateController;
use App\Repositories\PermissionRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Services\Guzzle;
use Session;
use PDF;
use DateTime;
use stdClass;
use SimpleXMLElement;

class InvoicesController extends EPOSBaseController
{   
    protected $templateController;
  
    public function __construct(UsersRepository $usersRepository, PermissionRepository $permissionRepository, TemplateController $templateController,Guzzle $guzzle)
    {
        parent::__construct($usersRepository, $permissionRepository, $guzzle); 

        $this->templateController = $templateController;
        $this->_messages          = config('message');
        $this->env                = \App::environment();
    }

    public function search(Request $request) {

        // get query string for search filters
        $searchType  = $request->get('search_type', 'sale_order_number');
        $searchValue = $request->get('search_value', '');
        $order_no    = $request->get('order_no', ''); // for mapping master data
 
        $orderNumber = '';
        $returnorderNumber = '';
        $invoice_number = '';

        if ($searchType === 'sale_order_number') { 
            $orderNumber = $searchValue;
        }
        else if ($searchType === 'return_order_number') {
            $orderNumber = $searchValue;
        }
        else if ($searchType === 'invoice_number') {
            $invoice_number = $searchValue;
        }
 
        session([
            'searchType' => $searchType,
            'searchValue' => $searchValue
        ]);

        if ($searchType == 'sale_order_number'){ $err_msg ='Invalid Sale Order Number!'; }
        else if ($searchType == 'invoice_number'){ $err_msg = 'Invalid Invoice Number!'; }
        else { $err_msg = 'Invalid Return Order Number!'; }

        $invoices = [];
        $invoiceDataSet = ''; // for dataTable
        if ($orderNumber != '') {
            $invoices = $this->getInvoices($orderNumber,$searchType);

            if ($invoices == '404') {
                return redirect('epos/invoice')->with('msg', 'Connection error!');
            }

            if ($invoices) {
                $invoiceDataSet = $this->_turnArrayInvoiceDetailToDataSet($invoices,$order_no);
                if (empty($invoiceDataSet)) {
                    return redirect('epos/invoice')->with('msg', $err_msg)->withInput(Input::all());
                }

            } else {
                return redirect('epos/invoice')->with('msg', $err_msg)->withInput(Input::all());
            }
        }
        else if ($invoice_number != '') {
            $invoices = $this->getInvoices($invoice_number,$searchType);
            //$invoices = $this->getInvoicesDetail($invoice_number);
            if ($invoices == '404') {

                return redirect('epos/invoice')->with('msg', 'Connection error!');
            }
            if ($invoices == '500') {
                return redirect('epos/invoice')->with('msg', $err_msg)->withInput(Input::all());
            }

            if ($invoices) {
                $invoiceDataSet = $this->_turnArrayInvoiceDetailToDataSet($invoices,$order_no);
                if (empty($invoiceDataSet)) {
                    return redirect('epos/invoice')->with('msg', $err_msg)->withInput(Input::all());
                }
            } else {
                return redirect('epos/invoice')->with('msg', $err_msg)->withInput(Input::all());
            }
        }   

        return view('epos.invoice.index', [
            'order_number' => $orderNumber,
            'search_result' => $invoices,
            'invoice_dataset' => $invoiceDataSet
        ]);

    }

    private function _turnArrayInvoiceDetailToDataSet($arr,$order_no='')
    {
        $result = [];
        if (is_array($arr) && isset($arr[0])) {

            foreach ($arr[0] as $key => $invoice) {
                $order_no = ($order_no==='')? $invoice->OrderNo : $order_no;
                $check_invoice = false;
                // Check Duplicate for invoiceDetail
                if ($key > 0) {
                    foreach ($result as $i => $val) {
                        if (isset($invoice->ExtnNewInvoiceNumber) && ($invoice->ExtnNewInvoiceNumber != '')) {
                            if (!$check_invoice && ($val[1] == $invoice->ExtnNewInvoiceNumber)) {
                                $check_invoice = true;
                            }
                        } else {
                            if (!$check_invoice && ($val[1] == $invoice->InvoiceNo)) {
                                $check_invoice = true;
                            }
                        }
                    }
                }
        
                if (!$check_invoice) {

                    if(($invoice->InvoiceType == 'CREDIT_MEMO' || $invoice->InvoiceType == 'RETURN') && $invoice->ExtnStatus != 'Settled'){

                        // No.
                        $result[$key][] = sprintf('<span class="text-danger">%s</span>', $key + 1);

                        // Manage
                        $result[$key][] = '<a class="btnUpdate text-danger" href="javascript:void(0)" onclick="update(\''.$invoice->OrderInvoiceKey.'\', \''.$invoice->InvoiceNo.'\', \'' . $invoice->InvoiceType .'\', \'' . $invoice->SearchCriteria1 .'\', \'Settled\');"><strong>Confirm</strong></a>';

                      
                        // Order Number
                        $result[$key][] =  sprintf('<span class="text-danger">%s</span>', !empty($order_no)? $order_no : $invoice->OrderNo );
                        
                        // Invoice Number
                        $result[$key][] = !empty($invoice->ExtnNewInvoiceNumber)? sprintf('<span class="text-danger">%s</span>',$invoice->ExtnNewInvoiceNumber) : sprintf('<span class="text-danger">%s</span>',$invoice->ExtnMakroInvoiceNumber);

                        // Create Date
                        $result[$key][] = sprintf('<span class="text-danger">%s</span>', $invoice->CreateDate);

                        // Invoice Type
                        $result[$key][] = sprintf('<span class="text-danger">%s</span>', $this->getInvoiceType($invoice->InvoiceType));

                        // Total Amount
                        $result[$key][] = isset($invoice->TotalAmount)? sprintf('<span class="text-danger">%s</span>', $invoice->TotalAmount) : '<span class="text-danger">0</span>';

                        // Print Counter
                        $result[$key][] = isset($invoice->PrintCounter)? sprintf('<span class="text-danger">%s</span>', $invoice->PrintCounter) : '<span class="text-danger">0</span>';

                        // Original Invoice Number
                        $result[$key][] = !empty($invoice->ExtnNewInvoiceNumber)? sprintf('<span class="text-danger">%s</span>', $invoice->ExtnMakroInvoiceNumber) : '<span class="text-danger"></span>';

                        // Issue Date
                        $result[$key][] = isset($invoice->IssueDate)? sprintf('<span class="text-danger">%s</span>', $invoice->IssueDate) : '<span class="text-danger"></span>';
                        
                        // Settlement Date
                        $result[$key][] = '';
                           
                    }
                    else{

                        // No.
                        $result[$key][] = $key + 1;

                        // Manage
                        $result[$key][] = '<a href="/epos/invoice/'.$invoice->OrderInvoiceKey.'?order_no='.$order_no.'" invoiceNo="'.$invoice->InvoiceNo.'" OrderNo="'.$order_no.'">Preview</a>';

                        // Order Number
                        $result[$key][] = !empty($order_no)? $order_no : $invoice->OrderNo;

                        // Invoice Number
                        $result[$key][] = !empty($invoice->ExtnNewInvoiceNumber)? $invoice->ExtnNewInvoiceNumber : $invoice->ExtnMakroInvoiceNumber;

                        // Create Date
                        $result[$key][] = $invoice->CreateDate;

                        // Invoice Type
                        $result[$key][] = $this->getInvoiceType($invoice->InvoiceType);

                        // Total Amount
                        $result[$key][] = isset($invoice->TotalAmount)? $invoice->TotalAmount : '0';

                        // Print Counter
                        $result[$key][] = isset($invoice->PrintCounter)? $invoice->PrintCounter : '0';

                        // Original Invoice Number
                        $result[$key][] = !empty($invoice->ExtnNewInvoiceNumber)? $invoice->ExtnMakroInvoiceNumber : '';

                        // Issue Date
                        $result[$key][] = isset($invoice->IssueDate)? $invoice->IssueDate : '';

                        // Settlement Date
                        $result[$key][] = isset($invoice->ExtnSettlementDate)? $invoice->ExtnSettlementDate : '';

                    }
                }
            }
        }
        return $result;
    }

    public function shouldUseLongForm($order) {
        $buyer = $order->buyer;
        $cardType = isset($buyer->makro_card_type)?$buyer->makro_card_type:'';
        $vatAddress = isset($buyer->vat_address)?$buyer->vat_address:'';
        $vatRegistered = isset($buyer->vat_registered)?$buyer->vat_registered:'';
        $commercialCertNo = isset($buyer->commercial_cert_no)?$buyer->commercial_cert_no:'';
        
        // Green card always use short form
        if ($cardType === 'green') {
            return false;
        }
        // If buyer doesnot have vat address always use short form
        else if ($vatAddress === 'N') {
            return false;
        }
        // Not a Green card member and has vatAddress
        else {
            if($vatRegistered === 'Y') {
                return ($commercialCertNo === 'N') ? false : true;
            }
            // Always use long form if has vatAddress and not has vatRegistered
            else {
                return true;
            }
        }
    }
    //Generate Pdf when user click print,reprint and comfirm
    public function createPdf($data)
    {   
        $result        =  false;     
        $pdf_params    =  $this->templateController->getDataInvoice($data['order_invoice_key'], $data['order_number']);
        
        if (!empty($pdf_params)) {
            $invoice_code       =  $this->templateController->getInvoiceCode($data['invoice_type'], $pdf_params['datas']['invoices'][0]['format_type']);

            $dir_prefix         =  'EFR' . $invoice_code . '_';
            $date_time          =  (new DateTime())->format('Ymd');
            $config_path        =  config('api.makro_edoc');

            // create forder in /data/invoice/pdf/batch/(YYY-MM-DD)
            $path               =  $this->templateController->manageFolder($config_path, $dir_prefix . $date_time);

            // Format file name E-doc from E-doc (Exp. FR103E_20180830_801_801355800077_00270516270710.pdf)
            $file_name_pdf      =  $this->templateController->generateFileNamePdf($data, $pdf_params['datas'], $invoice_code);
            $file_name_pdf_path =  $path.$file_name_pdf;
            $pdf                =  PDF::loadView('pdf.invoice', $pdf_params['datas'])->save($file_name_pdf_path);
            $result             =  true                             ;     
        }
        return $result;
    }

    public function preview($invoice_number = '', Request $request)
    {
        if ($invoice_number == '') return abort(404);
        $order_number = $request->get('order_no', '');
        return $this->templateController->getInvoicePrint($invoice_number,$order_number);
    }

    public function pdf($invoice_number = '', $order_no = '' )
    {
        if ($invoice_number == '') return abort(404);

        $datas = $this->templateController->getDataInvoice($invoice_number,$order_no);
        if (!$datas['status']) return abort(404);

        $pdf = PDF::loadView('pdf.invoice', $datas['datas']);

        return $pdf->stream();
    }

    public function full_thai_printDate($print_date)
    {
        if ($print_date != '') {
            $d = explode("/", $print_date);
            if ($d[1] == "01") { $thai_month = 'มกราคม';}
            if ($d[1] == "02") { $thai_month = 'กุมภาพันธ์';}
            if ($d[1] == "03") { $thai_month = 'มีนาคม';}
            if ($d[1] == "04") { $thai_month = 'เมษายน';}
            if ($d[1] == "05") { $thai_month = 'พฤษภาคม';}
            if ($d[1] == "06") { $thai_month = 'มิถุนายน';}
            if ($d[1] == "07") { $thai_month = 'กรกฎาคม';}
            if ($d[1] == "08") { $thai_month = 'สิงหาคม';}
            if ($d[1] == "09") { $thai_month = 'กันยายน';}
            if ($d[1] == "10") { $thai_month = 'ตุลาคม';}
            if ($d[1] == "11") { $thai_month = 'พฤศจิกายน';}
            if ($d[1] == "12") { $thai_month = 'ธันวาคม';}
            $thai_year = $d[2] + 543;

            return $d[0] . ' ' . $thai_month . ' ' . $thai_year;
        } else {
            return '';
        }
    }

    public function calculateTotalPage($invoices, $deposit_summary, $complex_summary, $totalItem, $complex_discount_total, $total_complex_discountItem, $vatdeposit_items, $nondeposit_items) {
        $totalLine = false;
        $linePerPage = 10;
        $rowData = [];

        if ($invoices[0][0]->InvoiceType == 'SHIPMENT') {
            $total = $totalItem + (($complex_discount_total > 0) ? ($total_complex_discountItem + 2) : 0);
            // Line for Vat Code Summary
            $total += 4;
        } elseif ($invoices[0][0]->InvoiceType == 'INFO') {
            $total = 2;
            // Line for Vat Code Summary
            $total += 4;
        } elseif ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO') {
            $total = 2;
            // Line for Vat Code Summary
            $total += 4;
        } elseif ($invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced') {
            $total = $totalItem + (($complex_discount_total > 0) ? ($total_complex_discountItem + 2) : 0);
            // Line for Vat Code Summary
            $total += 4;
        }

        $totalLine['total'] = $total;
        $totalLine['page'] = (int)ceil(($total)/($linePerPage));
        $totalLine['fill'] = (int)($linePerPage * $totalLine['page']) - $totalLine['total'];
        $totalLine['linePerPage'] = $linePerPage;

        if ($invoices[0][0]->InvoiceType == 'SHIPMENT') {

            $line = 0;
            $idx_item = 1;
            foreach ($invoices[0] as $a => $b) {
                $totalLine['line'][$line] = $this->get_itemLine($line, $linePerPage, $b, $a, $deposit_summary, $idx_item);
                $line += 1;
                $idx_item += 1;
            }

            // Fill line to Full Display Page
            for ($fill = 0; $fill < $totalLine['fill']; $fill++) {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'ITEM');
                $line += 1;
            }

            if ($complex_discount_total != 0) {
                $complex_line = 0;
                foreach ($complex_summary as $a => $b) {
                    if ($complex_line == 0) {
                        $totalLine['line'][$line] = $this->get_complex_head($line, $linePerPage);
                        $line += 1;
                    }

                    $totalLine['line'][$line] = $this->get_complex($line, $linePerPage, $complex_line, $b);
                    $line += 1;
                    $complex_line += 1;

                    if ($total_complex_discountItem == $complex_line) {
                        $totalLine['line'][$line] = $this->get_complex_footer($line, $linePerPage, $deposit_summary);
                        $line += 1;
                    }
                }
            }

            $summary_line = 0;
            foreach ($deposit_summary as $a => $b) {
                if ($summary_line == 0) {
                    $totalLine['line'][$line] = $this->get_summary_head($line, $linePerPage);;
                    $line += 1;
                    $summary_line += 1;
                }
                $totalLine['line'][$line] = $this->get_summary($line, $linePerPage, $a, $b, $vatdeposit_items, $nondeposit_items, $deposit_summary, $invoices[0][0]->InvoiceType);
                $line += 1;
                $summary_line += 1;
            }

        }
        elseif ($invoices[0][0]->InvoiceType == 'INFO') {

            $line = 0;

            $idx_item = 1;
            foreach ($invoices[0] as $a => $b) {
                $totalLine['line'][$line] = $this->get_itemLine($line, $linePerPage, $b, $a, $deposit_summary, $idx_item);
                $idx_item += 1;
                $line += 1;
            }

            if ($deposit_summary['Deposit-Vat']['AMT_PRICE'] != 0) {
                $totalLine['line'][$line] = $this->get_deposit($line, $linePerPage, $deposit_summary);
                $line += 1;
            }
            if ($deposit_summary['Deposit-NonVat']['AMT_PRICE'] != 0) {
                $totalLine['line'][$line] = $this->get_nondeposit($line, $linePerPage, $deposit_summary);
                $line += 1;
                if ($deposit_summary['Deposit-Vat']['AMT_PRICE'] == 0) {
                    $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'INFO');
                    $line += 1;
                }
            } else {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'INFO');
                $line += 1;
            }

            // Fill line to Full Display Page
            for ($fill = 0; $fill < $totalLine['fill']; $fill++) {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'ITEM');
                $line += 1;
            }

            $summary_line = 0;
            foreach ($deposit_summary as $a => $b) {
                if ($summary_line == 0) {
                    $totalLine['line'][$line] = $this->get_summary_head($line, $linePerPage);
                    $line += 1;
                    $summary_line += 1;
                }
                $totalLine['line'][$line] = $this->get_summary($line, $linePerPage, $a, $b, 1, 1, $deposit_summary, $invoices[0][0]->InvoiceType);
                $line += 1;
                $summary_line += 1;
            }

        }
        elseif ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO') {

            $line = 0;

            $idx_item = 1;
            foreach ($invoices[0] as $a => $b) {
                $totalLine['line'][$line] = $this->get_itemLine($line, $linePerPage, $b, $a, $deposit_summary, $idx_item);
                $idx_item += 1;
                $line += 1;
            }
            
            if ($deposit_summary['Deposit-Vat']['AMT_PRICE'] != 0) {

                $totalLine['line'][$line] = $this->get_deposit($line, $linePerPage, $deposit_summary);
                $line += 1;
            }
            if ($deposit_summary['Deposit-NonVat']['AMT_PRICE'] != 0) {
                $totalLine['line'][$line] = $this->get_nondeposit($line, $linePerPage, $deposit_summary);
                $line += 1;
                if ($deposit_summary['Deposit-Vat']['AMT_PRICE'] == 0) {
                    $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'INFO');
                    $line += 1;
                }
            } else {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'INFO');
                $line += 1;
            }

            // Fill line to Full Display Page
            for ($fill = 0; $fill < $totalLine['fill']; $fill++) {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'ITEM');
                $line += 1;
            }

            $summary_line = 0;
            foreach ($deposit_summary as $a => $b) {
                if ($summary_line == 0) {
                    $totalLine['line'][$line] = $this->get_summary_head($line, $linePerPage);
                    $line += 1;
                    $summary_line += 1;
                }
                $totalLine['line'][$line] = $this->get_summary($line, $linePerPage, $a, $b, 1, 1, $deposit_summary, $invoices[0][0]->InvoiceType);
                $line += 1;
                $summary_line += 1;
            }

        }
        elseif ($invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced') {
            $line = 0;
            $idx_item = 1;
            foreach ($invoices[0] as $a => $b) {
                $totalLine['line'][$line] = $this->get_itemLine($line, $linePerPage, $b, $a, $deposit_summary, $idx_item);
                $line += 1;
                $idx_item += 1;
            }

            // Fill line to Full Display Page
            for ($fill = 0; $fill < $totalLine['fill']; $fill++) {
                $totalLine['line'][$line] = $this->get_fillLine($line, $linePerPage, 'ITEM');
                $line += 1;
            }

            if ($complex_discount_total != 0) {
                $complex_line = 0;
                foreach ($complex_summary as $a => $b) {
                    if ($complex_line == 0) {
                        $totalLine['line'][$line] = $this->get_complex_head($line, $linePerPage);
                        $line += 1;
                    }

                    $totalLine['line'][$line] = $this->get_complex($line, $linePerPage, $complex_line, $b);
                    $line += 1;
                    $complex_line += 1;

                    if ($total_complex_discountItem == $complex_line) {
                        $totalLine['line'][$line] = $this->get_complex_footer($line, $linePerPage, $deposit_summary);
                        $line += 1;
                    }
                }
            }

            $summary_line = 0;
            foreach ($deposit_summary as $a => $b) {
                if ($summary_line == 0) {
                    $totalLine['line'][$line] = $this->get_summary_head($line, $linePerPage);;
                    $line += 1;
                    $summary_line += 1;
                }
                $totalLine['line'][$line] = $this->get_summary($line, $linePerPage, $a, $b, $vatdeposit_items, $nondeposit_items, $deposit_summary, $invoices[0][0]->InvoiceType);
                $line += 1;
                $summary_line += 1;
            }
        }

        return $totalLine;
    }

    public function get_itemLine($line, $linePerPage, $invoice, $a, $deposit_summary, $idx_item) {

        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = isset($invoice->PrimeLineNo) ? $idx_item : '&nbsp;';
        $rowData['ID'] = isset($invoice->ItemID) ? $invoice->ItemID : '&nbsp;';
        $rowData['Name'] = isset($invoice->ItemName) ? $invoice->ItemName : '&nbsp;';
        $rowData['Quantity'] = isset($invoice->Quantity) ? number_format($invoice->Quantity, 0) : '&nbsp;';
        $rowData['Unit'] = isset($invoice->UnitOfMeasure) ? $invoice->UnitOfMeasure : '&nbsp;';
        $rowData['SellPrice'] = isset($invoice->SellingPrice) ? number_format($invoice->SellingPrice - $invoice->Simple_Discount_PerUnit, 2) : '&nbsp;';
//        $rowData['SellPrice'] = isset($invoice->SellingPrice) ? number_format($invoice->SellingPrice, 2) : '&nbsp;';
        if (isset($invoice->VatRate) && $invoice->VatRate == 0) {
            $rowData['VATCode'] = $deposit_summary['Deposit-NonVat']['VAT_CODE'];
        } elseif (isset($invoice->VatRate) && $invoice->VatRate > 0) {
            $rowData['VATCode'] = $deposit_summary['Deposit-Vat']['VAT_CODE'];
        } else {
            $rowData['VATCode'] = '&nbsp;';
        }
        $rowData['TotalPrice'] = isset($invoice->Quantity) ? number_format((($invoice->Quantity) * ($invoice->SellingPrice) - $invoice->Simple_Discount), 2) : '';
        $rowData['Type'] = 'ITEM';

        return $rowData;
    }

    public function get_deposit($line, $linePerPage, $deposit_summary) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = '1';
        $rowData['ID'] = '222221';
        $rowData['Name'] = 'Deposit - Vat';
        $rowData['Quantity'] = '1';
        $rowData['Unit'] = 'EACH';
        $rowData['SellPrice'] = number_format($deposit_summary['Deposit-Vat']['AMT_PRICE'], 2);
        $rowData['VATCode'] = $deposit_summary['Deposit-Vat']['VAT_CODE'];
        $rowData['TotalPrice'] = number_format($deposit_summary['Deposit-Vat']['AMT_PRICE'], 2);
        $rowData['Type'] = 'INFO';
        return $rowData;
    }

    public function get_nondeposit($line, $linePerPage, $deposit_summary) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        if ($deposit_summary['Deposit-Vat']['AMT_PRICE'] != 0) {
            $rowData['Items'] = '2';
        } else {
            $rowData['Items'] = '1';
        }
        $rowData['ID'] = '333333';
        $rowData['Name'] = 'Deposit - Non Vat';
        $rowData['Quantity'] = '1';
        $rowData['Unit'] = 'EACH';
        $rowData['SellPrice'] = number_format($deposit_summary['Deposit-NonVat']['AMT_PRICE'], 2);
        $rowData['VATCode'] = $deposit_summary['Deposit-NonVat']['VAT_CODE'];
        $rowData['TotalPrice'] = number_format($deposit_summary['Deposit-NonVat']['AMT_PRICE'], 2);
        $rowData['Type'] = 'INFO';
        return $rowData;

    }

    public function get_complex_head($line, $linePerPage) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = 'Discount Condition';
        $rowData['ID'] = '&nbsp;';
        $rowData['Name'] = '&nbsp;';
        $rowData['Quantity'] = '&nbsp;';
        $rowData['Unit'] = '&nbsp;';
        $rowData['SellPrice'] = '&nbsp;';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = 'COMPLEX';
        return $rowData;
    }

    public function get_complex($line, $linePerPage, $complex_line, $b) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = ($complex_line + 1);
        $rowData['ID'] = $b['COMPLEX_NAME'];
        $rowData['Name'] = '&nbsp;';
        $rowData['Quantity'] = '&nbsp;';
        $rowData['Unit'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($b['DISCOUNT'], 2);
        $rowData['SellPrice'] = '&nbsp;';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = 'COMPLEX';
        return $rowData;
    }

    public function get_complex_footer($line, $linePerPage, $deposit_summary) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = '&nbsp;';
        $rowData['ID'] = '&nbsp;รวมส่วนลด';
        $rowData['Name'] = '&nbsp;';
        $rowData['Quantity'] = '&nbsp;';
        $rowData['Unit'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($deposit_summary['Deposit']['AMT_COMPLEX'], 2);
        $rowData['SellPrice'] = '&nbsp;';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = 'COMPLEX';
        return $rowData;
    }

    public function get_fillLine($line, $linePerPage, $type) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = '&nbsp;';
        $rowData['ID'] = '&nbsp;';
        $rowData['Name'] = '&nbsp;';
        $rowData['Quantity'] = '&nbsp;';
        $rowData['Unit'] = '&nbsp;';
        $rowData['SellPrice'] = '&nbsp;';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = $type;
        return $rowData;
    }

    public function get_summary($line, $linePerPage, $a, $b, $vatdeposit_items, $nondeposit_items, $deposit_summary, $inv_type) {
        $rowData = false;
        if ($a == 'Deposit-NonVat' && $b['AMT_PRICE'] != 0) {
            $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
            $rowData['Items'] = '&nbsp;';
            if ($nondeposit_items == 0) {
                $rowData['ID'] = '&nbsp;';
            } else {
                $rowData['ID'] = $nondeposit_items;
            }
            $rowData['Name'] = $b['VAT_CODE'];
            $rowData['Quantity'] = number_format($b['AMT_EXCVAT'], 2);
            $rowData['Unit'] = '0.00';
            $rowData['SellPrice'] = number_format($b['AMT_PRICE'], 2);
            $rowData['VATCode'] = '&nbsp;';
            $rowData['TotalPrice'] = '&nbsp;';
            $rowData['Type'] = 'SUMMARY';
        }
        elseif ($a == 'Deposit-NonVat' && $b['AMT_PRICE'] == 0) {
                $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
                $rowData['Items'] = '&nbsp;';
                $rowData['ID'] = '0';
                $rowData['Name'] = Config::get('invoice.vat_code_1');
                $rowData['Quantity'] = '0.00';
                $rowData['Unit'] = '0.00';
                $rowData['SellPrice'] = '0.00';
                $rowData['VATCode'] = '&nbsp;';
                $rowData['TotalPrice'] = '&nbsp;';
                $rowData['Type'] = 'SUMMARY';
        }

        if ($a == 'Deposit-Vat' && $b['AMT_PRICE'] != 0) {
            $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
            $rowData['Items'] = '&nbsp;';
            if ($vatdeposit_items == 0) {
                $rowData['ID'] = '&nbsp;';
            } else {
                $rowData['ID'] = $vatdeposit_items;
            }
            $rowData['Name'] = $b['VAT_CODE'];
            $rowData['Quantity'] = number_format($b['AMT_EXCVAT'], 2);
            $rowData['Unit'] = number_format($b['AMT_VAT'], 2);
            $rowData['SellPrice'] = number_format($b['AMT_PRICE'], 2);
            $rowData['VATCode'] = '&nbsp;';
            $rowData['TotalPrice'] = '&nbsp;';
            $rowData['Type'] = 'SUMMARY';
        }
        elseif ($a == 'Deposit-Vat' && $b['AMT_PRICE'] == 0) {
                $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
                $rowData['Items'] = '&nbsp;';
                $rowData['ID'] = '0';
                $rowData['Name'] = Config::get('invoice.vat_code_2');
                $rowData['Quantity'] = '0.00';
                $rowData['Unit'] = '0.00';
                $rowData['SellPrice'] = '0.00';
                $rowData['VATCode'] = '&nbsp;';
                $rowData['TotalPrice'] = '&nbsp;';
                $rowData['Type'] = 'SUMMARY';
        }
        if ($a == 'Deposit' && $b['AMT_PRICE'] != 0) {
            $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
            $rowData['Items'] = '&nbsp;';
            $rowData['ID'] = '&nbsp;';
            $rowData['Name'] = 'รวม';
            $rowData['Quantity'] = number_format($b['AMT_EXCVAT'], 2);
            if (($deposit_summary['Deposit-Vat']['AMT_PRICE'] == 0) && $deposit_summary['Deposit-NonVat']['AMT_PRICE'] != 0) {
                $rowData['Unit'] = '0.00';
            } else {
                $rowData['Unit'] = number_format($b['AMT_VAT'], 2);
            }
            $rowData['SellPrice'] = number_format($b['AMT_PRICE'], 2);
            $rowData['VATCode'] = '&nbsp;';
            $rowData['TotalPrice'] = '&nbsp;';
            $rowData['Type'] = 'SUMMARY';
        }

        return $rowData;
    }

    public function get_summary_head($line, $linePerPage) {
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = '&nbsp;';
        $rowData['ID'] = 'จำนวนชิ้น';
        $rowData['Name'] = 'รหัส ภ.พ.';
        $rowData['Quantity'] = 'ราคาสินค้า';
        $rowData['Unit'] = 'ภาษี';
        $rowData['SellPrice'] = 'รวม';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = 'SUMMARY_HEAD';
        return $rowData;
    }

    public function get_LastLine($line, $linePerPage) {
        // Final line is Empty line
        $rowData['Page'] = (int)ceil(($line + 1) / $linePerPage);
        $rowData['Items'] = '&nbsp;';
        $rowData['ID'] = '&nbsp;';
        $rowData['Name'] = '&nbsp;';
        $rowData['Quantity'] = '&nbsp;';
        $rowData['Unit'] = '&nbsp;';
        $rowData['SellPrice'] = '&nbsp;';
        $rowData['VATCode'] = '&nbsp;';
        $rowData['TotalPrice'] = '&nbsp;';
        $rowData['Type'] = 'LASTLINE';
        return $rowData;
    }

    public function replace($order_invoice_key, $order_number, $replace_invoice_number, $payment_type, $invoice_type, $store_id) {

        $order = $this->getOrder($order_number);

        if ($order == '404') {
            return redirect()->back()->with('msg', 'Connection error!, please try again')->withInput();

        }
        if (empty($order)) {
            return redirect('/epos/invoice')->withErrors('data not found');
        }

        $customerInfo                = new CustomerInformation();
        $customerInfo->shopName      = isset($order->search_criteria2) ? $order->search_criteria2 : '';
        $customerInfo->branchId      = isset($order->branch) ? $order->branch : '';
        $customerInfo->taxId         = isset($order->data->tax_payer_id) ? $order->data->tax_payer_id : '';
        $customerInfo->phone         = isset($order->additionalAddress->phone) ? $order->additionalAddress->phone : '';
        $customerInfo->addressLine1  = isset($order->additionalAddress->addressLine1) ? $order->additionalAddress->addressLine1 : '';
        $customerInfo->province      = isset($order->additionalAddress->state) ? $order->additionalAddress->state : '';
        $customerInfo->districts     = isset($order->additionalAddress->city) ? $order->additionalAddress->city : '';
        $customerInfo->sub_districts = isset($order->additionalAddress->addressLine4) ? $order->additionalAddress->addressLine4 : '';
        $customerInfo->zipcode       = isset($order->additionalAddress->zipCode) ? $order->additionalAddress->zipCode : '';
        $customerInfo->email         = isset($order->additionalAddress->email) ? $order->additionalAddress->email : '';

        return view('epos.invoice.replace.index', [
            'order_number'           => $order_number,
            'order_invoice_key'      => $order_invoice_key,
            'replace_invoice_number' => $replace_invoice_number,
            'payment_type'           => $payment_type,
            'invoice_type'           => $invoice_type,
            'store_id'               => $store_id,
            'customerInfo'           => $customerInfo
        ]);
    }

    public function saveReplace(PrintCounterRepository $printcounterRepository, ReplaceInvoiceRepository $replaceInvoiceRepository, InvoiceReplaceRequest $formRequest) {

        $order_invoice_key           = $formRequest->get('order_invoice_key', '');
        $invoice_number              = $formRequest->get('invoice_number', '');
        $replace_invoice_number      = $formRequest->get('replace_invoice_number', '');
        $order_number                = $formRequest->get('order_number', '');
        $payment_type                = $formRequest->get('payment_type', '');
        $invoice_type                = $formRequest->get('invoice_type', '');
        $store_id                    = $formRequest->get('store_id', '');

        $customerInfo                = new CustomerInformation();
        $customerInfo->shopName      = $formRequest->get('shop_name', '');
        $customerInfo->taxId         = $formRequest->get('tax_id', '');
        $customerInfo->branchId      = $formRequest->get('branch_id', '');
        // $customerInfo->phone      = $formRequest->get('phone', '');
        $customerInfo->addressLine1  = $formRequest->get('address_line_1', '');
        $customerInfo->province      = $formRequest->get('province_text', '');
        $customerInfo->districts     = $formRequest->get('districts_text', '');
        $customerInfo->sub_districts = $formRequest->get('sub_districts_text', '');
        $customerInfo->zipcode       = $formRequest->get('zipcode', '');
        $customerInfo->email         = $formRequest->get('email', '');

        if ($order_invoice_key != '') {
            $invoices = $this->getInvoicesDetail($order_invoice_key);
        } else {
            $invoices = '500';
        }

        $invoice_number = isset($invoices[0][0]->InvoiceNo) ? $invoices[0][0]->InvoiceNo : '';

        $userId = \Session::get('userId');
        $userData = $this->usersRepository->getUsers(['id' => $userId]);
        $username = isset($userData['data'][0]['username']) ? $userData['data'][0]['username'] : '';
        if ($replace_invoice_number != '0') {
            $old_invoice = $replace_invoice_number;
        } else {
            $old_invoice = $invoice_number;
        }

        // Check Replace Type
        $replace_type = 'long_to_long';
        if (empty($formRequest->get('old_tax_id')) || $formRequest->get('old_tax_id') == '-') {
            if (!empty($customerInfo->taxId)) {
                $replace_type = 'short_to_long';
            }
        }

        // Order
        $orderData = $this->templateController->getOrderData($order_number);
        if(!$orderData){
            return redirect()->back()->with('msg', 'Empty Order Data!')->withInput();
        }
        // Order Invoice Amount Data
        $orderInvoiceAmountData = $this->templateController->getOrderInvoiceAmountData($invoice_number, $invoices, $orderData);

        if(!$orderInvoiceAmountData){
            return redirect()->back()->with('msg', 'Empty Order Invoice Data!')->withInput();
        }
        $mapping_invoice_type = $this->templateController->mappingInvoiceType($invoice_type);
        $summary = $this->templateController->getSummary($orderInvoiceAmountData, $mapping_invoice_type);

        $amount_detail = $this->getAmountDetail($order_number);

	    $param = array(
            'type'              => 'replace',
            'replace_type'      => $replace_type,
            'order_invoice_key' => $order_invoice_key,
            'old_info'          => array(
                'company_name'      => $formRequest->get('old_shop_name', ''),
                'tax_id'            => $formRequest->get('old_tax_id', ''),
                'branch_id'         => $formRequest->get('old_branch_id', ''),
                'address_line1'     => $formRequest->get('old_address_line_1', ''),
                'mobile_phone'      => $formRequest->get('old_phone', ''),
                'provinces'         => $formRequest->get('old_provinces', ''),
                'districts'         => $formRequest->get('old_districts', ''),
                'sub_districts'     => $formRequest->get('old_sub_districts', ''),
                'zip_code'          => $formRequest->get('old_zipcode', ''),
                'issue_date'        => '',
                'settlement_date'   => ''
	        ),
            'new_info'          => array(
                'company_name'      => $customerInfo->shopName,
                'tax_id'            => $customerInfo->taxId,
                'branch_id'         => $customerInfo->branchId,
                'address_line1'     => $customerInfo->addressLine1,
                // 'mobile_phone'      => $customerInfo->phone,
                'provinces'         => $customerInfo->province,
                'districts'         => $customerInfo->districts,
                'sub_districts'     => $customerInfo->sub_districts,
                'zip_code'          => $customerInfo->zipcode,
                'issue_date'        => '',
                'settlement_date'   => ''
            ),
            'invoice_no'        => $invoice_number,
            'old_invoice'       => $old_invoice,
            'new_invoice'       => '',
            'order_number'      => $order_number,
            'subtotal'          => isset($summary['sub-total']) ? $summary['sub-total'] : 0,
            'vat'               => isset($summary['vat']) ? $summary['vat'] : 0,
            'net_amount'        => ($mapping_invoice_type == 'credit-note-refund' || $mapping_invoice_type == 'credit-note-return') ? (isset($summary['diff-order-amount']) ? $summary['diff-order-amount'] : 0) : (isset($summary['net-amount']) ? $summary['net-amount'] : 0),
	        'amount_exc_vat'    => round($amount_detail['amountExcVat'],2),
            'amount_inc_vat'    => round($amount_detail['amountIncVat'],2),
            'selling_vat'       => round($amount_detail['sellingVat'],2),
            'issue_date'        => '',
            'settlement_date'   => '',
            'interday'          => false,
            'store_id'          => $store_id,
            'created_at'        => (new DateTime())->format('Y-m-d H:i:s'),
            'created_by'        => $username,
            'status'            => '',
            'message'           => '',
            'invoice_type'      => $invoice_type
        );

        if ($invoices == '404') {
            $param['status'] = 'Error';
            $param['message'] = 'Connection error!, please try again';

            $logInfo = $this->sendLogInfo($param);

            return redirect()->back()->withErrors('Connection error!, please try again')->withInput();
        } elseif ($invoices == '500') {
            $param['status'] = 'Error';
            $param['message'] = 'Can not get invoice!';

            $logInfo = $this->sendLogInfo($param);
            return redirect()->back()->withErrors('Can not get invoice!')->withInput();
        }

        $param['issue_date'] = convertDateTime($invoices[0][0]->IssueDate, 'd/m/Y', 'Y-m-d');
        $param['settlement_date'] = convertDateTime($invoices[0][0]->ExtnSettlementDate, 'd/m/Y', 'Y-m-d');
        $param['old_info']['issue_date'] = convertDateTime($invoices[0][0]->IssueDate, 'd/m/Y', 'Y-m-d');
        $param['old_info']['settlement_date'] = convertDateTime($invoices[0][0]->ExtnSettlementDate, 'd/m/Y', 'Y-m-d');

        $document_type = '0001';
        if ($invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced') {
            $document_type = '0003';
            $order_number = $invoices[0][0]->OrderNo;
        }

        // update Cutomer Information to OMS
        $result = $this->updateInvoice($order_number, $customerInfo, $document_type);

        if ($result == '404') {
            $param['status'] = 'Error';
            $param['message'] = '[Update Invoice] Connection error!, please try again';

            $logInfo = $this->sendLogInfo($param);
            return redirect()->back()->withErrors('[Update Invoice] Connection error!, please try again')->withInput();

        }


        
        if (!$result->error) {

            // Replace Invoice to OMS
            $result = $this->replaceInvoice($order_invoice_key);

            if ($result == '404') {
                $param['status'] = 'Error';
                $param['message'] = '[Replace Invoice] Connection error!, please try again';

                $logInfo = $this->sendLogInfo($param);
                return redirect()->back()->withErrors('[Replace Invoice] Connection error!, please try again')->withInput();

            }

            if (!$result->error) {

                $new_invoices = $this->getInvoicesDetail($order_invoice_key);
                $new_invoice_number = $new_invoices[0][0]->ExtnNewInvoiceNumber;

                $seq = $printcounterRepository->createPrintSequence($new_invoice_number,$order_invoice_key,0);

                //update Person Billing Information to Ms Order
                $updatePersonResult = $this->updatePersonBillingInfo($formRequest->get('order_number', ''), $customerInfo);

                if ($updatePersonResult == '404') {
                    $param['status'] = 'Error';
                    $param['message'] = '[Update Tax Info] Connection error!, please try again';

                    $logInfo = $this->sendLogInfo($param);
                    return redirect()->back()->withErrors('[Update Tax Info] Connection error!, please try again')->withInput();

                }

                if ($updatePersonResult) {

                    $newInvoiceNumber                     = isset($result->new_invoiceNo) ? $result->new_invoiceNo : '';

                    $param['new_invoice']                 = $newInvoiceNumber;
                    $param['status']                      = 'Success';
                    $param['message']                     = '';
                    $param['new_info']['issue_date']      = convertDateTime($result->issueDate, 'd/m/Y', 'Y-m-d');
                    $param['new_info']['settlement_date'] = convertDateTime($result->extnSettlementDate, 'd/m/Y', 'Y-m-d');

                    $logInfo = $this->sendLogInfo($param);

                    // Success
                    Session::flash('messages', [
                        'type' => 'success',
                        'text' => $this->_messages['database']['success'],
                    ]);

                    return redirect()->back();

                } else {
                    $param['status'] = 'Error';
                    $param['message'] = '[Update Tax Info] Internal wrong, please try again';

                    $logInfo = $this->sendLogInfo($param);

                    return redirect()->back()->withErrors('[Update Tax Info] Internal wrong, please try again')->withInput();
                }
            } else {
                $param['status'] = 'Error';
                $param['message'] = '[Replace Invoice] ' . $result->errorMessage;

                $logInfo = $this->sendLogInfo($param);
                return redirect()->back()->withErrors('[Replace Invoice] ' . $result->errorMessage)->withInput();
            }

        } else {
            $param['status'] = 'Error';
            $param['message'] = '[Update Invoice] ' . $result->errorMessage;

            $logInfo = $this->sendLogInfo($param);
            return redirect()->back()->withErrors('[Update Invoice] ' . $result->errorMessage)->withInput();
        }

    }

    public function printCounter(PrintCounterRepository $printcounterRepository, Request $request) {
        $invoiceNumber         = $request->get('invoiceNumber', '');
        $customerInvoiceNumber = $request->get('customerInvoiceNumber', '');
        $orderInvoiceKey       = $request->get('orderInvoiceKey', '');
        $paymentType           = $request->get('paymentType', '');
        $invoiceType           = $request->get('invoiceType', '');
        $invoiceDate           = $request->get('invoiceDate', '');
        $subtotal              = $request->get('subtotal', '');
        $vat                   = $request->get('vat', '');
        $netamount             = $request->get('netamount', '');
        $shipping_fee          = $request->get('shipping_fee', 0);
        $store_id              = $request->get('store_id', '');
        $order_number          = $request->get('order_number', '');
        $order_date            = $request->get('order_date', '');
        $payment_type          = $request->get('payment_type', '');
        $shop_name             = $request->get('shop_name', '');
        $makro_member_card     = $request->get('makro_member_card', '');
        $tax_id                = $request->get('tax_id', '');
        $branch_id             = $request->get('branch_id', '');
        $address_line1         = $request->get('address_line1', '');
        $mobile_phone          = $request->get('mobile_phone', '');
        $provinces             = $request->get('provinces', '');
        $districts             = $request->get('districts', '');
        $sub_districts         = $request->get('sub_districts', '');
        $zip_code              = $request->get('zip_code', '');

        // reject invoice type "SHIPMENT"
        if ($invoiceType == 'SHIPMENT') {
            return Response::json(['status' => false, 'messages' => 'reject invoice type "SHIPMENT"']);
        }

        // From OMS
        // EGM-1166
        //1. An order with payment type=PayAtStore, the Print counter of deposit tax invoice must be set to 1.
        //2. An order with payment type=CC, the Print counter of deposit tax invoice must be set to 0.

        // get print counter
        $printCounter = $printcounterRepository->getNextSequence($customerInvoiceNumber , $orderInvoiceKey , $paymentType, $invoiceType);
        $userId       = \Session::get('userId');
        $userData     = $this->usersRepository->getUsers(['id' => $userId]);

        $invoices        = $this->getInvoicesDetail($orderInvoiceKey);
        $issue_date      = $invoices[0][0]->IssueDate;
        $settlement_date = $invoices[0][0]->ExtnSettlementDate;

        $username = isset($userData['data'][0]['username'])? $userData['data'][0]['username'] : '';

        $amount_detail = $this->getAmountDetail($order_number);

        $param = array(
            'type'              => 'print',
            'order_invoice_key' => $orderInvoiceKey,
            //customerInvoiceNumber is current invoice number that display on invoice preview.
            'invoice_no'        => $customerInvoiceNumber,
            //'new_invoice'     => $replaceInvoiceNumber,
            'invoice_date'      => $invoiceDate,
            'issue_date'        => convertDateTime($issue_date, 'd/m/Y', 'Y-m-d'),
            'settlement_date'   => convertDateTime($settlement_date, 'd/m/Y', 'Y-m-d'),
            'running_number'    => $printCounter,
            'subtotal'          => $subtotal,
            'vat'               => $vat,
            'net_amount'        => $netamount,
            'amount_exc_vat'    => round($amount_detail['amountExcVat'],2),
            'amount_inc_vat'    => round($amount_detail['amountIncVat'],2),
            'selling_vat'       => round($amount_detail['sellingVat'],2),
            'reprint_date'      => (new DateTime())->format('Y-m-d H:i:s'),
            'store_id'          => $store_id,
            'tax_info'          => array(
                'shop_name'         => $shop_name,
                'makro_member_card' => $makro_member_card,
                'tax_id'            => $tax_id,
                'branch_id'         => $branch_id,
                'address_line1'     => $address_line1,
                'mobile_phone'      => $mobile_phone,
                'provinces'         => $provinces,
                'districts'         => $districts,
                'sub_districts'     => $sub_districts,
                'zip_code'          => $zip_code
            ),
            'order_number'      => $order_number,
            'order_date'        => $order_date,
            'payment_method'    => $payment_type,
            'created_at'        => (new DateTime())->format('Y-m-d H:i:s'),
            'created_by'        => $username,
            'invoice_type'      => $invoiceType,
            'status'            => '',
            'message'           => ''
        );

        if ($invoiceType != 'SHIPMENT') {
          
             // Create Pdf and send to E-doc
             if (isset($printCounter) && $printCounter == 1) {
                if (!$this->createPdf($param)) {
                    return Response::json(['status' => false, 'messages' => '[Create Pdf]']);
                }
            }
        }

        $invoices = $this->getInvoicesPrint($orderInvoiceKey, $printCounter);

        if ($invoices == '404') {
            $param['status'] = 'Error';
            $param['message'] = 'Connection error!, please try again';
            $logInfo = $this->sendLogInfo($param);
            if($request->ajax()){
                return Response::json(['status' => false, 'messages' => 'Connection error!, please try again']);
            } else {
                return redirect()->back()->withErrors('Connection error!, please try again')->withInput();
            }
        }

        if (!$invoices->error) {
            $param['status']  = 'Success';
            $param['message'] = 'Update print counter';
            $logInfo      = $this->sendLogInfo($param);

        } else {
            if ($paymentType == 'PayAtStore' && $invoiceType == 'INFO' && $printCounter == 2) {
                $decreaseCounter = $printCounter - 2;
            } else {
                $decreaseCounter = $printCounter - 1;
            }
            $seq              = $printcounterRepository->setPrintSeq($customerInvoiceNumber, $orderInvoiceKey,$decreaseCounter);
            $param['status']  = 'Error';
            $param['message'] = '[Reprint Invoice] ' . $invoices->errorMessage;
            $logInfo          = $this->sendLogInfo($param);
            return Response::json(['status' => false, 'messages' => '[Reprint Invoice] ' . $invoices->errorMessage]);
        }
        return Response::json(['status'=>true]);
    }

    //======================================//
    //========== Invoice generate ==========//
    //======================================//

    public function generate()
    {
        return view('epos.invoice.generate');
    }

    public function generateCode(Request $request)
    {
            if ($request->ajax()) {

                    $orderNumber = trim($request->get('orderNumber'));

                    $outputs = [
                            'status'  => false,
                            'title'   => 'Error Get Order',
                            'message' => 'No result ' . $orderNumber
                            ];

                    if (!empty($orderNumber)) {

                        $resOrder = $this->guzzle->curl('GET', $this->api['makro_order_api'] . 'orders/' . $orderNumber);

                        if (isset($resOrder['data']['records']) && !empty($resOrder['data']['records'])) {

                                    $outputs = [
                                            'status' => true,
                                            'orderNumber' => $orderNumber
                                            ];
                                }
            }

            return Response::json($outputs);
        }

        return view('epos.invoice.generate');
    }

    public function generateOms(Request $request)
    {

        if ($request->ajax()) {
            $orderNumber = trim($request->get('orderNumber'));
            $outputs = [
                'status'  => false,
                'title'   => 'Error Get Oms',
                'message' => 'No Invoices created for this Order'
            ];

            if (!empty($orderNumber)) {
                //Mock send to OMS
                $resOms  = $this->post($this->api['makro_epos_api'] . 'eai/order/invoicegeneration',
                    '<Order OrderNo="' . $orderNumber . '" EnterpriseCode="TH" xmlns="http://www.sterlingcommerce.com/documentation/YFS/GenerateInvoice/input" />');
                $content = $resOms->getBody()->getContents();

                if (!empty($content)) {
                    $dataXML = new SimpleXMLElement($content);
                    $errorCode = $dataXML->xpath('//NS1:Error/@ErrorCode');

                    if (empty($errorCode)) {
                        $outputs = [
                            'status' => true,
                            'orderNumber' => $orderNumber
                        ];
                    }
                }
            }
                    return Response::json($outputs);}return view('epos.invoice.generate');
    }

    //======================================//
    //======== End Invoice generate ========//
    //======================================//
}
