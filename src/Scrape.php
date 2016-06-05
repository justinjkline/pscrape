<?php

require __DIR__.'/../vendor/autoload.php';
$rollingCurl = new \RollingCurl\RollingCurl();
    
class Scrape {

    public function hello() {
        echo "<pre>";
        print_r($rollingCurl);
    }

}

?>