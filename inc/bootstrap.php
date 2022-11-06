<?php

define("PROJECT_ROOT_PATH", dirname(__DIR__));

// include main configuration file
require_once PROJECT_ROOT_PATH."/inc/config.php";

// include the base controller file
require_once PROJECT_ROOT_PATH."/Controller/BaseController.php";

// include the use model file
require_once PROJECT_ROOT_PATH."/Model/UserModel.php";

// include the jwt service file
require_once PROJECT_ROOT_PATH."/Service/JwtService.php";