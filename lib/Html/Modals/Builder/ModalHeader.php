<?php

namespace Html\Modal\Builder;

use Forms\Builder\IForm;
use Html\HtmlHelper;

class ModalHeader implements IModal
{
    private IForm $content;
    private string $title;
    private string $description;

    public function __construct(string $title, IForm $content = null, string $description = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->description = $description;
    }

    public function map(): array
    {
        return [];
    }

    public function build()
    {
        HtmlHelper::openTag('div', ['style' => 'width:600px;']);

        if ($this->title) {
            HtmlHelper::div($this->title, ['class' => 'custom-modal-title']);
        }

        if ($this->description) {
            HtmlHelper::div($this->description, ['class' => 'custom-modal-description']);
        }

        HtmlHelper::tag('div', null, array('class' => 'custom-modal-line'));

        is_null($this->content) ? null : $this->content->response();
        HtmlHelper::endTag('div');
    }

    public function response()
    {
        $this->build();
    }
}