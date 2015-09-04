gearman-codeigniter-library
======================
A CodeIgniter library for a simple queue system using gearman.
Please feel free to let me know (or just fork) if you find any bugs or improvements points.

Thanks, 
Donny (https://github.com/black-lotus)

Requirements
-----------
1. PHP 5.0 or more
2. CodeIgniter 2.0 or more (http://codeigniter.com)
3. Gearman PHP Extension https://pecl.php.net/package/gearman)

Guide
-----------
### Installing the Gearman Library
The Gearman extension is required for Gearman Codeigniter Library to work,
so please install the Gearman extension library first (if you have not done so yet).


### Worker
Add function :

```
     $this->gearman_worker->add_function('function_name', 'function');
```

Or, you can access gearman instance :
```
	$this->gearman_worker->instance->{native_method};
```

### Client
Do normal :
```
	$res = $this->gearman_client->do_normal('function_name', 'params');
	var_dump($res);
```

Do background :
```
	$this->gearman_client->do_background('function_name', 'params');
```

Or, you can access gearman instance :
```
	$this->gearman_client->instance->{native_method};
```


License
----------
This library is released under the GPL license.


