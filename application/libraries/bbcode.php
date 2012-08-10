<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bbcode {
	
	public function bbcode2html($text) {
		
		//turn a youtube url into one with just the identifier
		$text = preg_replace('/\[yt\].*[\\?\\&]v=([^\\?\\&]+).*\[\/yt\]/', '[youtube]\1[/youtube]', $text);
		
		return preg_replace(array(
			'/\n/', 
    		'/\[b\](.*?)\[\/b\]/ms', 
		    '/\[i\](.*?)\[\/i\]/ms',
		    '/\[u\](.*?)\[\/u\]/ms',
		    '/\[img\](.*?)\[\/img\]/ms',
		    '/\[email\](.*?)\[\/email\]/ms',
		    '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
		    '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
		    '/\[youtube\](.*?)\[\/youtube\]/ms',
		    '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',    
		    '/\[quote](.*?)\[\/quote\]/ms',
		    '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
		    '/\[list\](.*?)\[\/list\]/ms',
		    '/\[\*\]\s?(.*?)\n/ms'
		   ),array(
			'<br>',
		    '<strong>\1</strong>',
		    '<em>\1</em>',
		    '<u>\1</u>',
		    '<img src="\1" alt="\1" />',
		    '<a href="mailto:\1">\1</a>',
		    '<a href="\1">\2</a>',
		    '<span style="font-size:\1%">\2</span>',
		    '<iframe width="420" height="315" src="http://www.youtube.com/embed/\1" frameborder="0" allowfullscreen></iframe>',
		    '<span style="color:\1">\2</span>',
		    '<blockquote>\1</blockquote>',
		    '<ol start="\1">\2</ol>',
		    '<ul>\1</ul>',
		    '<li>\1</li>'
		   ),$text);
	}
}

