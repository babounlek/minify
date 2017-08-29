<?php

namespace babounlek\minify;

/**
 *
 */
class minify
{

  public $source = "";
  private $extension = "";

  function __construct()
  {
      $args = func_get_args();
      foreach ($args as $index => $arg)
      {
          if($arg)$this->addsource($arg);
          //unset($args[$index]);
      }
  }

  public function setExtension($ext)
  {
    $this->extension = $ext;
    if(!in_array(strtoupper($this->extension),['CSS','JS']))
    throw new \Exception("This extension is not supported", 1);
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
    if(strtoupper($this->extension)=='CSS')
    {
      $buffer = $this->minifyCSS();
    }
    elseif(strtoupper($this->extension)=='JS')
    {
      $buffer = $this->minifyJS();
    }

		$final= mb_strlen($buffer,"UTF8");

		return [$buffer,$original,$final];
	}

  public function minifyCSS()
  {
    $buffer = $this->source;
    // Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// Remove space after colons
		$buffer = str_replace(': ', ':', $buffer);
		// Remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t",  '    ', '    '), '', $buffer);
    $buffer = str_replace("  "," ",$buffer);
    $buffer = str_replace(array(' {',' }',' ;','; ',', '),array('{','}',';',';',','), $buffer);
    $buffer = str_replace(";}","}",$buffer);
    return $buffer;
  }

  public function minifyJS()
  {
      $buffer = $this->source;
      $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
      $buffer = str_replace(["\r\n","\r","\t","\n",'  ','    ','     '], '', $buffer);
      $buffer = preg_replace(['(( )+\))','(\)( )+)'], ')', $buffer);

      return $buffer;
  }

}
