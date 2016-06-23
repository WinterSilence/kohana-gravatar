<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * A globally recognized avatar aka [Gravatar](http://gravatar.com/site/implement/) helper class.
 *
 * @package   Gravatar
 * @category  Base
 * @author    WinterSilence <info@handy-soft.ru>
 * @copyright (c) 2013-2014 handy-soft.ru
 * @license   MIT License
 * @link      https://github.com/WinterSilence/kohana-gravatar
*/
abstract class Kohana_Gravatar {

	// Do not load any image if none is associated with the email hash
	const IMAGE_404 = '404';
	// Mystery-man a simple, cartoon-style silhouetted outline of a person.
	const IMAGE_MM = 'mm';
	// A geometric pattern based on an email hash
	const IMAGE_IDENTICON = 'identicon';
	// A generated 'monster' with different colors, faces, etc
	const IMAGE_MONSTERID = 'monsterid';
	// Generated faces with differing features and backgrounds
	const IMAGE_WAVATAR = 'wavatar';
	// Awesome generated, 8-bit arcade-style pixelated faces
	const IMAGE_RETRO = 'retro';
	// A transparent PNG image (border added to HTML below for demonstration purposes)
	const IMAGE_BLANK = 'blank';

	// Suitable for display on all websites with any audience type.
	const RATING_G = 'g';
	// Rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence.
	const RATING_PG = 'pg';
	// Such things as harsh profanity, intense violence, nudity, or hard drug use.
	const RATING_R = 'r';
	// Hardcore sexual imagery or extremely disturbing violence.
	const RATING_X = 'x';

	/**
	 * @var string Default config group
	 */
	public static $default = 'default';

	/**
	 * @var string Email address
	 */
	protected $_email;

	/**
	 * @var integer Image size
	 */
	protected $_size = 80;

	/**
	 * @var string Used if not image is undefined
	 */
	protected $_default_image = self::IMAGE_404; 

	/**
	 * @var bool Not use user image
	 */
	protected $_force_default = FALSE;

	/**
	 * @var string User image rating
	 */
	protected $_rating = self::RATING_G;

	/**
	 * Gravatar factory
	 * 
	 * @access public
	 * @static
	 * @param  string  $email  User email address
	 * @param  string  $group  Config group name
	 * @result Gravatar
	 */
	public static function factory($email = NULL, $group = NULL)
	{
		return new self($email, $group);
	}

	/**
	 * Create Gravatar model
	 * 
	 * @access protected
	 * @param  string  $email  User email address
	 * @param  string  $group  Config group name
	 * @result void
	 */ 
	protected function __construct($email, $group)
	{
		// Set email property
		if ($email)
		{
			$this->email($email);
		}

		// If group is undefined uses default property
		if ( ! $group)
		{
			$group = self::$default;
		}

		// Loading configuration group
		$config = Kohana::$config->load('gravatar')->get($group);

		if (empty($config))
		{
			throw new Kohana_Exception(
				'Gravatar: config group `gravatar.:group` not exists', 
				array(':group' => $group)
			);
		}

		// Sets properties
		foreach ($config as $key => $value)
		{
			$this->{$key}($value);
		}
	}

	/**
	 * Get [user profile](http://gravatar.com/site/implement/profiles/php/) data from gravatar.com.
	 * 
	 * @access public
	 * @param  array  $params  Retrieves multiple paths from profile data
	 * @return array
	 * @throw  Kohana_Exception
	 * @uses   I18n::lang
	 * @uses   Request::factory
	 * @uses   Arr::extract
	 */
	public function profile_data(array $params = NULL)
	{
		// Get system language
		$lang = explode('-', I18n::lang(), -1);
		if (empty($lang))
		{
			$lang = I18n::lang();
		}

		$url = sprintf('http://%s.gravatar.com/%s.php', $lang, $this->_email);

		// Makes request to gravatar.com and returns response
		$data = Request::factory($url)->execute()->body();

		if (empty($data))
		{
			throw new Kohana_Exception('Gravatar: profile data request failed');
		}

		// Convert responce data
		$data = unserialize($data);
		$data = $data['entry'][0];

		// Return all ​​or selected data
		return empty($params) ? $data : Arr::extract($data, $params);
	}

	/**
	 * Create URL on gravatar image
	 * 
	 * @access public
	 * @param  bool  $secure  Is secure (ssl/https) request?
	 * @result string
	 */ 
	public function url($secure = FALSE)
	{
		return sprintf(
			'http%s://gravatar.com/avatar/%s?s=%d&d=%s&%s&r=%s', 
			$secure ? 's' : '', 
			$this->_email, 
			$this->_size, 
			$this->_default_image, 
			$this->_force_default, 
			$this->_rating
		);
	}

	/**
	 * Create HTML tag for gravatar image
	 * 
	 * @access public
	 * @param  string  $class   CSS class
	 * @param  bool    $secure  Is secure (ssl/https) request?
	 * @result string
	 * @throw  Kohana_Exception
	 * @uses   HTML::image
	 */ 
	public function render($class = NULL, $secure = FALSE)
	{
		if ( ! $this->_email)
		{
			throw new Kohana_Exception('Gravatar: rendering failed, email is not specified');
		}

		$attributes = array(
			'alt'    => __('Gravatar'), 
			'width'  => $this->_size, 
			'height' => $this->_size, 
			'class'  => $class === NULL ? 'gravatar' : $class,
		);

		return HTML::image($this->url($secure), $attributes);
	}

	/**
	 * Sets or gets email address
	 * 
	 * @access public
	 * @param  string  $value
	 * @result mixed
	 * @throw  Kohana_Exception
	 * @uses   Valid::email
	 * @uses   Valid::email_domain
	 */ 
	public function email($value = NULL)
	{
		if ($value === NULL)
		{
			return $this->_email;
		}

		if ( ! Valid::email($value) OR ! Valid::email_domain($value))
		{
			throw new Kohana_Exception('Gravatar: invalid email value');
		}

		$this->_email = md5(strtolower(trim($value)));

		return $this;
	}

	/**
	 * Sets or gets image size
	 * 
	 * @access public
	 * @param  mixed  $value
	 * @result mixed
	 * @throw  Kohana_Exception
	 */ 
	public function size($value = NULL)
	{
		if ($value === NULL)
		{
			return $this->_size;
		}

		if ($value < 30 OR $value > 1000)
		{
			throw new Kohana_Exception('Gravatar: image size must be more 30 and less 1000');
		}

		$this->_size = (int) $value;

		return $this;
	}

	/**
	 * Sets or gets default image
	 * 
	 * @access public
	 * @param  string  $value
	 * @result mixed
	 * @throw  Kohana_Exception
	 * @uses   Valid::url
	 */ 
	public function default_image($value = NULL)
	{
		if ($value === NULL)
		{
			return $this->_default_image;
		}

		$default_images = array(
			self::IMAGE_404, 
			self::IMAGE_MM, 
			self::IMAGE_IDENTICON, 
			self::IMAGE_MONSTERID,
			self::IMAGE_WAVATAR, 
			self::IMAGE_RETRO, 
			self::IMAGE_BLANK
		);

		if ( ! in_array($value, $default_images) AND ! Valid::url($url))
		{
			throw new Kohana_Exception('Gravatar: invalid default image value');
		}

		$this->_default_image = urlencode($value);

		return $this;
	}

	/**
	 * Sets or gets force default image
	 * 
	 * @access public
	 * @param  mixed  $value
	 * @result mixed
	 */
	public function force_default($value = NULL)
	{
		if ($value === NULL )
		{
			$value = $this->_force_default;
		}

		$this->_force_default = $value ? '&f=y' : '&f=';

		return $this;
	}

	/**
	 * Sets or gets image rating
	 * 
	 * @access public
	 * @param  mixed  $value
	 * @result mixed
	 * @throw  Kohana_Exception
	 */
	public function rating($value = NULL)
	{
		if ($value === NULL)
		{
			return $this->_rating;
		}

		$ratings = array(self::RATING_G, self::RATING_PG, self::RATING_R, self::RATING_X);

		if ( ! in_array($value, $ratings))
		{
			throw new Kohana_Exception('Gravatar: invalid rating value');
		}

		$this->_rating = $value;

		return $this;
	}

	/**
	 * Convert object to string
	 * 
	 * @access public
	 * @result string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Magic gets model properties
	 * 
	 * @access public
	 * @param  string  $name
	 * @result mixed
	 */
	public function __get($name)
	{
		return $this->{$name}();
	}

	/**
	 * Magic sets model properties
	 * 
	 * @access public
	 * @param  string  $name
	 * @param  mixed   $value
	 * @result void
	 */
	public function __set($name, $value)
	{
		$this->{$name}($value);
	}
}
