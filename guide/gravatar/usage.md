# Usage

## Create model

Use [Gravatar::factory] method for create Gravatar model.
~~~
$gravatar = Gravatar::factory($email, $config_group);
~~~

## Properties

Gets
~~~
$size = $gravatar->size();
// or
$size = $gravatar->size;
~~~
Sets
~~~
$gravatar->size(70);
// or
$gravatar->size = 70;
~~~
Class have constants for sets rating and default_image properties.
~~~
$gravatar->rating(Gravatar::RATING_PG);
$gravatar->default_image = Gravatar::IMAGE_MM;
~~~

## Image URL

Use [Gravatar::url] method for get Gravatar image URL. 
~~~
$url = $gravatar->url($is_secure_request);
~~~

## HTML image tag

Use [Gravatar::render] method for create HTML image tag. 

Basic way:
~~~
$tag = Gravatar::factory('me@site.com', 'config_group')
	->render('add_css_class_to_tag', $is_secure_request);
~~~
Short way:
~~~
$tag = Gravatar::factory('user@site.com', 'big');
~~~
Full way:
~~~
$tag = Gravatar::factory()
	->email('user@site.com')
	->size(50)
	->default_image(Gravatar::IMAGE_MM)
	->force_default(FALSE)
	->rating(Gravatar::RATING_PG)
	->render('gravatar_class', FALSE);
~~~

## Profile data

Use [Gravatar::profile_data] method for get profile data. 
More info: `http://gravatar.com/site/implement/profiles/php/`.
~~~
$data = $gravatar->profile_data(array('photos', 'accounts'));
~~~