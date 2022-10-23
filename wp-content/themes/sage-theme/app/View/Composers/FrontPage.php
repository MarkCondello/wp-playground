<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{

  // protected static $views = [
  //   'front-page', //automatically bound to related template
  // ];

  public function with()
  {
    return [
      'homeBanner' => "<section class='banner'><h1>Welcome home...</h1></section>",
    ];
  }
}