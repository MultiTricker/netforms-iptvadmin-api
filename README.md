# netforms-iptvadmin-api

Basic PHP class to use iptvadmin API (part of NETFORMS IPTV solution, http://4network.tv/). Boilerplate for your own code.

## Usage

```php
<?php

  require_once "iptvadminAPI.php";

  $iptvadmin = new \NETFORMS\iptvadmin("http://iptvadmin.somewhere.cz/", "user", "password");
  $iptvadmin->setPrintResponse(1);

  $iptvadmin->registerUser("ID1", "MultiTricker", "hesl", "Michal", "Ševčík", "michal@rete.cz",
                           "123456789", "10", "Rozšířená");

  $iptvadmin->removeUser("ID1");

  $iptvadmin->modifyUser("ID1", "MultiTricker", "hesl", "Michal", "Ševčík", "michal@rete.cz",
                         "123456789", "10");

  $iptvadmin->getUser("ID1");

  $iptvadmin->getUsers();

  $iptvadmin->getUserDevices("ID1");

  $iptvadmin->removeUserDevice("ID1", "DEVICEID");

  $iptvadmin->getPackages();

  $iptvadmin->activateUserPackage("ID1", "SPORT");

  $iptvadmin->deactivateUserPackage("ID1", "SPORT");

  $iptvadmin->addUserSTB("ID1", "12:34:56:78:90:12", "STB name");
```