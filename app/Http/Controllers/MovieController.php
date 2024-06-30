<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $MAX_BANNER = 3;
        $MAX_MOVIE_ITEM = 10;
        $MAX_TV_SHOWS_ITEM = 10;

        $bannerResponse = Http::get("{$baseURL}/trending/movie/week", [
            'api_key' => $apiKey,
        ]);

        $bannerArray = [];

        if ($bannerResponse->successful()) {
            $resultArray = $bannerResponse->object()->results;

            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    array_push($bannerArray, $item);

                    if (count($bannerArray) == $MAX_BANNER) {
                        break;
                    }
                }
            }
        }

        $topMoviesResponse = Http::get("{$baseURL}/movie/top_rated", [
            'api_key' => $apiKey,
        ]);

        $topMOviesArray = [];

        if ($topMoviesResponse->successful()) {
            $resultArray = $topMoviesResponse->object()->results;
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    array_push($topMOviesArray, $item);

                    if (count($topMOviesArray) == $MAX_MOVIE_ITEM) {
                        break;
                    }
                }
            }
        }

        $topTVShowResponse = Http::get("{$baseURL}/tv/top_rated", [
            'api_key' => $apiKey,
        ]);

        $topTVShowsArray = [];

        if ($topTVShowResponse->successful()) {
            $resultArray = $topTVShowResponse->object()->results;
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    array_push($topTVShowsArray, $item);

                    if (count($topTVShowsArray) == $MAX_TV_SHOWS_ITEM) {
                        break;
                    }
                }
            }
        }

        return view('home', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'banner' => $bannerArray,
            'topMovies' => $topMOviesArray,
            'topTVShows' => $topTVShowsArray,
        ]);
    }

    public function movies()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        $movieResponse = Http::get("{$baseURL}/discover/movie", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $movieArray = [];

        if ($movieResponse->successful()) {
            $resultArray = $movieResponse->object()->results;
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    array_push($movieArray, $item);
                }
            }
        }

        return view('movie', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movies' => $movieArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter,
        ]);
    }

    public function tvShows()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        $tvResponse = Http::get("{$baseURL}/discover/tv", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $tvArray = [];

        if ($tvResponse->successful()) {
            $resultArray = $tvResponse->object()->results;
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    array_push($tvArray, $item);
                }
            }
        }

        return view('tv', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'tvShows' => $tvArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter,
        ]);
    }

    public function search()
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        return view('search', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
        ]);
    }

    public function movieDetails($id)
    {
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseURL}/movie/{$id}", [
            'api_key' => $apiKey,
            'append_to)response' => 'videos'
        ]);

        $movieData = null;

        if ($response->successful()) {
            $movieData = $response->object();
        }

        return view('movie_details', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movieData' => $movieData
        ]);
    }
}
