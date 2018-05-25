<?php
class WebpageController extends BaseController {
   

    public function getIndex() {
    	return View::make('webpage.index');
    }
}