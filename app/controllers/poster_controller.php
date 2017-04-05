<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PosterController extends BaseController {

    public static function posters() {
        $posters = Poster::all();
        View::make('posters.html', array('posters' => $posters));
    }

    public static function posterShow($id) {
        $poster = Poster::find($id);
        View::make('poster_show.html', array('poster' => $poster));
    }
    
    public static function usersPosters($id) {
        $posters = Poster::allFromUser($id);
        View::make('account.html', array('posters' => $posters));
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
        
        $poster = new Poster($attributes);
        $errors = $poster->errors();
        
        if (count($errors)==0){
            $poster->save();
            Redirect::to('/account', array('message' => 'Poster was added!'));
        } else {
          View::make('new_poster.html', array('errors' => $errors, 'attributes' => $attributes));  
        }

    }

    public static function create() {
        View::make('new_poster.html');
    }

    public static function editPoster($id) {
        $poster = Poster::find($id);
        View::make('edit_poster.html', array ('attributes'=> $poster));
    }
    
    public static function update($id){
        $params = $_POST;
        
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'artist' => $params['artist'],
            'price' => $params['price'],
            'location' => $params['location'],
            'height' => $params['height'],
            'width' => $params['width']
        );
        
        $poster = new Poster($attributes);
        $errors = $poster->errors();
        
        if(count($errors) > 0){
            View::make('edit_poster.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $poster->update();
            
            Redirect::to('/posters/' . $poster->id, array('message' => 'Poster has been edited!'));
        }
    }
    
    public static function destroy($id){
        $poster = new Poster(array('id' => $id));
        $poster->destroy();
        Redirect::to('/', array('message' => 'Poster was deleted successfully!'));
    }
    
}
