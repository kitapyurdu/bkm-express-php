<?php

namespace Bex\merchant\response;

use Bex\merchant\token\Token;

class MerchantLoginResponse
{
    private $code;
    private $call;
    private $description;
    private $message;
    private $result;
    private $parameters;
    private $shortId;
    private $path;
    private $token;
    private $connectionToken;

    /**
     * MerchantLoginResponse constructor.
     *
     * @param $code
     * @param $call
     * @param $description
     * @param $message
     * @param $result
     * @param $parameters
     * @param $shortId
     * @param $path
     * @param $token
     */
    public function __construct($code, $call, $description, $message, $result, $parameters, $shortId, $path, $token)
    {
        $this->code = $code;
        $this->call = $call;
        $this->description = $description;
        $this->message = $message;
        $this->result = $result;
        $this->parameters = $parameters;
        $token = new Token($shortId, $path, $token);
        $this->shortId = $token->getShortId();
        $this->path = $token->getPath();
        $this->token = $token;
        $this->connectionToken = $token->getToken();
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
    public function getShortId()
    {
        return $this->shortId;
    }

    /**
     * @param mixed $shortId
     */
    public function setShortId($shortId)
    {
        $this->shortId = $shortId;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param Token $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getConnectionToken()
    {
        return $this->connectionToken;
    }

    /**
     * @param mixed $connectionToken
     */
    public function setConnectionToken($connectionToken)
    {
        $this->connectionToken = $connectionToken;
    }
}
