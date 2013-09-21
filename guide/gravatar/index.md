# Gravatar

A globally recognized avatar aka Gravatar module. Gravatar - a picture that follows you from site to site, appearing when you send a comment or write a blog. For more info see: `http://gravatar.com/`.

To enable, open your `application/bootstrap.php` file and modify the call to [Kohana::modules] by including the gravatar module like so:

~~~
Kohana::modules(array(
	...
	'gravatar' => MODPATH.'gravatar', // A globally recognized avatar aka Gravatar
	...
));
~~~

Next, you will then need to [configure](config) the gravatar module.