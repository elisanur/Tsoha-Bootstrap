<?php

class CategoryController extends BaseController {

    public static function listAll() {
        $categories = Category::all();
        View::make('category/categories.html', array('categories' => $categories));
    }

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'name' => strtolower($params['name']),
        );
        
        $category = new Category($attributes);
        
        $errors = $category->errors();
        if (count($errors) == 0) {
            $category->save();
            Redirect::to('/account/new_poster', array('message' => 'Category added!'));
        } else {
            View::make('/category/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function create() {
        View::make('category/new.html');
    }

    public static function show($name) {
        $category = Category::find($name);
        
        if ($category == null){
            Redirect::to('/categories', array('message' => 'Category not found!'));
        }
        
        $posters = Poster::findPostersByCategory($category->name);
        View::make('category/show.html', array('category' => $category, 'posters' => $posters));
    }

}
