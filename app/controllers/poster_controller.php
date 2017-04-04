<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PosterController extends BaseController {

    public static function posters() {
        // Haetaan kaikki pelit tietokannasta
        $posters = Poster::all();
        // Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
        View::make('posters.html', array('posters' => $posters));
    }

    public static function posterShow($id) {
        $poster = Poster::find($id);
        View::make('poster_show.html', array('poster' => $poster));
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'publisher' => $params['publisher'],
            'artist' => $params['artist'],
            'price' => $params['price'],
            'location' => $params['location'],
            'height' => $params['height'],
            'width' => $params['width'],
            'image' => $params['image']
        );

//        Kint::dump($params);
        
        $poster = new Poster($attributes);
        $errors = $poster->errors();
        
        if (count($errors)==0){
            $poster->save();
            Redirect::to('/account/' . $poster->publisher, array('message' => 'Poster was added!'));
        } else {
          View::make('new_poster.html', array('errors' => $errors, 'attributes' => $attributes));  
        }

    }

    public static function create() {
        View::make('new_poster.html');
    }

    public static function editPoster() {
        View::make('edit_poster.html');
    }

}
