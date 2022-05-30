<?php

namespace App\Repositories;

use App\Interfaces\MovieRepositoryInterface;
use App\Models\Catalogue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MovieRepository implements MovieRepositoryInterface 
{
    public function getMoviesInCinema($cinema=null) 
    {
        return Catalogue::whereHas('cinema.user', function (Builder $query) {
            $query->where('id', request()->user()->id);
        })->when($cinema, function($query) use ($cinema){
            $query->whereHas('cinema', function (Builder $query) use ($cinema) {
                $query->where('cinema_id', $cinema);
            });
        })->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getMovieById($orderId) 
    {
        return Catalogue::with(['cinema.user' => function ($query) {
            $query->where('id', request()->user()->id);
        }])->where('movie_id', request()->movie_id)->first();
    }

    public function deleteMovie($movieId) 
    {
        Catalogue::destroy($movieId);
    }

    public function updateOrCreate(array $data) 
    {
        Catalogue::updateOrCreate(
            ['user_id' => $data['user_id'], 'cinema_id' => $data['cinema_id'], 'movie_id' => $data['movie_id']],
            $data
        );

        return ;
    }

    public function getAllMovies() 
    {
        return Catalogue::when(request()->search, function($query){
            $query->where('cinema_id', request()->search)->orWhere('movie', 'like', "%".request()->search."%")->orWhere('description', 'like', "%".request()->search."%");
        })->orderBy('created_at', 'desc')->paginate(15);
    }
}
