<?php 
namespace Core\Crud;

class Statuses 
{ 
	protected static $extended = [];

	const draft 		= 'draft'; 
	const scheduled		= 'scheduled'; 
	const published 	= 'published';
	const unpublished 	= 'unpublished';
	const archived 		= 'archived'; 
	const removed 		= 'removed';   
	const blocked 		= 'blocked'; 
	const pending 		= 'pending';
	const activated		= 'activated';
	const deactivated	= 'deactivated';   
	const banned		= 'banned';  

	public static function key(string $status)
	{
		if(isset(self::$status)) {
			return self::$status;
		}

		if(isset(self::$extended[$status])) {
			return self::$extended[$status];
		}

		return $status;
	}

	public static function has(string $status)
	{
		return isset(self::$status) || isset(self::$extended[$status]);
	}

	public static function toArray()
	{
		return array_merge([
			'draft'       => self::draft,
			'scheduled'   => self::scheduled,
			'published'   => self::published,
			'unpublished' => self::unpublished,
			'archived'    => self::archived,
			'removed'     => self::removed,
			'blocked'     => self::blocked,
			'pending'     => self::pending,
			'activated'   => self::activated,
			'deactivated' => self::deactivated,
			'banned'      => self::banned, 
		], self::$extended);
	}

	public static function all()
	{
		return collect(self::toArray());
	}

	public static function extend($key, $value)
	{
		if(! self::has($key)) {
			self::$extended[$key] = $value;
		} 

		return static::class;
	}

	public static function publishing()
	{
		return [
			static::key('draft'),
			static::key('pending'),
			static::key('published'),
			static::key('archived'),
		];
	}

	public static function scheduling()
	{
		return array_merge(static::publishing(), [ 
			static::key('scheduled'), 
		]);
	}
}