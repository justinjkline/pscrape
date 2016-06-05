<?php

namespace Pscrape\Pscrape;
require __DIR__.'/../vendor/autoload.php';
use Illuminate\Support\ServiceProvider;


class Scrape {

    public function rollingCurl($type, $opts = false) {
        if ( $type == "rollingCurl" ) {
            return new \RollingCurl\RollingCurl();
        } else {
            $opts['method'] = ( !isset($opts['method']) ) ? "GET" : $opts['method'];
            return new \RollingCurl\Request($opts['url'], $opts['method']);
        }
    }

    public function _curl_post($url, $headers = array(), $cookies = "", $post_data = array(), $cookie_file = false, $post = false, $login = false, $send_info = false) {

        $ch = curl_init();

        // use cookie file if passed in
        if ($cookie_file) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, env('COOKIE_PATH'));
        }
        // just reading purposes
        else {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        }

        curl_setopt($ch, CURLOPT_COOKIEFILE, env('COOKIE_PATH'));

        // use raw cookie if that's passed in
        if ($cookies != "") {
            curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        }

        if ( $login ) {
            curl_setopt($ch, CURLOPT_USERPWD, $login);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, trim($url));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        

        if (is_array($post_data)) {
            if (sizeof($post_data) > 0) {
                curl_setopt($ch, CURLOPT_POST, $post);
                $p_data = http_build_query($post_data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $p_data);
            }
        } else if ($post_data != "") {
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }


        $response  = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ( $send_info ) {
            $response = array('page_data' => $response, 'info' => $info);
        }

        return $response;
    }

    public function get_image($link) {

        $zd = gzopen($link, "r");
        $contents = gzread($zd, 1000000);
        gzclose($zd);
        
        return 'data:image/png;base64,' . base64_encode($contents);

    }

    private function random_user_agent() {

        $browser_strings = array (
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6  Firefox 2.0, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)  Internet Explorer 7, Windows Vista",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)  Internet Explorer 7, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)  Internet Explorer 6, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 5.0; Windows NT 5.1; .NET CLR 1.1.4322) Internet Explorer 5, Windows XP",
            "Opera/9.20 (Windows NT 6.0; U; en)  Opera 9.2, Windows Vista",
            "Opera/9.00 (Windows NT 5.1; U; en)  Opera 9.0, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50  Opera 8.5, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.0  Opera 8.0, Windows XP",
            "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]  Opera 7.02, Windows XP",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20060127 Netscape/8.1  Netscape 8.1, Windows XP",
            "Googlebot/2.1 ( http://www.googlebot.com/bot.html) Googlebot 2.1 (search engine bot)",
            "Googlebot-Image/1.0 ( http://www.googlebot.com/bot.html) Googlebot Image 1.0 (search engine image bot)",
            "Mozilla/2.0 (compatible; Ask Jeeves) Ask Jeeves (search engine bot)",
            "msnbot-Products/1.0 (+http://search.msn.com/msnbot.htm) Windows Live (search engine bot)"
        );


        $num = rand(0, sizeof($browser_strings) - 1);

        return $browser_strings[$num];
        
    }

    public function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public function is_url_valid($url) {

        $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,8})"; // Host or IP
        $regex .= "(\:[0-9]{2,5})?"; // Port
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

        if(preg_match("/^$regex$/", $url, $m))
            return true;//var_dump($m);
        else
            false;

    }

}

?>