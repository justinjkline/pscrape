<?php

require __DIR__.'/../vendor/autoload.php';
namespace Pscrape\Pscrape;
use Illuminate\Support\ServiceProvider;
    
class Scrape {

    public function hello() {
        $rollingCurl = new \RollingCurl\RollingCurl();
        echo "<pre>";
        print_r($rollingCurl);
    }

}

?>