### A globally recognized avatar aka Gravatar helper class for Kohana framework 3.3
Gravatar - a picture that follows you from site to site, appearing when you send a comment or write a blog. `http://gravatar.com/`

### Usage:
Create image tag with gravatar:
<pre>
echo Gravatar::factory('user@site.com', 'config_group_name')->render('add_this_class_to_img', $secure);
// Short version
echo Gravatar::factory('user@site.com', 'big');
// Configurate in code
$avatar = Gravatar::factory();
echo $avatar->email('user@site.com')
			->size(50)
			->default_image(Gravatar::IMAGE_MM)
			->force_default(FALSE)
			->rating(Gravatar::RATING_PG)
			->render('avatar_class', FALSE);
</pre>
Get URL:
<pre>
echo $avatar->url($secure);
</pre>
Get properties:
<pre>
echo $avatar->size();
echo $avatar->rating();
</pre>

### TODO:
- Add profile requests `http://ru.gravatar.com/site/implement/profiles/`
