<?php

namespace Phpg\Application\Controller\Admin;

use Phpg\Application\Controller\Base as BaseController;

class Base extends BaseController
{
    public function afterExecuteRoute()
    {
        $this->view->setViewsDir($this->view->getViewsDir() . 'admin/');
    }
}
