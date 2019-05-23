<?php

$environments = array(
    "prod" => array(
        "cors" => "https://pelan.osis.io",
        "auth" => "credentials",
        "cookie"=> array(
            "secure"=>false,
            "domain"=>"osis.io",
            "prefix"=>"pelProCoo"
        )
    ),
    "test" => array(
        "cors" => "http://localhost:8080",
        "auth" => "credentials",
        "cookie"=> array(
            "secure"=>false,
            "domain"=>"",
            "prefix"=>"lhPelanC"
        )
    ),
    "medusa" => array(
        "cors" => "",
        "auth" => "medusa",
        "cookie"=> array(
            "secure"=>false,
            "domain"=>"",
            "prefix"=>"lhPelanCMedusa"
        )
    ),
    "medusa_test" => array(
        "cors" => "http://localhost:8080",
        "auth" => "medusa_fake",
        "cookie"=> array(
            "secure"=>false,
            "domain"=>"",
            "prefix"=>"lhPelanCMedusaFake"
        )
    ),
    "demo" => array(
        "cors" => "https://pelan-demo.osis.io",
        "auth" => "demo",
        "cookie"=> array(
            "secure"=>false,
            "domain"=>"osis.io",
            "prefix"=>"pelDEMOCoo"
        )
    )
);

$conf = array(
    "env" => $environments['medusa_test'],
    "db" => array(
        "host" => "localhost",
        "database" => "pelan",
        "user" => "root",
        "pass" => ""
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