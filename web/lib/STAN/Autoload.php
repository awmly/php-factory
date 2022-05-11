<?php

namespace STAN;

class Autoload{

	public static function Init(){

		spl_autoload_extensions('.php');

		spl_autoload_register('self::Lib');

	}

	public static function Load($file){

		if(is_file($file)) return include($file); else return false;

	}


	public static function Lib($class){

		$file=PATH_LIB.str_replace("\\","/",$class).'.php';

		return self::Load($file);

	}


}
