<?php

namespace Webbundels\Essentials;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Attributes\Log;
use Webbundels\Essentials\Console\Commands\GetCommits;
use Webbundels\Essentials\Models\Commit;

class EssentialsServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public static function getCommitsFromRepository($url, $repository, $page) {

      $client = new Client([
        'base_uri' => 'https://api.github.com/',
        'headers' => [
            'Authorization' => 'Bearer '.env('GITHUB_TOKEN'),
            'Accept' => 'application/vnd.github.v3+json',
        ],
      ]);

      // Send a get request to the github api to get the commits between our dates.
      $commit_request = $client->get($url.'/commits?per_page=100&page='.$page);

      $raw_commits = json_decode($commit_request->getBody());

      // Then we iterate through the json and create new commits.
      foreach ($raw_commits as $raw_commit) {
              $new_commit = new Commit();
              $new_commit->commiter = $raw_commit->commit->author->name;
              $new_commit->message = $raw_commit->commit->message;
              $new_commit->created_at = $raw_commit->commit->author->date;
              $new_commit->repository = $repository;
              $new_commit->url = $raw_commit->html_url;

              $new_commit->save();
      }

      return count($raw_commits);
  }

  private static function refreshCommitsForRepository($repository, $url) {
    // Create a new http client with the github token that is STORED IN THE .env FILE!
    $client = new Client([
      'base_uri' => 'https://api.github.com/',
      'headers' => [
          'Authorization' => 'Bearer '.env('GITHUB_TOKEN'),
          'Accept' => 'application/vnd.github.v3+json',
      ],
    ]);

    // We first send a get request so that we can compare dates to know if we even should get commits.
    $res = $client->get($url);


    $body = json_decode($res->getBody());
    $date = new DateTime($body->updated_at);


    $cached_data = new DateTime('2020/1/1 1:1:1');

    // If we dont have a cache but we do have commits we use that for our date.
    // If for some reason we dont have a cache AND dont have any commits we use the date 2020/1/1 as fallback
    if (Commit::where('repository', '=', $repository)->count() > 0) {
      $cached_data = Commit::where('repository', '=', $repository)->orderByDesc('created_at')->first()->created_at;
    } 

    if ($date > $cached_data || $cached_data == null || Commit::count() == 0) {
      // Send a get request to the github api to get the commits between our dates.
      $commit_request = $client->get($url.'/commits?per_page=100&since='. $cached_data->format('Y-m-d') .'T'.  $cached_data->format('H:i:s').'z');

      $raw_commits = json_decode($commit_request->getBody());


      // Then we iterate through the json and create new commits.
      foreach ($raw_commits as $raw_commit) {

              $new_commit = new Commit();
              $new_commit->commiter = $raw_commit->commit->author->name;
              $new_commit->message = $raw_commit->commit->message;
              $new_commit->created_at = $raw_commit->commit->author->date;
              $new_commit->repository = $repository;
              $new_commit->url = $raw_commit->html_url;

              $new_commit->save();
      }


    }
    Cache::forever('last_update', $date);
  }

  // Checks the date with github and our date,
  // If we have outdated data we get the new commits from github.
  public static function refreshCommits() {
        // Try catch here so that if for some reason github would reject our requests, We dont crash
        // If for some reason it goes wrong we call a redirect to the changelog
        //try {

          $repositories = explode(',', env("GITHUB_REPOSITORIES"));

          foreach ($repositories as $repository) {

            $url = 'https://api.github.com/repos/'.env("GITHUB_OWNER").'/'.$repository;

            EssentialsServiceProvider::refreshCommitsForRepository($repository, $url);

          }

       
    // } catch(Exception $e) {
    //     return redirect()->to("changelog")('errors' => ['Could not get commits']);
    // }

  }

  public function boot()
  {
      $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
      $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
      $this->loadViewsFrom(__DIR__.'/../resources/views', 'EssentialsPackage');

      if ($this->app->runningInConsole()) {
        $this->commands([
            GetCommits::class,
        ]);
    }
  }
}
