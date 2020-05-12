<?php

class querystring
	{
	static function proper_parse_str($str)
		{
//print "<br/>proper_parse_str($str)";
//		if (is_array($str)) juniper()->backtrace('x');
		if (!is_string($str) || $str=='') return array();
		$str = self::strip_leading_qm($str);
//		if ($str[0]=="?") $str = substr($str, 1);						# strip off any leading ?
		$arr = array();									# result array
		$pairs = explode('&', $str);							# split on outer delimiter

		foreach ($pairs as $i)  								# loop through each pair
			{
			@list($name,$value) = explode('=', $i, 2);					# split into name and value
			if( isset($arr[$name]) ) 							# if name already exists
				{
				if( is_array($arr[$name]) ) 					# stick multiple values into an array
					$arr[$name][] = $value;
				else 
					$arr[$name] = array($arr[$name], $value);
				}
			else 												# otherwise, simply stick it in a scalar
				{
				$arr[$name] = $value;
				}
			}
		return $arr;											# return result array
		}

	static function add_leading_qm($s) 
		{
		if ($s=="") return $s;
		if(substr($s,0,7)=="http://"||substr($s,0,8)=="https://")
			return strstr($s,"?")=="?"?$s:$s."?";
		return (($s[0]=="?")?"":"?").$s;
		}
	static function strip_leading_qm($s) {return (''.$s=='')?'':((@$s[0]=="?")?substr($s, 1):$s);}
	static function get_querystring_var($url, $key="")
		{
		if ($key=="") {$key=$url;$url=@$_SERVER['QUERY_STRING'];}
		$output = juniper_querystring::proper_parse_str($url);
		return isset($output[$key])?$output[$key]:"";
		}
	
	static function add_querystring_var($url, $key, $value) 
		{
		if ($url!="") $url=self::add_leading_qm($url);
//print "<br/>add_querystring_var($url, $key, $value)";
		if ($key=="") return $url;
//		if (substr($url, 0, 1)!='?') $url="?".$url;
//	    $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
	    $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]*?(&)(.*)/i', '$1$2$4', $url . '&');
	    $url = substr($url, 0, -1);
		if ($value=="") return $url;
	    if (substr($url, strlen($url)-1)=='?') $url = $url . $key . '=' . $value;
		else $url = ($url . '&' . $key . '=' . $value);
//print "<br/>url=$url";
		return $url;
		}

	static function remove_querystring_var($url, $key)
		{   
		$url = self::add_leading_qm($url);
//print "<br/>remove_querystring_var($url, $key)";
		$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]*?(&)(.*)/i', '$1$2$4', $url . '&');
		$url = substr($url, 0, -1);
		return $url;
		}

	static function get($url, $key)		{return self::get_querystring_var($url, $key);	}
	static function add($url, $key, $value)	{return self::add_querystring_var($url, $key, $value);	}
	static function remove($url, $key )	{return self::remove_querystring_var($url, $key);	}
	}
