<?php

namespace Webbundels\Essentials;

use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Webbundels\Essentials\Models\Commit;

class EssentialsServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public static function getGithubRepo(): string {
    return 'webbundels/changelog';
  }

  public static function getGithubURL(): string {
    return 'https://api.github.com/repos/webbundels/changelog';
  }

  public static function refreshCommits() {
        $client = new Client([
            // 'base_uri' => 'https://api.github.com/',
            // 'headers' => [
            //     'Authorization' => 'Bearer ' . env('GITHUB_TOKEN'),
            //     'Accept' => 'application/vnd.github.v3+json',
            // ],
        ]);



        $res = $client->get(EssentialsServiceProvider::getGithubURL());


        $body = json_decode($res->getBody());
        $date = new DateTime($body->updated_at);


        $cached_data = new DateTime('2024/1/1 1:1:1');

        if (Cache::has('last_update')) {
            $cached_data = Cache::get('last_update');
        } else if (Commit::count() > 0) {
            $cached_data = new DateTime(Commit::first()->created_at);
        }

        if ($date > $cached_data || $cached_data == null || count(Commit::all()) == 0) {
            // We need commits
            $commit_request = $client->get(EssentialsServiceProvider::getGithubURL().'/commits?since='. $cached_data->format('Y-m-d') .'T'.  $cached_data->format('H:i:s').'z');
            $raw_commits = json_decode($commit_request->getBody());


            foreach ($raw_commits as $raw_commit) {
                    $new_commit = new Commit();
                    $new_commit->commiter = $raw_commit->commit->author->name;
                    $new_commit->message = $raw_commit->commit->message;
                    $new_commit->created_at = $raw_commit->commit->author->date;

                    $new_commit->save();
            }
        }
        Cache::forever('last_update', $date);


  }

  public function boot()
  {
      $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
      $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
      $this->loadViewsFrom(__DIR__.'/../resources/views', 'EssentialsPackage');
  }
}
