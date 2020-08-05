<?php

require 'Connect.php';


class Movie
{


   public function importMovies($title, $storyline, $imdbRating, $posterurl, $releaseDate)
   {

       # Try/catch block, insert movies
       try {

           $sth = Connect::getInstance()->db->prepare("INSERT INTO movies (`title`, `storyline`, `imdbRating`, `posterurl`, `releaseDate`)
                                                                    VALUES (:title, :storyline, :imdbRating, :posterurl, :releaseDate)");
           $sth->bindParam(':title', $title);
           $sth->bindParam(':storyline', $storyline);
           $sth->bindParam(':imdbRating', $imdbRating);
           $sth->bindParam(':posterurl', $posterurl);
           $sth->bindParam(':releaseDate', $releaseDate);
           $sth->execute();


       } catch (PDOException $e) {
           echo 'Database error!' . $e->getMessage();
       }
   }

    # Get all movies stored in database
    public static function getAllMovies()
    {

        try {
            $sth = Connect::getInstance()->db->prepare("SELECT * FROM `movies`");
            $sth->execute();
            $results = $sth->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($results);

        } catch (PDOException $e) {
            echo 'Database error!' . $e->getMessage();
        }

    }

    # Get recommended movie list... higher that 7.0
    public static function getRecommendedMovies()
    {

        try {
            $sth = Connect::getInstance()->db->prepare("SELECT * FROM `movies` WHERE imdbRating > 6.9");
            $sth->execute();
            $results = $sth->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($results);

        } catch (PDOException $e) {
            echo 'Database error!' . $e->getMessage();
        }
    }

    # Insert movie/day combinations
    public function sortable($day, $movie)
    {
        $getDay = $this->getDayID($day);
        $getMovie = $this->getMovieID($movie);

        try {

            $sth = Connect::getInstance()->db->prepare("INSERT INTO movies_days (`day_id`, `movie_id`)
                                                                    VALUES (:day_id, :movie_id)");
            $sth->bindParam(':day_id', $getDay);
            $sth->bindParam(':movie_id', $getMovie);
            $sth->execute();

        } catch (PDOException $e) {
            echo 'Database error!' . $e->getMessage();
        }
    }

    # Private functions not accessible outside the class
    # Get day ID stored in the database by short name
    private function getDayID($day)
    {

        try {
            $sth = Connect::getInstance()->db->prepare("SELECT id FROM `days` WHERE short = '$day'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_OBJ);

            foreach ($result as $res) {
                return $res->id;
            }
        }catch (PDOException $e) {
            echo 'Database error!' . $e->getMessage();
        }

    }

    # Get movie ID stored in the database by title
    private function getMovieID($movie)
    {

        try {
            $sth = Connect::getInstance()->db->prepare("SELECT id FROM `movies` WHERE title = '$movie'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_OBJ);

            foreach ($result as $res) {
                return $res->id;
            }
        }catch (PDOException $e) {
            echo 'Database error!' . $e->getMessage();
        }

    }

}

$instance = new Movie();