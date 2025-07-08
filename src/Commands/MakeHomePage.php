<?php

namespace SmartCms\Kit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SmartCms\Kit\Models\Page;

class MakeHomePage extends Command
{
    protected $signature = 'make:home-page';

    protected $description = 'Make scms home page';

    public function handle()
    {
        $page = Page::query()->updateOrCreate([
            'name' => 'Home',
        ], [
            'slug' => '',
            'status' => true,
            'is_system' => true,
        ]);
        DB::table($page->getTable())->where('id', $page->id)->update([
            'slug' => '',
        ]);
    }
}
