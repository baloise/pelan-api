<?php

include_once '../../settings.php';
include_once '../../_config/headers.php';
include_once '../../_config/core.php';

$jwt = "";
if (setAuth($jwt, time() - 3600, $conf['env']['cookie'])) {
    returnSuccess($conf['env']['cookie']['prefix']);
}
