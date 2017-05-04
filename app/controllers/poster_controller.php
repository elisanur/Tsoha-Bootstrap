<?php

/*
 */

class PosterController extends BaseController {

    public static function unsoldPosters() {
        $posters = Poster::allUnsoldPosters();
        View::make('poster/posters.html', array('posters' => $posters));
    }

    public static function posterShow($id) {
        settype($id, "integer");
        $poster = Poster::find($id);

        if ($poster == null) {
            Redirect::to('/posters', array('message' => 'Poster not found!'));
        }

        View::make('poster/poster_show.html', array('poster' => $poster));
    }

    public static function usersUnsoldPosters($id) {
        $user = User::find($id);
        $posters = Poster::allUnsoldPostersFromUser($id);

        if (!isset($user)) {
            Redirect::to('/users', array('message' => 'User not found!'));
        }

        View::make('poster/users_posters.html', array('posters' => $posters, 'user' => $user->name));
    }

    public static function usersSales() {
        $sales = User::sales();
        View::make('user/sales.html', array('sales' => $sales));
    }

    public static function usersOrders() {
        $orders = User::orders();
        View::make('user/orders.html', array('orders' => $orders));
    }

    public static function store() {
        $params = $_POST;
        $publisher = self::get_user_logged_in_id();

        $categories = array();
        if (isset($params['categories'])) {
            $categories = $params['categories'];
        }

        $errors = array();
        
        //Jos kuva on liian iso, $_POST array tyhjenee.
        if (!isset($_FILES['image'])){
            $errors[] = 'Image size is too big';
            View::make('poster/new_poster.html', array('errors' => $errors, 'attributes' => $params, 'categories' => Category::all())); 
        }
        
        $imageData = $_FILES['image'];
        
        // file_get_contents hakee sisällön ja johtaa poikkeustilaan jo hakuvaiheessa, jos sisältöä ei löydy, tämän vuoksi tavalliset
        // tarkistukset if-lauseen avulla eivät toimineet ja toistaiseksi ratkaistu try catch lauseen avulla. 
        // Tässä tapauksessa myös $_POST array tyhjenee
        try {
            file_get_contents($imageData["tmp_name"]);
        } catch (Exception $exc) {
            $errors[] = 'Image has to be added!';
            $categories = Category::all();
            View::make('poster/new_poster.html', array('errors' => $errors,'categories' => $categories));
            
        }

        $image = new Image(array(
            'image' => base64_encode(file_get_contents($imageData["tmp_name"])),
            'fileType' => $imageData[strtolower("type")], 
            'filePath' => $imageData["tmp_name"] 
        ));
        
        $errors = array_merge($errors, $image->errors());

        $attributes = array(
            'name' => trim($params['name']),
            'publisher' => $publisher,
            'artist' => trim($params['artist']),
            'price' => $params['price'],
            'location' => trim($params['location']),
            'height' => $params['height'],
            'width' => $params['width'],
            'image' => $image,
            'categories' => array()
        );

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
        }

        $poster = new Poster($attributes);
        $errors = array_merge($errors, $poster->errors());

        if ($imageData['size'] == 0) {
            $error[] = 'Image upload failed';
        }

        if (count($errors) == 0) {
            $poster->save();
            foreach ($categories as $category) {
                Category::savePosterCategory($poster->id, $category);
            }
            Redirect::to('/account', array('message' => 'Poster was added!'));
        } else {
            $categories = Category::all();
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

        $categories = array();

        if (isset($params['categories'])) {
            $categories = $params['categories'];
        }

        $user = parent::get_user_logged_in_id();

        $image = Image::findImageByPoster($id);

        $attributes = array(
            'id' => $id,
            'name' => trim($params['name']),
            'artist' => trim($params['artist']),
            'price' => $params['price'],
            'location' => trim($params['location']),
            'height' => $params['height'],
            'width' => $params['width'],
            'image' => $image->image,
            'fileType' => $image->fileType,
            'categories' => array()
        );

        $posterCategories = Category::posterCategories($id);

        foreach ($posterCategories as $posterCategory) {
            Category::destroyPosterCategoryByPosterId($id);
        }

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
        }
        $poster = new Poster($attributes);

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
            Category::savePosterCategory($poster->id, $category);
        }

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
