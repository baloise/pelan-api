<?php

include_once '../../_config/settings.php';
include_once '../../_config/headers.php';
include_once '../../_config/core.php';

$jwt = "";
if (setAuth($jwt, time() - 3600, $api_conf['cookie'])) {
    returnSuccess("logout successful");
}
