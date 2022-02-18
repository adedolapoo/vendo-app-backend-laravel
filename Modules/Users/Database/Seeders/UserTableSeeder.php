<?php namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->truncate();

		\Sentry::getUserProvider()->create(
			[
				'email'      => 'admin@admin.com',
				'password'   => "admin",
				'activated'  => 1,
			]
		);
	}

}
