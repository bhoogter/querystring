<?php

class querystring
{
    public static function hqm($s) { return is_string($s) && substr($s, 0, 1) == '?'; }
    public static function rqm($s) { return ('' . $s == '') ? '' : ((substr($s, 0, 1) == "?") ? substr($s, 1) : $s); }
    public static function aqm($s)
    {
        if ($s == "") return $s;
        if (substr($s, 0, 7) == "http://" || substr($s, 0, 8) == "https://")
            return strstr($s, "?") == "?" ? $s : $s . "?";
        return (($s[0] == "?") ? "" : "?") . $s;
    }

    private static function qs() { 
        $v = @$_SERVER['QUERY_STRING']; 
        return $v ? $v : '';
    }
    private static function shift2qs(&$a, &$b)
    {
        // print "\nSHIFT2";
        $b = $a;
        $a = self::qs();
    }

    private static function shift3qs(&$a, &$b, &$c)
    {
        // print "\nSHIFT3";
        $c = $b;
        $b = $a;
        $a = self::qs();
    }

    public static function pparse($str)
    {
        if (!is_string($str) || $str == '') return array();
        $str = self::rqm($str);
        $arr = [];                                              # result array
        $pairs = explode('&', $str);                            # split on outer delimiter

        foreach ($pairs as $i)                                  # loop through each pair
        {
            @list($name, $value) = explode('=', $i, 2);         # split into name and value
            if (isset($arr[$name]))                             # if name already exists
            {
                if (is_array($arr[$name]))                      # stick multiple values into an array
                    $arr[$name][] = $value;
                else
                    $arr[$name] = array($arr[$name], $value);
            } else                                                 # otherwise, simply stick it in a scalar
            {
                $arr[$name] = $value;
            }
        }
        return $arr;                                            # return result array
    }

    public static function get($url, $key = null)
    {
        if ($key == null) self::shift2qs($url, $key);
        $output = querystring::pparse($url);
        return isset($output[$key]) ? $output[$key] : "";
    }

    static function set($url, $key, $value = null)
    {
        $had_qm = self::hqm($url);
        if (!is_string($value) && $value == null) self::shift3qs($url, $key, $value);
        else if ($value == '') return self::del($url, $key);

        if ($url != "") $url = self::aqm($url);
        if ($key == "") return $url;
        $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]*?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if ($value == "") return $url;
        if (substr($url, strlen($url) - 1) == '?') $url = $url . $key . '=' . $value;
        else $url = ($url . ($url==''?'':'&') . $key . '=' . $value);
        //print "<br/>url=$url";
        $url = $had_qm ? self::aqm($url) : self::rqm($url);
        return $url;
    }

    static function del($url, $key = null)
    {
        if ($key == null) self::shift2qs($url, $key);
        $url = self::aqm($url);
        //print "<br/>remove_querystring_var($url, $key)";
        $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]*?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        return $url;
    }

    static function remove($url, $key) { return self::del($url, $key); }
    static function add($url, $key, $value) { return self::set($url, $key, $value); }

    static function zap(&$url, $key) { return self::pop($url, $key); }
    static function pop(&$url, $key)
    {
        if ($key == null) self::shift2qs($url, $key);
        $v = self::get($url, $key);
        $url = self::del($url, $key);
        return $v;
    }
}
