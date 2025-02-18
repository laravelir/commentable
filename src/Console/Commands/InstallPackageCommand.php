<?php

namespace Laravelir\Commentable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPackageCommand extends Command
{
    protected $signature = 'commentable:install';

    protected $description = 'Install the commentable Package';

    public function handle()
    {
        $this->line("\t... Welcome To Package Installer ...");

        if (File::exists(config_path('commentable.php'))) {
            $confirm = $this->confirm("commentable.php already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishConfig();
            } else {
                $this->error("you must overwrite config file");
                exit;
            }
        } else {
            $this->publishConfig();
        }

        if (!empty(File::glob(database_path('migrations\*_create_commentable_table.php')))) {
            $list  = File::glob(database_path('migrations\*_create_commentable_table.php'));
            collect($list)->each(function ($item) {
                File::delete($item);
                $this->warn("Deleted: " . $item);
            });
            $this->publishMigration();
        } else {
            $this->publishMigration();
        }

        $this->info("Package Successfully Installed.\n");
        $this->info("\t\tGood Luck.");
    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "Laravelir\\Commentable\\Providers\\CommentableServiceProvider",
            '--tag'      => 'commentable-config',
            '--force'    => true
        ]);
    }

    private function publishMigration()
    {
        $this->call('vendor:publish', [
            '--provider' => "Laravelir\\Commentable\\Providers\\CommentableServiceProvider",
            '--tag'      => 'commentable-migrations',
            '--force'    => true
        ]);
    }
}
