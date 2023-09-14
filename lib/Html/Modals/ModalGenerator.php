<?php

namespace Html\Modal;

use Html\Modal\Builder\IModal;

class ModalGenerator
{
    const MODAL_CALCULATE = 'modal.calculate';
    const MODAL_CALLBACK = 'modal.callback';

    public static function show(IModal $modal)
    {
        $modal->response();
    }
}