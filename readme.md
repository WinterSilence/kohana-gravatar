# Kohana Gravatar
A globally recognized avatar aka Gravatar module for Kohana framework.

Gravatar - a picture that follows you from site to site, 
appearing when you send a comment or write a blog. 
For more info see: `http://gravatar.com/`.

### Properties:
- Flexible configuration using the groups
- Getting the Gravatar URL
- Getting the image tag
- Getting the profile data

### Usage examples:
Create image tag with Gravatar:
~~~
echo Gravatar::factory('me@site.com', 'cfg_group')->render('img_class_attr', $secure);
// Short way
echo Gravatar::factory('user@site.com', 'big');
// Configurate in code
$gravatar = Gravatar::factory();
echo $gravatar->email('user@site.com')
	->size(50)
	->default_image(Gravatar::IMAGE_MM)
	->force_default(FALSE)
	->rating(Gravatar::RATING_PG)
	->render('avatar_class', FALSE);
~~~
Get profile data:
~~~
var_export($gravatar->profile_data(array('photos', 'accounts')));
~~~
Get URL:
~~~
echo $gravatar->url($secure);
~~~
Get properties:
~~~
echo $gravatar->size();
echo $gravatar->rating();
~~~
