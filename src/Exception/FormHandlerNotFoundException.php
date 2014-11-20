<?php

namespace Mitom\Bundle\FormHandlerBundle\Exception;


class FormHandlerNotFoundException extends \Exception
{
    public function __construct($type) {
        parent::__construct(sprintf('No FormHandler could be found for %s type', $type));
    }
} 