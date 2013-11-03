<?php

/**
 * @author JacekK
 * @version 0.1
 * @requires YII_DEBUG (bool) or APP_SERVER_VERSION (e.x. '0.2.6') constants
 *
 * Helper class to manage assets paths.
 * Adds timestamp ending to css, js and image files.
 * If YII_DEBUG defined as true, ending changes on every request.
 * If not, APP_SERVER_VERSION is hashed.
 *
 * Usage:
 * echo AssMan::ver() >> jvhvss
 * echo AssMan::css('home-page') >> /my-project/public/css/home-page.css?v=jvhvss
 * echo AssMan::img('logo.png') >> /my-project/public/images/logo.png?v=jvhvss
 * echo AssMan::js('contact-form') >> /my-project/public/js/contact-form.js?v=jvhvss
 * echo AssMan::jsLib('knockout-2.3.0') >> /my-project/public/js/libs/knockout-2.3.0.js?v=jvhvss
 */

class AssMan
{
	private static $_ending = null;

	public static function ver()
	{
		if (self::$_ending === null){
			$toHash = YII_DEBUG ? time() : APP_SERVER_VERSION;
			self::$_ending = substr(md5($toHash), 0, 6);
		}
		return self::$_ending;
	}
	private static function cssAndJsPathTpl($fileType)
	{
		$min = YII_DEBUG ? '' : $fileType=='js' ? 'min/' : '';
		return sprintf('%s/public/%s/%s{0}.%s?v=%s',
			Yii::app()->request->baseUrl,
			$fileType,
			$min,
			$fileType,
			self::ver()
		);
	}

	// css
	private static $_cssPath = null;

	public static function css($fileName)
	{
		if (self::$_cssPath === null)
			self::$_cssPath = self::cssAndJsPathTpl('css');
		return strtr(self::$_cssPath,array('{0}'=>$fileName));
	}

	// js
	private static $_jsLibsPath = null;
	private static $_jsPath = null;

	public static function js($fileName)
	{
		if (self::$_jsPath === null)
			self::$_jsPath = self::cssAndJsPathTpl('js');
		return strtr(self::$_jsPath,array('{0}'=>$fileName));
	}
	public static function jsLib($fileName){
		if (self::$_jsLibsPath === null)
			self::$_jsLibsPath = self::jsLibsPathTpl();
		return strtr(self::$_jsLibsPath,array('{0}'=>$fileName));
	}
	private static function jsLibsPathTpl(){
		return sprintf('%s/public/js/libs/{0}.js?v=%s',
			Yii::app()->request->baseUrl,
			self::ver()
		);
	}
	// images
	private static $imagePath = null;

	public static function img($fileName)
	{
		if (self::$imagePath === null)
			self::$imagePath = self::imagesPathTpl();
		return strtr(self::$imagePath,array('{0}'=>$fileName));
	}
	private static function imagesPathTpl()
	{
		$toHash = YII_DEBUG ? time() : APP_SERVER_VERSION;
		return sprintf('%s/public/images/{0}?v=%s',
			Yii::app()->request->baseUrl,
			self::ver()
		);
	}
}