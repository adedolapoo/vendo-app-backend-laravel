<?php namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Sentry;

class UserGroupsTableSeeder extends Seeder {

	public function run()
	{
		\DB::table('users_groups')->truncate();

		$user = Sentry::getUserProvider()->findByLogin('admin@admin.com');
		$group = Sentry::getGroupProvider()->findByName('Admin');
		$user->addGroup($group);

		/*$user = Sentry::getUserProvider()->findByLogin('admin@parent.com');
		$group = Sentry::getGroupProvider()->findByName('Parent');
		$user->addGroup($group);*/
	}

}