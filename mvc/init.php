<?php

define('DIR_PATH', plugin_dir_path(__DIR__));
define('DIR_URL', plugin_dir_url(__DIR__));

require_once __DIR__."/middlewares/BaseMiddleware.php";
require_once __DIR__."/models/BaseModel.php";
require_once __DIR__."/models/DBVersion.php";
require_once __DIR__."/models/init.php";
require_once __DIR__."/helpers/helper.php";
require_once __DIR__."/helpers/basetable.php";
require_once __DIR__."/helpers/baseform.php";
require_once __DIR__."/routes/baseroute.php";
require_once __DIR__."/routes/ajaxroute.php";
require_once __DIR__."/controllers/viewcontroller.php";
require_once __DIR__."/controllers/ajaxcontroller.php";


require_once __DIR__."/middlewares/AddAjaxMiddleware.php";
require_once __DIR__."/middlewares/AddViewMiddleware.php";
require_once __DIR__."/middlewares/DefViewMiddleware.php";
require_once __DIR__."/middlewares/DelAjaxMiddleware.php";
require_once __DIR__."/middlewares/EditAjaxMiddleware.php";
require_once __DIR__."/middlewares/EditViewMiddleware.php";