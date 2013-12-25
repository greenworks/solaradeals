<?php

class sociable
{
	var $url;
	var $resolution;
	var $target;
	var $title;
	var $args;
	
	function sociable($base, $res = 32, $target = 'target="_blank"', $title='', $param)
	{
		$this->url = $base;
		$this->resolution = $res;
		$this->target = $target;
		$this->title = $title;
		$this->args = $param;
	}	
	
	function get_link($type)
	{
		switch ($type)
		{
			//diggit
			case 'diggit':
				$link = '<a href="http://www.digg.com/submit?phase=2&amp;url='. urlencode($this->url) .'" title="Digg It!" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/diggit_'.$this->resolution.'.png" alt="DiggIt" />';
				$link .= '</a>';
			break;
			
			//facebook
			case 'facebook':
				$link = '<a href="http://www.facebook.com/sharer.php?u='. urlencode($this->url) .'" title="Facebook" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/facebook_'.$this->resolution.'.png" alt="Facebook" />';
				$link .= '</a>';
			break;
			
			//delicious
			case 'delicious':
				$link = '<a href="http://del.icio.us/post?url='. urlencode($this->url) .'" title="Del.ici.ous" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/delicious_'.$this->resolution.'.png" alt="Del.ici.ous" />';
				$link .= '</a>';
			break;
			
			//google
			case 'google':
				$link = '<a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk='. urlencode($this->url) .'" title="Google Bookmarks" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/google_'.$this->resolution.'.png" alt="Google Bookmarks" />';
				$link .= '</a>';
			break;
					
			//google
			case 'mixx':
				$link = '<a href="http://www.mixx.com/submit?page_url='. urlencode($this->url) .'" title="Mixx" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/mixx_'.$this->resolution.'.png" alt="Mixx" />';
				$link .= '</a>';
				break;
				
			//myspace
			case 'myspace':
				$link = '<a href="http://www.myspace.com/Modules/PostTo/Pages/?u='. urlencode($this->url) .'" title="MySpace" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/myspace_'.$this->resolution.'.png" alt="MySpace" />';
				$link .= '</a>';
				break;
				
			//newsvine
			case 'newsvine':
				$link = '<a href="http://www.newsvine.com/_tools/seed&amp;save?u='. urlencode($this->url) .'" title="Newsvine" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/newsvine_'.$this->resolution.'.png" alt="Newsvine" />';
				$link .= '</a>';
				break;
				
			//reddit
			case 'reddit':
				$link = '<a href="http://reddit.com/submit?url='. urlencode($this->url) .'" title="Google" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/reddit_'.$this->resolution.'.png" alt="Google" />';
				$link .= '</a>';
				break;

			//stumbleupon
			case 'stumbleupon':
				$link = '<a href="http://www.stumbleupon.com/submit?url='. urlencode($this->url) .'" title="StumbleUpon" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/stumbleupon_'.$this->resolution.'.png" alt="StumbleUpon" />';
				$link .= '</a>';
				break;
				
			//technorati
			case 'technorati':
				$link = '<a href="http://technorati.com/faves?add='. urlencode($this->url) .'" title="Technorati" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/technorati_'.$this->resolution.'.png" alt="Technorati" />';
				$link .= '</a>';
				break;
				
			//twitter
			case 'twitter':
				$link = '<a href="http://twitter.com/home?status='. urlencode($this->url) .'" title="Twitter" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/twitter_'.$this->resolution.'.png" alt="Twitter" />';
				$link .= '</a>';
				break;
				
			//udjamaflip
			case 'udjamaflip':
				$link = '<a href="http://www.udjamaflip.com/" title="Andy Sharman\'s Joomla Sociable Module" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/udjamaflip_'.$this->resolution.'.png" alt="Andy Sharman\'s Joomla Sociable Module" />';
				$link .= '</a>';
				break;
						
			//linkedin
			case 'linkedin':
				$link = '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='. urlencode($this->url) .'" title="linkedin" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/linkedin_'.$this->resolution.'.png" alt="linkedin" />';
				$link .= '</a>';
				break;
				
			//aim
			case 'aim':
				$link = '<a href="https://my.screenname.aol.com/_cqr/login/login.psp?sitedomain=x.aim.com&authLev=1&lang=en&locale=us&siteState=OrigUrl%3D'. urlencode($this->url) .'" title="aim" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/aim_'.$this->resolution.'.png" alt="aim" />';
				$link .= '</a>';
				break;
				
			//bebo
			case 'bebo':
				$link = '<a href="http://www.bebo.com/PleaseSignIn.jsp?Page=c/share&amp;Url='. urlencode($this->url) .'" title="bebo" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/bebo_'.$this->resolution.'.png" alt="bebo" />';
				$link .= '</a>';
				break;
				
			//blogger
			case 'blogger':
				$link = '<a href="https://ws.addthis.com/share/v2/facade?dest=blogger&amp;url='. urlencode($this->url) .'&amp;html=&title='.urlencode($this->title).'" title="blogger" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/blogger_'.$this->resolution.'.png" alt="blogger" />';
				$link .= '</a>';
				break;
							
			//friendfeed
			case 'friendfeed':
				$link = '<a href="https://friendfeed.com/account/login?next='. urlencode($this->url) .'" title="friendfeed" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/friendfeed_'.$this->resolution.'.png" alt="friendfeed" />';
				$link .= '</a>';
				break;
				
			//wordpress
			case 'wordpress':
				$link = '<a href="https://ws.addthis.com/share/v2/facade?dest=wordpress&amp;url='. urlencode($this->url) .'&amp;html=&title='.urlencode($this->title).'" title="wordpress" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/wordpress_'.$this->resolution.'.png" alt="wordpress" />';
				$link .= '</a>';
				break;
				
			//netvibes
			case 'netvibes':
				$link = '<a href="http://www.netvibes.com/signin?url='. urlencode($this->url) .'" title="netvibes" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/netvibes_'.$this->resolution.'.png" alt="netvibes" />';
				$link .= '</a>';
				break;
			
			//tumblr
			case 'tumblr':
				$link = '<a href="http://www.tumblr.com/login?redirect_to='. urlencode($this->url) .'" title="tumblr" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/tumblr_'.$this->resolution.'.png" alt="tumblr" />';
				$link .= '</a>';
				break;
				
			//yahoo
			case 'yahoo':
				$link = '<a href="https://login.yahoo.com/config/login?.src=bmk2&.intl=us&.done='. urlencode($this->url) .'" title="Yahoo" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/yahoo_'.$this->resolution.'.png" alt="Yahoo" />';
				$link .= '</a>';
				break;
				
			//yahoobuzz
			case 'yahoobuzz':
				$link = '<a href="https://login.yahoo.com/config/login_verify2?.pd=c%3DhYw09vWp2e4FXlpTB9bd0rU-&.src=ybz&.intl=us&.done='. urlencode($this->url) .'" title="yahoobuzz" '.$this->target.' >';
				$link .= '<img src="modules/mod_sociable/icons/'.$this->resolution.'/yahoobuzz_'.$this->resolution.'.png" alt="yahoobuzz" />';
				$link .= '</a>';
				break;
		}
						
		return $link;
	}
	
