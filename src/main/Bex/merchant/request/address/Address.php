<?php

namespace Bex\merchant\request\address;

class Address
{
    private $addressType;
    private $name;
    private $surname;
    private $companyName;
    private $address;
    private $telephone;
    private $taxNumber;
    private $taxOffice;
    private $county;
    private $city;
    private $tckn;
    private $email;

    public function __construct($address)
    {
        if (null == $address) {
            return $this;
        }
        $this->addressType = $address['addressType'];
        $this->name = $address['name'];
        $this->surname = $address['surname'];
        $this->companyName = $address['companyName'];
        $this->address = $address['address'];
        $this->telephone = $address['telephone'];
        $this->taxNumber = $address['taxNumber'];
        $this->taxOffice = $address['taxOffice'];
        $this->county = $address['county'];
        $this->city = $address['city'];
        $this->tckn = $address['tckn'];
        $this->email = $address['email'];
    }

    /**
     * @return mixed
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * @param mixed $addressType
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    /**
     * @param mixed $taxNumber
     */
    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return mixed
     */
    public function getTaxOffice()
    {
        return $this->taxOffice;
    }

    /**
     * @param mixed $taxOffice
     */
    public function setTaxOffice($taxOffice)
    {
        $this->taxOffice = $taxOffice;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getTckn()
    {
        return $this->tckn;
    }

    /**
     * @param mixed $tckn
     */
    public function setTckn($tckn)
    {
        $this->tckn = $tckn;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
