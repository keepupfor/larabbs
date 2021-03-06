<?php

use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $links=factory(Link::class)->times(6)->make()->toArray();
        Link::insert($links);
    }
}
