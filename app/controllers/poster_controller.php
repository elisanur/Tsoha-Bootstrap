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

        $categories = $params['categories'];

        $attributes = array(
            'name' => $params['name'],
            'publisher' => $publisher,
            'artist' => $params['artist'],
            'price' => $params['price'],
            'location' => $params['location'],
            'height' => $params['height'],
            'width' => $params['width'],
            'image' => $params['image'],
            'categories' => array()
        );

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
            Category::savePosterCategory($publisher, $category);
        }

        $poster = new Poster($attributes);
        $errors = $poster->errors();

        if (count($errors) == 0) {
            $poster->save();
            Redirect::to('/account', array('message' => 'Poster was added!'));
        } else {
            View::make('poster/new_poster.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function addImage($conn, $filename) {
// Open and read the file that was uploaded
        $fp = fopen($filename, "r");
        if ($fp == false)
            echo "Error opening file";
// Begin a PostgreSQL transaction
        pg_exec("begin");

// create the large object and get the lo id
        $lo_id = pg_locreate();

// have postgresql open the large object for writing
        $lo_fp = pg_loopen($lo_id, "w");

// for ever 8192 bytes of the uploaded file
        while ($nbytes = fread($fp, 8192)) {

// write to the large object
            $tmp = pg_lowrite($lo_fp, $nbytes);

// handle possible error
            if ($tmp < $nbytes) {
                echo "error while writing large object";
            }
        }

// close the large object
        pg_loclose($lo_fp);

// commit the postgresql transaction
        pg_exec("commit");

// close the uploaded file
        fclose($fp);


        if (!is_int($lo_id)) {
// return false
            return false;
        }

        if (is_int($lo_id)) {
// return large object id
            return $lo_id;
        }
    }

    public static function ReadImage($lo_id, $filesize) {
        pg_exec("begin");

        $handle = pg_lo_open($lo_id, "r");
        $data = pg_lo_read($handle, $filesize);
//pg_lo_close($lo_fp);
//pg_exec($conn,"commit");
        return $data;
    }

    public static function WriteImageToFile($id, $filename, $filesize) {
        $data = ReadImage($id, $filesize);
        $f = fopen($filename, "w");
        if (fwrite($f, $data) == FALSE) {
            $message = 'ERROR';
//getTokenValue("CANT_WRITE_FILE",$lang)."dbresource.txt";
        }
        fclose($f);
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
            View::make('poster/edit_poster.html', array('errors' => $errors, 'attributes' => $attributes));
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
