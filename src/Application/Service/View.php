<?php

namespace Phpg\Application\Service;

use Phalcon\Mvc\View as PhalconView;

class View extends PhalconView
{
    /**
     * @return bool
     */
    public function isPicked()
    {
        return !empty($this->_pickView);
    }
}

