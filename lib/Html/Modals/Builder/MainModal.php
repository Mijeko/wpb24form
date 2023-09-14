<?php

namespace Html\Modal\Builder;

use Forms\Builder\IForm;
use Html\Helpers\HtmlHelper;

class MainModal implements IModal
{
    private IForm $content;
    private string $title;
    private $description;

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
        echo HtmlHelper::openTag('div', ['style' => 'width:600px;', 'class' => 'custom-modal']);
        echo HtmlHelper::openTag('div', ['class' => 'custom-modal-container']);

        if ($this->title) {
            echo HtmlHelper::div($this->title, ['class' => 'custom-modal-title']);
        }

        if ($this->description) {
            echo HtmlHelper::div($this->description, ['class' => 'custom-modal-description']);
        }


        echo HtmlHelper::tag('div', null, array('class' => 'custom-modal-line'));

        is_null($this->content) ? null : $this->content->response();
        echo HtmlHelper::endTag('div');

        echo HtmlHelper::openTag('div', array('class' => 'custom-modal-stash'));
            echo HtmlHelper::openTag('div', array('class' => 'custom-modal-success'));
                echo HtmlHelper::tag('div', HtmlHelper::img('/wp-content/plugins/formsintegrator/assets/images/ok.svg'),array('class' => 'custom-modal-success__icon'));
                echo HtmlHelper::tag('div','Форма успешно отправлена', array('class' => 'custom-modal-success__label'));
            echo HtmlHelper::endTag('div');
        echo HtmlHelper::endTag('div');

        echo HtmlHelper::endTag('div');
    }

    public function response()
    {
        $this->build();
    }
}