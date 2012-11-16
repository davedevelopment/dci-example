<?php

namespace BookShop;

use Silex\Application as SilexApplication;
use Silex\Application\SecurityTrait;
use Silex\Application\UrlGeneratorTrait;

class Application extends SilexApplication
{
    use SecurityTrait;
    use UrlGeneratorTrait;
    
}
