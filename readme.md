### A globally recognized avatar aka Gravatar helper class for Kohana framework 3.3
Gravatar - a picture that follows you from site to site, 
appearing when you send a comment or write a blog. `http://gravatar.com/`

### Usage:
Create image tag with Gravatar:
<pre>
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
</pre>
Get profile data:
<pre>
var_export($gravatar->profile_data(array('photos', 'accounts')));
</pre>
Get URL:
<pre>
echo $gravatar->url($secure);
</pre>
Get properties:
<pre>
echo $gravatar->size();
echo $gravatar->rating();
</pre>