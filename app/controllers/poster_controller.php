<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PosterController extends BaseController{
  public static function posters(){
    // Haetaan kaikki pelit tietokannasta
    $posters = Poster::all();
    // Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
    View::make('posters.html', array('posters' => $posters));
  }
}