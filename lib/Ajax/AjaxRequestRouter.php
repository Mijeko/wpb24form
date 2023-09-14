<?php

namespace Ajax;

use Forms\Builder\CallbackForm;
use Forms\Builder\HeaderForm;
use Forms\FormGenerator;
use Forms\Handlers\IFormHandler;
use Html\Modal\Builder\MainModal;
use Html\Modal\ModalGenerator;

class AjaxRequestRouter
{
    const ACTION_GET_FORM = 'getForm';
    const ACTION_HANDLE_FORM = 'handleForm';
    const ACTION_GET_MODAL = 'getModal';

    public function actions()
    {
        return [
            self::ACTION_HANDLE_FORM => [
                HeaderForm::getInstance()->uniqKey() => HeaderForm::class,
                CallbackForm::getInstance()->uniqKey() => CallbackForm::class,
            ],
            self::ACTION_GET_FORM => [
                HeaderForm::getInstance()->uniqKey() => function () {
                    FormGenerator::show(new HeaderForm());
                },
            ],
            self::ACTION_GET_MODAL => [
                ModalGenerator::MODAL_CALLBACK => function () {
                    ModalGenerator::show(new MainModal('Закажите звонок', CallbackForm::build()));
                },
                ModalGenerator::MODAL_CALCULATE => function () {
                    ModalGenerator::show(
                        new MainModal(
                            'Заказать звонок',
                            HeaderForm::build(),
                            'Поделитесь мнением о нашей работе или задайте нам любой интересующий вас вопрос в поле комментарий'
                        )
                    );
                },
            ],
        ];
    }

    protected function getActions($key)
    {
        $map = $this->actions();

        if (array_key_exists($key, $map)) {
            return $map[$key];
        }

        return false;
    }

    public function route($action, $formData)
    {
        switch ($action) {
            case self::ACTION_HANDLE_FORM:
                $actions = $this->getActions($action);

                $formClass = $actions[$formData['form']];
                unset($formData['form']);

                /* @var $formHandler IFormHandler */
                $formHandler = $formClass::handler();

                $formHandler->handle($formData);

                break;

            case self::ACTION_GET_FORM:

                $actions = $this->getActions($action);
                $formName = $formData['FORM'];

                if (array_key_exists($formName, $actions)) {
                    call_user_func($actions[$formName]);
                }
                break;
            case self::ACTION_GET_MODAL:

                $actions = $this->getActions($action);
                $formName = $formData['modal'];

                if (array_key_exists($formName, $actions)) {
                    call_user_func($actions[$formName]);
                }
                break;
            default:
                throw new \Exception('Нет подходящего действия');
        }
    }
}