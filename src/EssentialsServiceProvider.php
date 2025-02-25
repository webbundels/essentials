<?php

namespace Webbundels\Essentials;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Attributes\Log;
use Webbundels\Essentials\Models\Commit;

class EssentialsServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }
  public static function getGithubRepo(): string {
    return env("GITHUB_REPOSITORY");
  }

  public static function getGithubURL(): string {
    return 'https://api.github.com/repos/'.env('GITHUB_REPOSITORY');
  }

  // Checks the date with github and our date,
  // If we have outdated data we get the new commits from github.
  public static function refreshCommits() {
        // Try catch here so that if for some reason github would reject our requests, We dont crash
        // If for some reason it goes wrong we call a redirect to the changelog
        try {

        // Create a new http client with the github token that is STORED IN THE .env FILE!
        $client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => 'Bearer '.env('GITHUB_TOKEN'),
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);


        // We first send a get request so that we can compare dates to know if we even should get commits.
        $res = $client->get(EssentialsServiceProvider::getGithubURL());


        $body = json_decode($res->getBody());
        $date = new DateTime($body->updated_at);


        $cached_data = new DateTime('2020/1/1 1:1:1');

        // If we dont have a cache but we do have commits we use that for our date.
        // If for some reason we dont have a cache AND dont have any commits we use the date 2020/1/1 as fallback
        if (Cache::has('last_update')) {
            $cached_data = Cache::get('last_update');
        } else if (Commit::count() > 0) {
            $cached_data = new DateTime(Commit::first()->created_at);
        }

        if ($date > $cached_data || $cached_data == null || count(Commit::all()) == 0) {
            // Send a get request to the github api to get the commits between our dates.
            $commit_request = $client->get(EssentialsServiceProvider::getGithubURL().'/commits?since='. $cached_data->format('Y-m-d') .'T'.  $cached_data->format('H:i:s').'z');
            $raw_commits = json_decode($commit_request->getBody());

            // Then we iterate through the json and create new commits.
            foreach ($raw_commits as $raw_commit) {
                    $new_commit = new Commit();
                    $new_commit->commiter = $raw_commit->commit->author->name;
                    $new_commit->message = $raw_commit->commit->message;
                    $new_commit->created_at = $raw_commit->commit->author->date;

                    $new_commit->save();
            }
        }
        Cache::forever('last_update', $date);
    } catch(Exception $e) {
        return redirect()->to("changelog");
    }

  }

  public function boot()
  {
      $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
      $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
      $this->loadViewsFrom(__DIR__.'/../resources/views', 'EssentialsPackage');
  }
}
