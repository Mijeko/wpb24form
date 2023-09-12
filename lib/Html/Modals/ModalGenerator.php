<?php

namespace Html\Modal;

use Html\Modal\Builder\IModal;

class ModalGenerator
{
    const MODAL_HEADER = 'modal.header';

    public static function show(IModal $modal)
    {
        $modal->response();
    }
}