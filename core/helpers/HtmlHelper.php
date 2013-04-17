<?php
/**
 *	PhIntuitive - Fast websites development framework
 *	Copyright 2013, Boutter Loïc - http://loicboutter.fr
 *
 *	Licensed under The MIT License
 *	Redistributions of files must retain the above copyright notice.
 *
 *	@copyright Copyright 2013, Boutter Loïc - http://loicboutter.fr
 *	@author Boutter Loïc
 *	@since 2.0.0
 */



/**
 * HTML Helper is useful to add html tags easily.
 *
 * You can have a lot of "head" tags with HtmlHelper methods and a few "body" tags like "img" and "a". You do not need to worry about the path for links, 
 * the Router will automatically be called and will generate correct links.
 */
class HtmlHelper extends Helper
{
	private $doctypes = array('html5'		   => '<!DOCTYPE html>',
								'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
								);

	private $tags = array('meta' => '<meta %s />',
							'link' => '<a href="%s"%s>%s</a>',
							'image'=> '<img src="%s"%s />',
							'css'  => '<link rel="stylesheet" type="text/css" href="%s" />',
							'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
							'js'   => '<script type="text/javascript" src="%s"></script>',
							'jquery' => '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/%s/jquery.min.js"></script>');


	/**
	 * Returns the doctype of the web document. Two types of doctypes are available :
	 * - html5 (default) : returns html5 doctype (<!DOCTYPE html>)
	 * - xhtml-strict : returns the classic xhtml 1.0 doctype
	 * @param $type the doctype type
	 * @return string containig the doctype declaration
	 */
	public function doctype($type = 'html5')
	{
		return $this->doctypes[$type]."\n";
	}

	/**
	 * Returns the meta charset tag corresponding to the choosen charset
	 * - UTF-8 (default)
	 * - any other charset
	 * @param $type the charset
	 * @return string containig the meta declaration
	 */
	public function charset($type = 'UTF-8')
	{
		return sprintf($this->tags['charset'], $type)."\n";
	}

	/**
	 * Returns the meta robots tag corresponding to the choosen content
	 * - allow
	 * - follow
	 * - deny
	 * - combination of those separated by comma
	 * @param $content the content value
	 * @return string containig the meta declaration
	 */
	public function robots($content)
	{
		$meta = 'name="robots" content="'.$content.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	/**
	 * Returns the meta description tag corresponding to the choosen description
	 * @param $description the description
	 * @return string containig the meta declaration
	 */
	public function description($description)
	{
		$meta = 'name="description" content="'.$description.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	/**
	 * Returns the meta keywords tag corresponding to the choosen keywords
	 * @param $keywords the keywords
	 * @return string containig the meta declaration
	 */
	public function keywords($keywords)
	{
		$meta = 'name="keywords" content="'.$keywords.'"';
		return sprintf($this->tags['meta'], $meta)."\n";
	}

	/**
	 * Returns the link tag to include the CSS file you want. The CSS file location is assumed given :
	 * - if you give an absolute path (with http://) it's included directly
	 * - if you give a file starting with a '/', the file is search relatively to phintuitive root
	 * - else, if phintuitive will look for the CSS file into app/layouts/css folder
	 * Please note that you must give the CSS extension as well
	 * @param $file the filename with extension
	 * @return string containig the link tag
	 */
	public function css($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['css'], $file)."\n";

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().$file)."\n";

		return sprintf($this->tags['css'], App::host().App::layouts().'css/'.$file)."\n";
	}

	/**
	 * Returns the script tag that include jquery from google repository. You can specify the version you want. Default : 1.9.1
	 * @param $version the version of jquery
	 * @return string containig the script tag
	 */
	public function jQuery($version = '1.9.1')
	{
		return sprintf($this->tags['jquery'], $version)."\n";
	}

	/**
	 * Returns the script tag to include the JS file you want. The JS file location is assumed given :
	 * - if you give an absolute path (with http://) it's included directly
	 * - if you give a file starting with a '/', the file is search relatively to phintuitive root
	 * - else, if phintuitive will look for the JS file into app/layouts/js folder
	 * Please note that you must give the JS extension as well
	 * @param $file the filename with extension
	 * @return string containig the script tag
	 */
	public function js($file)
	{
		if(preg_match('!http://!', $file))
			return sprintf($this->tags['js'], $file)."\n";

		if($file[0] == '/')
			return sprintf($this->tags['css'], App::host().trim($file, '/'))."\n";

		return sprintf($this->tags['css'], App::host().App::layouts().'js/'.$file)."\n";
	}

	/**
	 * Returns a <a> tag (link) to the page you want to reach.
	 * The first parameter is very important, it can be :
	 * - an absolute path (with http://)
	 * - a path starting with a '/', the target is search relatively to phintuitive root
	 * - a path that does not begin wih a '/' that will be checked for routes, prefixes and then included after website root.
	 * - an array that contains at least the controller and the action to call and optionnaly parametes for that action. IE :
	 *	@code
	 *	$url = array(
	 *		'controller' => 'posts',
	 *		'action'	 =>	'view',
	 *		'id'		 => 3 		// This is a parameter for Post->view($id)
	 *	);
	 *	@endcode
	 * @param $url the url, see above description
	 * @param $title the content of the <a> tag, the text that you will click on
	 * @param $target if you want to change the taget of the link (_top, _blank, ...)
	 * @param $confirm a message of confirmation that will be alerted to the user when he'll click on the link
	 * @return string containig the link tag
	 */
	public function link($url, $title = null, $target = null, $confirm = null)
	{
		if(is_array($url)) {
			$url = Router::url($url);
		} else if(preg_match('!http://!', $url))
			$url = $url;
		elseif($url[0] == '/')
			$url = App::host().trim($url, '/');
		else
			$url = Router::url($url);

		$others = '';
		if($target)	$others .= ' target="'.$target.'"';
		if($confirm)
		{
			$confirm = str_replace("'", "\'", $confirm);
			$confirm = str_replace('"', '\"', $confirm);
			$others .= ' onclick="return confirm(\''.$confirm.'\')"';
		}


		return sprintf($this->tags['link'], $url, $others, $title);
	}

	/**
	 * Returns a <img> tag (image)
	 * The first parameter is very important, it can be :
	 * - an absolute path (with http://)
	 * - a path starting with a '/', the target is search relatively to phintuitive root
	 * - else the image will be searched into app/layouts/img/ folder
	 * @param $url the url, see above description
	 * @param $alt the alternative text you want to display if the image does not exist
	 * @param $title the title that appears when the mouse is over the image
	 * @return string containig the image tag
	 */
	public function img($url, $alt = null, $title = null)
	{
		if(preg_match('!http://!', $url))
			$url = $url;
		elseif($url[0] == '/')
			$url = App::host().trim($url, '/');
		else
			$url = App::host().App::layouts().'img/'.$url;

		$others = '';
		if($alt)	$others .= ' alt="'.$alt.'"';
		if($title)  $others .= ' title="'.$title.'"';


		return sprintf($this->tags['image'], $url, $others);
	}


}