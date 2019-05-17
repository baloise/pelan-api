<?php

$environments = array(
    "prod" => array(
        "cors" => "https://pelan.osis.io",
        "auth" => "credentials"
    ),
    "test" => array(
        "cors" => "http://localhost:8080",
        "auth" => "medusa_fake"
    ),
    "medusa" => array(
        "cors" => "",
        "auth" => "medusa"
    ),
    "medusa_test" => array(
        "cors" => "",
        "auth" => "medusa_fake"
    ),
    "demo" => array(
        "cors" => "https://pelan-demo.osis.io",
        "auth" => "demo"
    )
);

$conf = array(
    "env" => $environments['test'],
    "db" => array(
        "host" => "localhost",
        "database" => "pelan",
        "user" => "root",
        "pass" => ""
    ),
    "cookie" => array(
        "domain" => "", //IE11 doesn't like this
        "secure" => false //Set TRUE if HTTPS
    ),
    "token" => array(
        "secret" => 'asdffae@hjk4352[bnbnmv]lkjhgfr:334', //Change for PROD!
        "algorithm" => array('HS256'),
        "issuer" => 'Pelan Application',
        "issuedAt" => time(),
        "notBefore" => time(),
        "expireAt" => time() + (3*60*60) // 3*60*60=3H; 15*60=15Min
    )
);