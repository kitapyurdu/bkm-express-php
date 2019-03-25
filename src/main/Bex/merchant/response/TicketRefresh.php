<?php
/**
 * TicketRefresh.
 */

namespace Bex\merchant\response;

class TicketRefresh
{
    private $id;
    private $path;
    private $token;

    /**
     * TicketRefresh constructor.
     *
     * @param $id
     * @param $path
     * @param $token
     */
    public function __construct($id, $path, $token)
    {
        $this->id = $id;
        $this->path = $path;
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
