<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * A globally recognized avatar aka Gravatar helper class
 *
 * @package   Kohana/Gravatar
 * @category  Helpers
 * @author    WinterSilence
 * @copyright (c) 2013 handy-soft.ru
 * @license   MIT License
 * @link      https://github.com/WinterSilence/kohana-gravatar
 *
 *   echo Gravatar::factory('test@site.com', 'big')->render();
 *  
 *   $avatar = Gravatar::factory();
 *   echo $avatar->email('test@site.com')
 *   			->size(80)
 *   			->default_image(Gravatar::IMAGE_MM)
 *   			->force_default(FALSE)
 *   			->rating(Gravatar::RATING_PG)
 *   			->render('avatar_class', FALSE);
 *  
 *   echo $avatar->url(TRUE);
 *   echo $avatar->size();
 *  
 * @see http://gravatar.com/site/implement/images/
 * @see http://ru.gravatar.com/site/implement/profiles/
*/
abstract class Model_Gravatar extends Model
{
	// Image URL pattern
	const URL = 'http%s://www.gravatar.com/avatar/%s?s=%d&d=%s&%s&r=%s';

	// Do not load any image if none is associated with the email hash
	const IMAGE_404 = '404';
	// Mystery-man a simple, cartoon-style silhouetted outline of a person.
	const IMAGE_MM  = 'mm';
	// A geometric pattern based on an email hash
	const IMAGE_IDENTICON  = 'identicon';
	// A generated 'monster' with different colors, faces, etc
	const IMAGE_MONSTERID  = 'monsterid';
	// Generated faces with differing features and backgrounds
	const IMAGE_WAVATAR  = 'wavatar';
	// Awesome generated, 8-bit arcade-style pixelated faces
	const IMAGE_RETRO  = 'retro';
	// A transparent PNG image (border added to HTML below for demonstration purposes)
	const IMAGE_BLANK  = 'blank';

	// Suitable for display on all websites with any audience type.
	const RATING_G  = 'g';
	// May contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence.
	const RATING_PG = 'pg';
	// May contain such things as harsh profanity, intense violence, nudity, or hard drug use.
	const RATING_R  = 'r';
	// May contain hardcore sexual imagery or extremely disturbing violence.
	const RATING_X  = 'x';

	// Default config group
	public static $default = 'default';
	// User email address
	protected $_email;
	// Image size
	protected $_size = 80;
	// 
	protected $_default_image = self::IMAGE_404;
	// 
	protected $_force_default = FALSE;
	// 
	protected $_rating = self::RATING_G;

	/**
	 * Gravatar factory
	 */
	public static function factory($email = NULL, $group = NULL)
	{
		return new Gravatar($email, $group);
	}

	/**
	 * 
	 */
	protected function __construct($email = NULL, $group = NULL)
	{
		// Set email property
		if ($email)
		{
			$this->email($email);
		}
		// If not set use default group
		if ( ! $group)
		{
			$group = self::$default;
		}
		// Load config group
		$config = Kohana::$config->load('gravatar')->get($group);
		// Set property
		foreach ($config as $key => $value)
		{
			$this->{$key}($value);
		}
	}

	/**
	 * Create utl
	 */
	public function url($secure = FALSE)
	{
		$secure = ($secure ? 's' : '');
		
		return sprintf(self::URL, $secure, $this->_email, $this->_size, 
			$this->_default_image, $this->_force_default, $this->_rating);
	}

	/**
	 * Create image tag
	 */
	public function render($class = NULL, $secure = FALSE)
	{
		if ( ! $this->_email)
		{
			throw new Kohana_Exception('Can`t render - not specified email');
		}
		
		$attributes = array('alt' => 'Gravatar', 'width' => $this->_size, 'height' => $this->_size);
		
		if ($class)
		{
			$attributes =+ array('class' => $class);
		}
		
		return HTML::image($this->url($secure), $attributes);
	}

	/**
	 * Sets or gets email
	 */
	public function email($value = NULL)
	{
		if ($value)
		{
			if ( ! Valid ::email($value) OR ! Valid ::email_domain($value))
			{
				throw new Kohana_Exception('Invalid e-mail address');
			}
			$this->_email = md5(strtolower(trim($value)));
			
			return $this;
		}
		return $this->_email;
	}

	/**
	 * Sets or gets image size
	 */
	public function size($value = NULL)
	{
		if ($value)
		{
			if ($value < 30 OR $value > 1000)
			{
				throw new Kohana_Exception('Size must be greater than 30 and less than 1000');
			}
			$this->_size = $value;
			
			return $this;
		}
		return $this->_size;
	}

	/**
	 * Sets or gets default image
	 */
	public function default_image($value = NULL)
	{
		if ($value)
		{
			$default = array(self::IMAGE_404, self::IMAGE_MM, self::IMAGE_IDENTICON, 
				self::IMAGE_MONSTERID, self::IMAGE_WAVATAR, self::IMAGE_RETRO, self::IMAGE_BLANK);
			
			if ( ! in_array($value, $default) AND ! Valid::url($url))
			{
				throw new Kohana_Exception('Invalid default image');
			}
			$this->_default_image = urlencode($value);
			
			return $this;
		}
		return $this->_default_image;
	}

	/**
	 * Sets or gets force default image
	 */
	public function force_default($value = NULL)
	{
		if ( ! is_null($value))
		{
			$this->_force_default = ( (bool) $value ? '&f=y' : '');
			
			return $this;
		}
		return $this->_force_default;
	}

	/**
	 * Sets or gets image rating
	 */
	public function rating($value = NULL)
	{
		if ($value)
		{
			if ( ! in_array($value, array(self::RATING_G, self::RATING_PG, self::RATING_R,self::RATING_X)))
			{
				throw new Kohana_Exception('Invalid rating value');
			}
			$this->_rating = $value;
			
			return $this;
		}
		return $this->_rating;
	}

	/**
	 * Convert to string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Magic getter
	 */
	public function __get($name)
	{
		return $this->{'_'.$name};
	}

	/**
	 * Magic setter
	 */
	public function __set($name, $value)
	{
		return $this->{$name}($value);
	}

} // End Gravatar
