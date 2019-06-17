<?php

namespace Bex\merchant\request\transactions;

class TransactionDetailRequest
{
    public $id;

    public $signature;

    public $ticketId;

    public $orderId;

    public function __construct($id, $ticketId, $orderId)
    {
        $this->id = $id;
        $this->ticketId = $ticketId;
        $this->orderId = (string) $orderId;
    }
}
