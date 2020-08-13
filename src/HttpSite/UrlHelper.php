<?php 
namespace Core\HttpSite;
 

class UrlHelper  
{      
    static public function assertDefaultHost(string $url)
    {     
        return self::assertEqualDomain($url, self::defaultHost(true));
    }

    static public function trimHost(string $url, string $host = null)
    {    
        return str_after(
            $host ?: self::defaultHost(true), UrlHelper::getNormalizedHost($domain)
        );  
    }

	static public function assertEqualDomain($first, $second, $trim = true)
    {   
        return self::getNormalizedHost($first, $trim) === self::getNormalizedHost($second, $trim);
    }

    static public function getNormalizedHost($domain, $trim = false)
    { 
        $parsed = self::parseUrl($domain);

        return $trim ? str_after($parsed['host'], 'www.') : $parsed['host'];; 
    } 

    static public function parseUrl($url)
    {   
        $fullUrl = self::ensureProtocol($url);

        return parse_url($fullUrl); 
    }

    static public function ensureProtocol(string $domain)
    { 
        if(! preg_match('/^https?:\/\//', $domain)) {
            $domain = self::protocol() . $domain;
        }

        return $domain; 
    }

    static public function protocol()
    {
        return request()->secure() ? 'https://' : 'http://';
    }

    static public function trimSlash(string $string = null)
    {
        return trim($string, '/');
    }

    static public function defaultHost(bool $trim = false)
    {
        return self::getNormalizedHost(config('app.url')?: env('APP_URL'), $trim);
    } 

    static public function encode(string $url = null)
    {
        return urlencode($url);
    }

    static public function decode(string $url = null)
    {
        return urldecode($url);
    }

}
