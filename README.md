# querystring

Simple querystring module with static accessors.

## Interface

```
Array = querystirng::pparse($querystring) - Performs a proper parse of the querystring (arrays to arrays, etc)

querystring:rqm($querystring) - Removes leading question mark
querystring:aqm($querystring) - Adds leading question mark

querystring::get($url, $key);
querystring::get($key); // pulls from `$_SERVER`
String = querystring::del($url, $key); // Returns querysting without `$key`
String = querystring::add($url, $key, $value); // Returns querysting with `$key=$value` added

```

## LICENSE

BSD 2 Clause
