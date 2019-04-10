<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use \MongoDB\Operation\FindOneAndUpdate;

class PrintCounterRepository extends BaseRepository
{
 
    public function createPrintSequence($invoice_number, $orderInvoiceKey, $seq) {

        $result = DB::table('printcounter')->insert(
            ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number,  "seq" => (int)$seq ]
        );

        return $result;
    }

    public function getNextSequence($invoice_number,$orderInvoiceKey,$payment_type,$invoice_type)
    {
        // From OMS
        // EGM-1166
        //1. An order with payment type=PayAtStore, the Print counter of deposit tax invoice must be set to 1.
        //2. An order with payment type=CC, the Print counter of deposit tax invoice must be set to 0.
        //EGM-1904
        //3.  Print counter of normal tax invoice must be set to 0.
        if ($payment_type == 'PayAtStore' && $invoice_type == 'INFO') {
            $seq = DB::getCollection('printcounter')->findOneAndUpdate( 
                ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number],
                ['$inc' => ['seq' => 1]],
                ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
            );
            if ($seq->seq == 1) {
                $seq = DB::getCollection('printcounter')->findOneAndUpdate(
                ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number],
                ['$inc' => ['seq' => 1]],
                ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
                );
            }
        } else {
            if ($invoice_type == 'SHIPMENT') {
                $seq = DB::getCollection('printcounter')->findOneAndUpdate(
                    ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number],
                    ['$inc' => ['seq' => 0]],
                    ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
                );
            }else {
                $seq = DB::getCollection('printcounter')->findOneAndUpdate(
                    ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number],
                    ['$inc' => ['seq' => 1]],
                    ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
                );
            } 
        }
        return $seq->seq;
    }

    public function setPrintSeq($invoice_number, $orderInvoiceKey, $seq) {

        $result = DB::getCollection('printcounter')->findOneAndUpdate(
                ['orderInvoiceKey' => $orderInvoiceKey, 'invoice_number' => $invoice_number],
                ['$set' => ['seq' => (int)$seq]],
                ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
            );
 
        return $result->seq;
    }
}