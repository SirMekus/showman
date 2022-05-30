<?php

namespace App\Interfaces;

interface MovieRepositoryInterface 
{
    public function getMoviesInCinema();
    public function getMovieById($movieId);
    public function deleteMovie($movieId);
    public function updateOrCreate(array $movieDetails);
    public function getAllMovies();
}