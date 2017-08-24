<?php

namespace babounlek\minifier;
use Url;

/**
 *
 */
class Minifier
{

  public $source = "";

  function __construct()
  {
      $args = func_get_args();
      foreach ($args as $index => $arg)
      {
          if($arg)$this->addsource($arg);
          //unset($args[$index]);
      }
  }

  public function addsource($source)
  {
    if(Url::is_absolute($source))
    {
      $source = file_get_contents($source);
    }
    $this->source .= $source;
  }

  public function minify()
	{
    $buffer = $this->source;
		$original = mb_strlen($buffer);
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// Remove space after colons
		$buffer = str_replace(': ', ':', $buffer);
		// Remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t",  '    ', '    '), '', $buffer);
    $buffer = str_replace("  "," ",$buffer);
    $buffer = str_replace(array(' {',' }',' ;','; ',', '),array('{','}',';',';',','), $buffer);
    $buffer = str_replace(";}","}",$buffer);
		$final= mb_strlen($buffer,"UTF8");

		return [$buffer,$original,$final];
	}

}
