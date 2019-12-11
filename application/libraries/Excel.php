<? if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
ini_set('error_reporting', E_STRICT); 
require_once( APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
class Excel extends PHPExcel 
{ 
	public function __construct() 
	{ 
		parent::__construct(); 
	}
} 
?>