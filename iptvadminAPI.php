<?php

  // Basic class for IPTVADMIN API

  namespace NETFORMS;

  /**
   * Class iptvadmin
   * @package NETFORMS
   */
  class iptvadmin
  {

    private $URL;
    private $userLogin;
    private $userPassword;
    private $printResponse;

    /**
     * @param $URL
     * @param string $userLogin
     * @param string $userPassword
     * @param int $printResponse
     */
    function __construct($URL, $userLogin, $userPassword = "", $printResponse = 0)
    {

      $this->setUrl($URL);
      $this->setLogin($userLogin);
      $this->setPassword($userPassword);
      $this->setPrintResponse($printResponse);

      return;

    }

    /**
     * @param mixed $URL
     * @return $this
     */
    public function setUrl($URL)
    {

      if(substr($URL, -1) != "/")
      {
        $URL .= "/";
      }

      $this->URL = $URL;

      return $this;
    }

    /**
     * @param mixed $userLogin
     * @return $this
     */
    public function setLogin($userLogin)
    {
      $this->userLogin = $userLogin;
      return $this;
    }

    /**
     * @param mixed $userPassword
     * @return $this
     */
    public function setPassword($userPassword)
    {
      $this->userPassword = $userPassword;
      return $this;
    }

    /**
     * @param mixed $printResponse
     */
    public function setPrintResponse($printResponse)
    {
      $this->printResponse = $printResponse;
    }

    /**
     * @return string JSON
     */
    public function getUrl()
    {
      return $this->URL;
    }

    /**
     * @return string JSON
     */
    public function getLogin()
    {
      return $this->userLogin;
    }

    /**
     * @return string JSON
     */
    public function getPassword()
    {
      return $this->userPassword;
    }

    /**
     * @return mixed
     */
    public function getPrintResponse()
    {
      return $this->printResponse;
    }

    /**
     * @param string $action
     * @param array $parameters
     * @return string JSON
     */
    public function generateApiCall($action, $parameters)
    {

      // Add login
      $parameters['login'] = $this->getLogin();
      $parameters['password'] = $this->getPassword();

      return $this->getUrl() . $action . "?" . http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);

    }

    /**
     * @param $request
     * @return string JSON
     */
    public function callApi($request)
    {

      // INIT
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $request);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

      // No SSL check
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      // Do it
      $response = curl_exec($ch);
      echo curl_error($ch);
      curl_close($ch);
      unset($ch);

      if($this->getPrintResponse() == 1)
      {
        echo $response;
      }

      return $response;

    }

    /**
     * @param $MAC
     * @return bool
     */
    public function isMac($MAC)
    {

      if(preg_match("/([a-fA-F0-9]{2}[:|\-]?){6}/", $MAC))
      {
        return true;
      }
      else
      {
        return false;
      }

    }

    /**
     * @param $MAC
     * @return string
     */
    function formatMac($MAC)
    {

      return trim(str_replace(["-", ".", ",", "::", ";"], ":", $MAC));

    }

    /**
     * @param string $partnerid
     * @param string $firstName
     * @param string $familyName
     * @param string $email
     * @param string $phone
     * @param int $maxDeviceCount
     * @param string $package
     * @return string JSON
     */
    public function registerUser($partnerid, $userLogin, $userPassword, $firstName, $familyName, $email = "", $phone = "", $maxDeviceCount = 5, $package)
    {

      $request = $this->generateApiCall("registeruser", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @return string JSON
     */
    public function removeUser($partnerid)
    {

      $request = $this->generateApiCall("remove-user", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @param string $userLogin
     * @param string $userPassword
     * @param string $firstName
     * @param string $familyName
     * @param string $email
     * @param string $phone
     * @param int $maxDeviceCount
     * @return string JSON
     */
    public function modifyUser($partnerid, $userLogin, $userPassword, $firstName, $familyName, $email = "", $phone = "", $maxDeviceCount = 5)
    {

      $request = $this->generateApiCall("modifyuser", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @return string JSON
     */
    public function getUser($partnerid)
    {

      $request = $this->generateApiCall("getuser", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @return string JSON
     */
    public function getUsers()
    {

      $request = $this->generateApiCall("getusers", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @return string JSON
     */
    public function getUserDevices($partnerid)
    {

      $request = $this->generateApiCall("get-user-devices", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @param int $deviceid
     * @return string JSON
     */
    public function removeUserDevice($partnerid, $deviceid)
    {

      $request = $this->generateApiCall("remove-user-device", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @return string JSON
     */
    public function getPackages()
    {

      $request = $this->generateApiCall("getpackages", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @param string $package
     * @return string JSON
     */
    public function activateUserPackage($partnerid, $package)
    {

      $request = $this->generateApiCall("activate-user", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @param string $package
     * @return string JSON
     */
    public function deactivateUserPackage($partnerid, $package)
    {

      $request = $this->generateApiCall("deactivate-user", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @param string $MAC
     * @param string $name
     * @return string JSON
     * @throws \Exception
     */
    public function addUserStb($partnerid, $MAC, $name = "")
    {

      $MAC = $this->formatMac($MAC);

      if(!$this->isMac($MAC))
      {

        throw new \Exception("Invalid MAC address format. Should be 00:00:00:00:00:00, is ".$MAC);

      }
      else
      {

        $request = $this->generateApiCall("add-user-stb", get_defined_vars());
        $response = $this->callApi($request);
        return $response;

      }

    }

    /**
     * @param $partnerid
     * @param $timelimit
     * @return string JSON
     */
    public function setPvrTimelimit($partnerid, $timelimit)
    {

      $request = $this->generateApiCall("set-pvr-timelimit", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    function __destruct()
    {
      return;
    }

  }