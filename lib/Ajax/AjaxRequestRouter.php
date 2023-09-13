<?php

namespace Ajax;

use Bitrix24\Bitrix24Api;
use Forms\Builder\AMainForm;
use Forms\Builder\CallbackForm;
use Forms\Builder\HeaderForm;
use Forms\Fields\DropdownField;
use Forms\Fields\InputField;
use Forms\Fields\TextareaField;
use Forms\FormGenerator;
use Forms\Handlers\FormHandler;
use Forms\Handlers\IFormHandler;
use Html\Modal\Builder\MainModal;
use Html\Modal\ModalGenerator;
use Http\Converter\Json;
use Http\HttpCurl;
use Http\Response\JsonResponse;

class AjaxRequestRouter
{
    const ACTION_GET_FORM = 'getForm';
    const ACTION_HANDLE_FORM = 'handleForm';
    const ACTION_GET_MODAL = 'getModal';

    public function actions()
    {
        return [
            self::ACTION_HANDLE_FORM => [
                HeaderForm::getInstance()->uniqKey() => FormHandler::class
            ],
            self::ACTION_GET_FORM => [
                HeaderForm::getInstance()->uniqKey() => function () {
                    FormGenerator::show(new HeaderForm());
                },
            ],
            self::ACTION_GET_MODAL => [
                ModalGenerator::MODAL_CALLBACK => function () {
                    ModalGenerator::show(
                        new MainModal(
                            'Закажите звонок',
                            CallbackForm::build([
                                InputField::build('name', [
                                    'label' => 'Имя',
                                ]),
                                InputField::build('phone', [
                                    'label' => 'Телефон',
                                ]),
                                DropdownField::build('topic')
                                    ->variants([
                                        'Проектирование бассейнов' => 'Проектирование бассейнов',
                                        'Строительство и реконструкция бассейнов' => 'Строительство и реконструкция бассейнов',
                                        'Оборудование для бассейнов' => 'Оборудование для бассейнов',
                                        'Системы управления бассейнов' => 'Системы управления бассейнов',
                                        'Покрытия для бассейнов' => 'Покрытия для бассейнов',
                                        'Сервисное и техническое обслуживание бассейнов' => 'Сервисное и техническое обслуживание бассейнов',
                                        'Рассчитать стоимость' => 'Рассчитать стоимость',
                                        'Бетонные бассейны' => 'Бетонные бассейны',
                                        'Композитные бассейны' => 'Композитные бассейны',
                                        'Сборные бассейны' => 'Сборные бассейны',
                                    ])
                                    ->default('Тема обращения'),
                                TextareaField::build('comment', [
                                    'label' => 'Комментарий',
                                ]),
                            ])
                        ),
                    );
                },
                ModalGenerator::MODAL_HEADER => function () {
                    ModalGenerator::show(
                        new MainModal(
                            'Заказать звонок',
                            HeaderForm::build([
                                InputField::build('name', [
                                    'label' => 'Имя',
                                ]),
                                InputField::build('phone', [
                                    'label' => 'Телефон',
                                ]),
                                TextareaField::build('comment', [
                                    'label' => 'Комментарий',
                                ]),
                            ]),
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

    public function route($action, $params)
    {
        switch ($action) {
            case self::ACTION_HANDLE_FORM:
                $actions = $this->getActions($action);

                $handlerClass = $actions[$params['form']];
                unset($params['form']);

                /* @var $model IFormHandler */
                $model = new $handlerClass(
                    new JsonResponse(),
                    new Bitrix24Api(new HttpCurl(), new Json()),
                );

                $model->handle($params);

                break;

            case self::ACTION_GET_FORM:

                $actions = $this->getActions($action);
                $formName = $params['FORM'];

                if (array_key_exists($formName, $actions)) {
                    call_user_func($actions[$formName]);
                }
                break;
            case self::ACTION_GET_MODAL:

                $actions = $this->getActions($action);
                $formName = $params['modal'];

                if (array_key_exists($formName, $actions)) {
                    call_user_func($actions[$formName]);
                }
                break;
            default:
                throw new \Exception('Нет подходящего действия');
        }
    }
}