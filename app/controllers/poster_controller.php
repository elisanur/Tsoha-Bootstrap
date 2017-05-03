<?php

/*
 */

class PosterController extends BaseController {

    public static function posters() {
        $posters = Poster::all();

        View::make('poster/posters.html', array('posters' => $posters));
    }

    public static function posterShow($id) {
        $poster = Poster::find($id);
        View::make('poster/poster_show.html', array('poster' => $poster));
    }

    public static function usersPosters($id) {
        $posters = Poster::allFromUser($id);
        View::make('poster/users_posters.html', array('posters' => $posters));
    }

    public static function userLoggedInPosters() {
        $user = self::get_user_logged_in();
        $posters = Poster::allFromUser($user->id);
        View::make('user/account.html', array('posters' => $posters));
    }

    public static function store() {
        $params = $_POST;
        $publisher = self::get_user_logged_in_id();

        $categories = array();

        if (isset($params['categories'])) {
            $categories = $params['categories'];
        }

        $image = $_FILES['image'];

        $error = array();

        if ($image['size'] == 0) {
            $error[] = 'Image upload failed';
        }

        $attributes = array(
            'name' => $params['name'],
            'publisher' => $publisher,
            'artist' => $params['artist'],
            'price' => $params['price'],
            'location' => $params['location'],
            'height' => $params['height'],
            'width' => $params['width'],
            'image' => base64_encode(file_get_contents($image["tmp_name"])),
            'fileType' => $image[strtolower("type")],
            'categories' => array()
        );

        $poster = new Poster($attributes);

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
            Category::savePosterCategory($poster->id, $category);
        }

        $errors = $poster->errors();
        $errors = array_merge($errors, $error);

        $categories = Category::all();

        if (count($errors) == 0) {
            $poster->save();
            Redirect::to('/account', array('message' => 'Poster was added!'));
        } else {
            View::make('poster/new_poster.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => $categories));
        }
    }

    public static function create() {
        $categories = Category::all();
        View::make('poster/new_poster.html', array('categories' => $categories));
    }

    public static function editPoster($id) {


        $poster = Poster::find($id);


        $categories = Category::all();

        $posterCategories = Category::posterCategories($id);

        $posterCategoryNames = array();

        foreach ($posterCategories as $category) {
            $posterCategoryNames[] = $category->name;
        }


        $userid = $poster->publisher;

        if ($userid == parent::get_user_logged_in_id()) {
            View::make('poster/edit_poster.html', array('attributes' => $poster, 'categories' => $categories, 'posterCategoryNames' => $posterCategoryNames));
        } else {
            Redirect::to('/', array('message' => 'Please stick to your own '
                . 'business. You don´t have permission to enter that page. Kind regards, admin.'));
        }
    }

    public static function update($id) {
        $params = $_POST;
        $categories = $params['categories'];
        $user = parent::get_user_logged_in_id();

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'artist' => $params['artist'],
            'price' => $params['price'],
            'location' => $params['location'],
            'height' => $params['height'],
            'width' => $params['width'],
            'categories' => array()
        );

        $posterCategories = Category::posterCategories($id);

        foreach ($posterCategories as $posterCategory) {
            Category::destroyPosterCategory($id);
        }

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
            Category::savePosterCategory($user, $category);
        }

        $poster = new Poster($attributes);
        $errors = $poster->errors();



        if (count($errors) > 0) {
            $categories = Category::all();
            $posterCategories = Category::posterCategories($id);

            $posterCategoryNames = array();

            foreach ($posterCategories as $category) {
                $posterCategoryNames[] = $category->name;
            }

            View::make('poster/edit_poster.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => $categories, 'posterCategoryNames' => $posterCategoryNames));
        } else {
            $poster->update();

            Redirect::to('/posters/' . $poster->id, array('message' => 'Poster has been edited!'));
        }
    }

    public static function destroy($id) {
        $poster = new Poster(array('id' => $id));
        $poster->destroy();
        Redirect::to('/', array('message' => 'Poster was deleted successfully!'));
    }

}
