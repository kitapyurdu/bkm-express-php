<?php

namespace Bex\merchant\response;

class TicketResponse
{
    private $code;
    private $call;
    private $description;
    private $message;
    private $result;
    private $parameters;
    private $ticketShortId;
    private $ticketPath;
    private $ticketToken;

    /**
     * TicketResponse constructor.
     *
     * @param $code
     * @param $call
     * @param $description
     * @param $message
     * @param $result
     * @param $parameters
     * @param $ticketShortId
     * @param $ticketPath
     * @param $ticketToken
     */
    public function __construct($code, $call, $description, $message, $result, $parameters, $ticketShortId, $ticketPath, $ticketToken)
    {
        $this->code = $code;
        $this->call = $call;
        $this->description = $description;
        $this->message = $message;
        $this->result = $result;
        $this->parameters = $parameters;
        $this->ticketShortId = $ticketShortId;
        $this->ticketPath = $ticketPath;
        $this->ticketToken = $ticketToken;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @param mixed $call
     */
    public function setCall($call)
    {
        $this->call = $call;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getTicketShortId()
    {
        return $this->ticketShortId;
    }

    /**
     * @param mixed $ticketShortId
     */
    public function setTicketShortId($ticketShortId)
    {
        $this->ticketShortId = $ticketShortId;
    }

    /**
     * @return mixed
     */
    public function getTicketPath()
    {
        return $this->ticketPath;
    }

    /**
     * @param mixed $ticketPath
     */
    public function setTicketPath($ticketPath)
    {
        $this->ticketPath = $ticketPath;
    }

    /**
     * @return mixed
     */
    public function getTicketToken()
    {
        return $this->ticketToken;
    }

    /**
     * @param mixed $ticketToken
     */
    public function setTicketToken($ticketToken)
    {
        $this->ticketToken = $ticketToken;
    }
}
