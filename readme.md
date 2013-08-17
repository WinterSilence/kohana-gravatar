## A globally recognized avatar aka Gravatar helper class for Kohana framework 3.3
Gravatar - a picture that follows you from site to site, appearing when you send a comment or write a blog. https://gravatar.com/

### Usage examples:
	echo Gravatar::factory('test@site.com', 'big')->render();
	
	$avatar = Gravatar::factory();
	echo $avatar->email('test@site.com')
				->size(80)
				->default_image(Gravatar::IMAGE_MM)
				->force_default(FALSE)
				->rating(Gravatar::RATING_PG)
				->render('avatar_class', FALSE);
	
	echo $avatar->url(TRUE);
	echo $avatar->size();


### TODO:
- Add profile requests http://ru.gravatar.com/site/implement/profiles/