<?php

namespace Bex\merchant\token;

class Token
{
    private $shortId;
    private $path;
    private $token;

    /**
     * Token constructor.
     *
     * @param $shortId
     * @param $path
     * @param $token
     */
    public function __construct($shortId, $path, $token)
    {
        $this->shortId = $shortId;
        $this->path = $path;
        $this->token = $token;
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
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