	function fetch_list()
	{
		$list = $this->get_list();
		
		$return = '<ul class="sociableList">';
		foreach ($list as $item)
		{
			//echo '<!-- '.$item.' - '.$this->args->get($item).' -->'."\r\n";
			if ($this->args->get($item) == 1)
			{
				$return .= '<li>'. $this->get_link($item) .'</li>';
			}
		}		
		$return .= '</ul>';
		
		return $return;
	}
	
	//returns a list of all available sociable buttons.
	function get_list()
	{
		$list[0] = 'diggit';
		$list[1] = 'facebook';
		$list[2] = 'delicious';
		$list[3] = 'google';
		$list[4] = 'mixx';
		$list[5] = 'myspace';
		$list[6] = 'newsvine';
		$list[7] = 'reddit';
		$list[8] = 'stumbleupon';
		$list[9] = 'technorati';
		$list[10] = 'twitter';
		$list[11] = 'udjamaflip';
		$list[12] = 'linkedin';
		$list[13] = 'aim';
		$list[14] = 'bebo';
		$list[15] = 'blogger';
		$list[16] = 'friendfeed';
		$list[17] = 'wordpress';
		$list[18] = 'netvibes';
		$list[19] = 'tumblr';
		$list[20] = 'yahoo';
		$list[21] = 'yahoobuzz';
		
		//makes it appear nicer when ordered.
		sort($list);		
		return $list;	
	}
	
	function convert_target()
	{
		switch ($this->target)
		{
			//Targets the same window
			case '0':
				$this->target = '';
				break;
			//Targets a greybox window
			case '1':
				$this->target = 'rel="gb_page_fs[]"';
				break;
			//Targets a new window
			case '2':
				$this->target = 'target="_blank"';
				break;			
		}
	}
	
}

?>