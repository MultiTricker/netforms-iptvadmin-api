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

      // Drop empty parameters
      foreach($parameters AS $key => $value)
      {
        if($value == "")
        {
          unset($parameters[$key]);
        }
      }

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
     * @param string $userLogin
     * @param string $userPassword
     * @param string $firstName
     * @param string $familyName
     * @param string $email
     * @param string $phone
     * @param int $maxDeviceCount
     * @param int|string $package
     * @param int|string $timelimit
     * @return string JSON
     */
    public function registerUser($partnerid, $userLogin, $userPassword, $firstName, $familyName, $email = "", $phone = "", $maxDeviceCount = 5, $package = "", $timelimit = "")
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
     * @param string $packageoutput input values are: id,full,isOtt
     * @return string JSON
     */
    public function getUser($partnerid, $packageoutput = "")
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
     * @param string $packageoutput input values are: id,full,isOtt
     * @return string JSON
     */
    public function getUserDevices($partnerid, $packageoutput = "")
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
     * @param string $packageoutput input values are: id,full,isOtt
     * @return string JSON
     */
    public function getPackages($packageoutput = "")
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
     * @param string $mac
     * @param string $name
     * @return string JSON
     * @throws \Exception
     */
    public function addUserStb($partnerid, $mac, $name = "")
    {

      $mac = $this->formatMac($mac);

      if(!$this->isMac($mac))
      {

        throw new \Exception("Invalid MAC address format. Should be 00:00:00:00:00:00, is ".$mac);

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

    /**
     * @param $partnerid
     * @return string JSON
     */
    public function disableUser($partnerid)
    {

      $request = $this->generateApiCall("disable-user", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $mac
     * @param string $type soft (default) or hard
     * @return string JSON
     */
    public function restartStb($mac, $type = "")
    {

      $request = $this->generateApiCall("restart-stb", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @return string JSON
     */
    public function getChannels()
    {

      $request = $this->generateApiCall("get-channels", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * Use returned token to redirect user on portal:
     * https://www.urlportalu.cz/autologin/TOKEN/
     *
     * @param string $partnerid
     * @return string JSON
     */
    public function getAutologinToken($partnerid)
    {

      $request = $this->generateApiCall("get-channels", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $package
     * @return string JSON
     */
    public function getPackage($package)
    {

      $request = $this->generateApiCall("get-package", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $package
     * $param int $ott 0/1
     * @return string JSON
     */
    public function addPackage($package, $ott)
    {

      $request = $this->generateApiCall("add-package", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $package
     * @return string JSON
     */
    public function removePackage($package)
    {

      $request = $this->generateApiCall("remove-package", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $package
     * @param string $onstb 0/1
     * @param string $onss 0/1
     * @param int $channelid
     * @param int $position
     * @return string JSON
     */
    public function addPackageChannel($package, $onstb, $onss, $channelid, $position)
    {

      $request = $this->generateApiCall("add-package-channel", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $package
     * @param $int $channelid
     * @return string JSON
     */
    public function removePackageChannel($package, $channelid)
    {

      $request = $this->generateApiCall("remove-package-channel", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    /**
     * @param string $partnerid
     * @return string JSON
     */
    public function generatePairCode($partnerid)
    {

      $request = $this->generateApiCall("generate-pair-code", get_defined_vars());
      $response = $this->callApi($request);
      return $response;

    }

    function __destruct()
    {
      return;
    }

  }