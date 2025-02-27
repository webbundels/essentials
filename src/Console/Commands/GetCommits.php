<?php

namespace Webbundels\Essentials\Console\Commands;

use Illuminate\Console\Command;
use Webbundels\Essentials\EssentialsServiceProvider;

class GetCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-commits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all the commitss';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $this->info("Getting Commits For the following repos:");
      
      $repos = explode(",", config('essentials.github_repositories'));

      $this->info(config('essentials.github_repositories'));

      foreach ($repos as $repo) {
        
            $page = 1;

            $url = 'https://api.github.com/repos/'.config('essentials.github_owner').'/'.$repo;

            while(true) {

                $this->info("Getting Commits from page ".$page.' of the repo: '. $repo);
                
                $status = EssentialsServiceProvider::getCommitsFromRepository($url, $repo, $page);

                if ($status <= 0) {
                    $this->info("page empy, finished getting commits");
                    break;
                }

                $page += 1;
            }

      }
    }
}
