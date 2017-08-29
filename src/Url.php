<?php 
namespace babounlek\minify;

Class url
{

	public static function path($url)
	{
		return str_replace(["/b/","/"], "", parse_url(str_replace("///", "//", $url))['path']);
	}

	public static function complete_url($domain, $url)
	{
		if(!Url::is_absolute($url))
		{
			if(Url::get_domain($url)['domain']<>Url::get_domain($domain)['domain'])
				return $domain.$url;
			else
			{
				if(strpos($url, "http://")===false and (strpos($url, "https://")===false))
				{
					
					if(strpos($url, "//")==0)
						$val = str_replace("//", "http://", $url);
					elseif(strpos($url, "//")==0)
						$val = str_replace("://", "http://", $url);
			    		if($val==$url)
			    		{
			    			$url="http://".$val;
			    		}
			    		else
							return $val;
				}
				else

				return $url;
			}
		}
		else
		{
			return $url;
		}
	}

	public static function is_absolute($url)
	{
		$urlParts = parse_url($url);

		return @$urlParts['scheme']<>"";
	}

	public static function alt_url($url)
	{
		$url = str_replace("http://", "", $url);
		$url = str_replace("https://", "", $url);
		if(strpos($url, "www.")!==false)
			return str_replace("www.", "", $url);
		else
		{
			return "www.".$url;
		}
	}

	public static function get_domain($url)
	{
	 
	  $pieces = parse_url($url);
	 // print_r($pieces)."<br />";
	  $domain = isset($pieces['host']) ? $pieces['host'] : '';
	  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
	    return array("domain" => rtrim($regs['domain'],"/"), "scheme"=>@$pieces["scheme"]?@$pieces["scheme"]:"http");
	  }
	  elseif(@$pieces['path'])
	  {//print_r($pieces);
	  	return  array("domain" => rtrim($pieces['path'],"/"),"scheme"=>@$pieces["scheme"]?@$pieces["scheme"]:"http");
	  }
	  return false;
	}

	public static function get_link($url, $label='', $class='')
	{
		$link = "<a rel='nofollow' target ='_blank' href ='".route('follow',['url'=>$url])."'' ";
		if($class<>'') $link .= "class = '".$class."'";

		$link .= ">";
		
		if($label<>'') 
			$link .= $label;
		else
			$link .= $url;

			$link .= "</a>";
		return $link;
	}

	public static function is_external($url, $host) {

	  $components = parse_url($url);
	
	  if(empty($components['host'])) return false;  // we will treat url like '/relative.php' as relative

	  if(strcasecmp($components['host'], $host) === 0) return false; // url host looks exactly like the local host

	  if((strrpos(strtolower($components['host']), '.'.$host) == strlen($components['host']) - strlen('.'.$host)) and (substr($components['host'], 0,4) !='www.')) 
	  {
	  	return false;//1; // check if the url host is a subdomain
	  }
	  else
	  	return 1;
	  return 2;
	}

	public static function get_first_internal($list, $host)
	{
		$number = count($list);
		$i= 0 ;
		$find = 0;
		while($i<$number and !$find)
		{
			$find = !Url::is_external($list[$i],$host);
			if($find)return $list[$i];
			$i +=1;
		}
		return null;
	}

	public static function get_internal($host, $list)
	{
		$number = count($list);
		$i= 0 ;
		$internal = array();
		$external = array();
		while($i<$number)
		{	$url = $list[$i][0];
			if(!Url::is_external($url ,$host))
			{
				if(strpos($host,"rank2me.com")===false)
					array_push($internal, [Url::complete_url($host,$url),$list[$i][1]]);
				elseif(Url::is_absolute($url))
				{
					array_push($internal, [$url,$list[$i][1]]);
				}
			}
			else
			{
				array_push($external,[$url,$list[$i][1]]);
			}
			$i +=1;
		}

		return [$internal,$external];
	}

	public static function strpos_array($haystack, $needles, $offset = 0) {
		if (is_array($needles)) {
			foreach ($needles as $needle) {
				$pos = Url::strpos_array($haystack, $needle);
				if ($pos !== false) {
					return $pos;
				}
			}
			return false;
		} else {
			return strpos($haystack, $needles, $offset);
		}
	}

	public static function is_seo_friendly_url($url)
	{
		$str = strtolower($url);
		$str = trim($str);
		$str = preg_replace('/[^a-z0-9.\/-:]+/', '-', $str);
		return ($url == $str);
	}

	public static function get_curl_remote_ips($fp) 
	{
	    rewind($fp);
	    $str = fread($fp, 8192);
	    $regex = '/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/';
	    if (preg_match_all($regex, $str, $matches)) {
	        return array_unique($matches[0]); 
	    } else {
	        return false;
	    }
	}

	public static function add_scheme($url,$scheme='http://')
	{
	
		if(Url::strpos_array($url, array('http://','https://'))===false)
		{
			if(strpos($url,'//')===false)
			{

			
				$url = $scheme.$url;
				/*if(strpos($url,'w3schools.com/')===false)
					echo $url." - 1<br>";*/
				return $url;
			
			}
			elseif(strpos($url,'//')==0)
			{	
			
					return str_replace("//",$scheme,$url);		
			}
			else
			{
				//echo "2<br>";
				return $scheme.str_replace("//","/",$url);
			}
		}else
		{
			//echo "3<br>";
			return $url;
		}
	}

	public static function is_blog_link($url,$title)
	{
		return (strpos(strtolower($url),'blog')>=0 and strpos(strtolower(trim($title)),'blog')>=0);
	}

	public static function sitename($url)
	{	$url = Url::add_scheme($url,"http://");
		 $pieces = parse_url($url);
		  $domain = isset($pieces['host']) ? $pieces['host'] : '';
		    $host = "";
		  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		    $host = $regs['domain'];
		  }

		  $ext = explode('.', $host);
		  if(count($ext)) 
		  		return $ext[0];
		  else
		  	return "";
	}

	public static function rewrite($url)
	{
		//String to lower
		$url = strtolower($url);
		//Replace accented characters php
		$unwanted_chars_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
		$url = strtr( $url, $unwanted_chars_array);
		 //Replace not alphanumeric character
		 $url = preg_replace('/[^A-Za-z0-9\-]/', '-', $url);
		 //Replace multiple a high dash (-)
		 $url = str_replace(array("----", "---","--"),"-",$url);
		 return $url;
	}

	public static function favicon($domain,$size)
	{
		$domain = Url::get_domain($domain)['domain'];
		
		$icon = "https://www.google.com/s2/favicons?domain=".$domain."&size=32";
		//$icon = '<i style="background-image: url('.$icon.')" class="icon icon-'.$size.'-'.$size.'" alt="Favicon">&nbsp;&nbsp;&nbsp;&nbsp;</i>';
		$icon = "<img  class='icon icon-".$size."-".$size."' src='".$icon."'/>";
		return $icon;
	}

	public static function create($link,$label)
	{
		return "<a target='_blank' href='".route('follow',['url'=>$link])."'>".$label."</a>";
	}

	public static function crawlable($url)
	{
	        $http = substr_count($url, "http:");
	        $https = substr_count($url, "https:");
	        $link = rtrim($url,"/");
	        $link = rtrim($link,"#");
	        $not_intern = strpos($url,'#')==false;
	        return ($url==$link && $http<2 && $https<2 && $not_intern && !($http==1 and $https==1));
	        //http://rank2me.com/fr/follow/http://rank2me.com/fr/index/lc-doc.com
	}
	
	public static function valid($url)
	{
		 $not_intern = strpos($url,'#')==false;
		 return $not_intern;
	}

	public static function social_icon($url)
	{
		$val = Url::get_domain($url)['domain'];

		switch ($val) {
			case 'facebook.com':
				$icon = Common::get_icon_facebook_official(1,'fa-lg');
				break;
			case 'twitter.com':
				$icon = Common::get_icon_twitter_official(1,'fa-lg');
				break;	
			case 'google.com':
				$icon = Common::get_icon_plus_official(1,'fa-lg');
				break;	
			case 'instagram.com':	
				$icon = Common::get_icon_instagram_official(1,'fa-lg');
				break;	
			case 'linkedin.com':	
				$icon = Common::get_icon_linkedin_official(1,'fa-lg');
				break;
			case 'youtube.com':	
				$icon = Common::get_icon_youtube_official(1,'fa-lg');
				break;
			case 'pinterest.com':	
				$icon = Common::get_icon_pinterest_official(1,'fa-lg');
				break;
			case 'slideshare.net':	
				$icon = Common::get_icon_slideshare_official(1,'fa-lg');
				break;
			default:
				$icon = '';
				break;
		}
		return $icon;
	}
	
	public static function follow($txt)
	{
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		
		// The Text you want to filter for urls
		$text = $txt;
		
		// Check if there is a url in the text
		if(preg_match($reg_exUrl, $text, $url)) {
		
		       // make the urls hyper links
		       return preg_replace($reg_exUrl, route('follow', ['url' => $url[0]]), $text);
		
		} else {
		
		       // if no urls in the text just return the text
		       return $text;
		
		}
	}
	
	
	public static function url_exists($strURL) {
	    $resURL = curl_init();
	    curl_setopt($resURL, CURLOPT_URL, $strURL);
	    curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1);
	    curl_setopt($resURL, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($resURL, CURLOPT_HEADER, TRUE);
	    curl_setopt($resURL, CURLOPT_NOBODY, TRUE);
	    curl_setopt($resURL, CURLOPT_FOLLOWLOCATION, TRUE); 
	    curl_setopt($resURL, CURLOPT_FAILONERROR, 1);
	    curl_setopt($resURL, CURLOPT_VERBOSE, TRUE);
	    curl_setopt($resURL, CURLOPT_RETURNTRANSFER, TRUE);
	 
	    curl_exec ($resURL);
	 
	    $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
	    
	    curl_close ($resURL);
	 
	    if($intReturnCode==0)
	    {
	    	return 0;
	    }
	    elseif($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) {
	    	return 0;
	    } else{
	        return 1;
	    }
	}
	

	public static function combine($files,$outputfile)
	{
	    $filepath = __DIR__."/../../public/".$outputfile;
	    $out = fopen($filepath, "w");
	
	      foreach($files as $file){
	          $in = fopen($file, "r");
	          while ($line = fgets($in)){

	               fwrite($out, $line);
	          }
	          fclose($in);
	      }
	
	    fclose($out);
	
	    return $filepath;
	}
}