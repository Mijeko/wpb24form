<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
define('SECRET_KEY', '6LdRxmgfAAAAACuusoB_JXWC4vkhfkGrKLckBCEo');
if (!CModule::IncludeModule("iblock")) {
    return;
}

use Bitrix\Iblock\ElementTable;




if (!array_key_exists('INNET_MODULE_ID', $GLOBALS)) $GLOBALS['INNET_MODULE_ID'] = 'innet.focus';
$arResult["PARAMS_HASH"] = md5(serialize($arParams) . $this->GetTemplateName());
$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");


if ($arParams["OK_MESSAGE"] == '')
    $arParams["OK_MESSAGE"] = GetMessage("INNET_FORM_OK_MESSAGE");


$res = CIBlock::GetProperties($arParams['IBLOCK_ID'], array("SORT" => "ASC"), array("ACTIVE" => "Y"));
while ($arProp = $res->Fetch()) {
    if (in_array($arProp['CODE'], $arParams['INNET_PROPERTIES_SHOW_FORM'])) {
        if ($arProp['LIST_TYPE'] == "L" || $arProp['LIST_TYPE'] == "C") {
            $property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => $arProp['CODE']));
            while ($enum_fields = $property_enums->GetNext()) {
                $arProp['PROPERTIES_LIST'][] = $enum_fields;
            }
        }

        $arResult["FIELDS"][$arProp['CODE']] = $arProp;
    }


    if ($arProp['CODE'] == 'FORM_GOAL_ID') {
        $arResult["FORM_GOAL_ID"] = $arProp['DEFAULT_VALUE'];
    } else if ($arProp['CODE'] == 'FORM_GOAL_NUMBER') {
        $arResult["FORM_GOAL_NUMBER"] = $arProp['DEFAULT_VALUE'];
    }

    if ($arProp['CODE'] == 'BUTTON_GOAL_ID') {
        $arResult["BUTTON_GOAL_ID"] = $arProp['DEFAULT_VALUE'];
    } else if ($arProp['CODE'] == 'BUTTON_GOAL_NUMBER') {
        $arResult["BUTTON_GOAL_NUMBER"] = $arProp['DEFAULT_VALUE'];
    }
}


$res = CIBlock::GetProperties($arParams['IBLOCK_ID'], array("SORT" => "ASC"));
while ($arProp = $res->Fetch()) {
    if ($arProp['CODE'] == 'FORM_HEADER') {
        $arResult["FIELDS_DESCRIPTION"]['FORM_HEADER'] = ($arProp['NAME'] ? $arProp['NAME'] : $arProp['DEFAULT_VALUE']);
    }
    if ($arProp['CODE'] == 'FORM_DESCRIPTION') {
        $arResult["FIELDS_DESCRIPTION"]['FORM_DESCRIPTION'] = ($arProp['NAME'] ? $arProp['NAME'] : $arProp['DEFAULT_VALUE']);
    }
    if ($arProp['CODE'] == 'FORM_BUTTON') {
        $arResult["FIELDS_DESCRIPTION"]['FORM_BUTTON'] = ($arProp['NAME'] ? $arProp['NAME'] : $arProp['DEFAULT_VALUE']);
    }
}


foreach ($arResult["FIELDS"] as $key => $field) {
    if ($field['USER_TYPE'] == 'HTML') {
        $arFieldsProp[$field['CODE']] = array('VALUE' => array('TYPE' => 'HTML', 'TEXT' => $_POST[$field['CODE']]));
//        $arFieldsProp[$field['CODE']] = $_POST[$field['CODE']];
    } else {
        $arFieldsProp[$field['CODE']] = $_POST[$field['CODE']];
    }

    $arResult["FIELDS"][$key]['NAME'] = (strlen($field['HINT']) > 0) ? $field['HINT'] : $field['NAME'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] <> '' && (!isset($_POST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_POST["PARAMS_HASH"])) {
    if ($arParams["EMAIL_TO"] == '')
        $arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");

    $arResult["ERROR_MESSAGE"] = array();

    if (check_bitrix_sessid()) {
        if (!empty($arResult["FIELDS"])) {
            foreach ($arResult["FIELDS"] as $field) {
                if ($field['IS_REQUIRED'] == 'Y') {
                    if (empty($_POST[$field['CODE']])) {
                        $field_name = (strlen($field['HINT']) > 0) ? $field['HINT'] : $field['NAME'];
                        $arResult["ERROR_MESSAGE"][] = GetMessage("INNET_FIELD_EMPTY") . $field_name;
                    }
                }

                if ($_FILES[$field['CODE']]) {
                    if ($field['PROPERTY_TYPE'] == 'F') {
                        foreach ($_FILES[$field['CODE']]["name"] as $key => $photo) {
                            $tmpFile = array(
                                "name" => $photo,
                                "size" => $_FILES[$field['CODE']]["size"][$key],
                                "tmp_name" => $_FILES[$field['CODE']]["tmp_name"][$key],
                                "type" => $_FILES[$field['CODE']]["type"][$key],
                                "old_file" => "",
                                "del" => "y",
                                "MODULE_ID" => "iblock"
                            );

                            $fid = CFile::SaveFile($tmpFile, "/upload/iblock/");
                            $arFieldsProp[$field['CODE']][] = $fid;
                        }
                    }
                }
            }
        }

        if ($arParams["USE_CAPTCHA"] == "Y") {
            if (COption::GetOptionString($GLOBALS['INNET_MODULE_ID'], "innet_use_google_captcha_" . SITE_ID) == 'Y') {
                if (isset($_POST['g-recaptcha-response'])) {
                    function getCaptcha($SecretKey)
                    {
                        $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$SecretKey}");
                        $Return = json_decode($Response);
                        return $Return;
                    }

                    $Return = getCaptcha($_POST['g-recaptcha-response']);
                    /*ВЫВОДИМ НА ЭКРАН ПОЛУЧЕННЫЙ ОТВЕТ*/

                    if ($Return->success == true && $Return->score > 0.7) {

                        if(isset($_POST["COMMENT"])){
                            $text = $_POST['COMMENT']; // получаем текст из POST-запроса
                            $link_pattern = '/https?:\/\/\S+/'; // регулярное выражение для поиска ссылки
                            if (preg_match($link_pattern, $text, $matches)) {
                                if (strpos($matches[0], 'glasha') === false) {
                                    $arResult["ERROR_MESSAGE"][] = GetMessage("INNET_FORM_CAPTHCA_EMPTY");
                                }
                            }
                        }



                    } else {
                        $arResult["ERROR_MESSAGE"][] = GetMessage("INNET_FORM_CAPTHCA_EMPTY");
                    }
                } else {
                    $arResult["ERROR_MESSAGE"][] = GetMessage("INNET_FORM_RECAPTCHA");
                }
            }
        }


        if (empty($arResult["ERROR_MESSAGE"])) {
            global $USER;
            $arFieldsProp['USER_ID'] = $USER->GetID();
            $arFieldsProp['ELEMENT_ID'] = $arParams['INNET_ELEMENT_ID'];
            $arFieldsProp['LINK'] = $_REQUEST['LINK'];
            $arFieldsProp['THEME'] = $arParams['INNET_THEME'];
            $arFieldsProp['SITE_TEMPLATE_PATH'] = SITE_SERVER_NAME . SITE_TEMPLATE_PATH;

            if (!empty($arParams['INNET_PARAMS_ORDER']['ITEMS'])) {
                $arFieldsProp['SERIALIZE']['BASKET_TOTAL_PRICE'] = $arParams['INNET_PARAMS_ORDER']['BASKET_TOTAL_PRICE'];
                $arFieldsProp['SERIALIZE']['TOTAL_PRICE_DIFF'] = $arParams['INNET_PARAMS_ORDER']['TOTAL_PRICE_DIFF'];
                $arFieldsProp['SERIALIZE']['BASKET_TOTAL_COUNT'] = $arParams['INNET_PARAMS_ORDER']['BASKET_TOTAL_COUNT'];
                $arFieldsProp['SERIALIZE']['CURRENCY'] = $arParams['INNET_PARAMS_ORDER']['CURRENCY'];
                $arFieldsProp['SERIALIZE']['BASKET_TOTAL_PRICE_FORMAT'] = $arParams['INNET_PARAMS_ORDER']['BASKET_TOTAL_PRICE_FORMAT'];

                include_once __DIR__ . '/composition_orders.php';
            }


            if (!empty($arParams['IBLOCK_ID'])) {
                $arFieldsAdd = array(
                    "ACTIVE" => "N",
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), 'FULL'),
                    "NAME" => ConvertTimeStamp(time(), 'FULL'),
                    "CODE" => ConvertTimeStamp(time(), 'FULL'),
                    "PROPERTY_VALUES" => $arFieldsProp
                );

                $el = new CIBlockElement();
                if (!$el->Add($arFieldsAdd)) {
                    $arResult["ERROR_MESSAGE"][] = GetMessage("INNET_FORM_ERROR_ADD_IBLOCK");
                }
            }

            $arFieldsProp['COMMENT'] = $arFieldsProp['COMMENT']['VALUE']['TEXT'];




            $title = "Заявка с сайта Глашатай: обратный звонок";

            if (isset($arFieldsProp["ELEMENTS"][0])) {
                $iblockId = 44; // ID инфоблока
                $elementId = $arFieldsProp["ELEMENTS"][0]; // ID элемента инфоблока

// Получение названия элемента инфоблока по его ID и ID инфоблока
                $element = ElementTable::getList(array(
                    'filter' => array('=ID' => $elementId, '=IBLOCK_ID' => $iblockId),
                    'select' => array('ID', 'NAME')
                ))->fetch();

                if ($element) {
                    $elementName = $element['NAME'];
                    $name_order = $elementName;
                }
                $title = "Заявка с сайта Глашатай: узнать стоимость $name_order";
            }

            /*Отправка лида начало*/
            /*ТУТ ВСЕ КТО МОЖЕТ БЫТЬ ОТВЕТСТВЕННЫМ ЗА ЗАЯВКУ*/
            $assigned_ids = [7, 9, 35, 11];
            /*$assigned_ids = [1];*/


            // Параметры авторизации
            /*            $domain = 'b24-ukf3ab.bitrix24.ru'; // Домен Битрикс24
                        $auth = 'cnmm0j71xiednb7m'; // Код авторизации*/
            $domain = 'glashatay.bitrix24.ru'; // Домен Битрикс24
            $auth = 'vczrzrlvx1owth1h'; // Код авторизации

// Формируем URL для запроса
            $url = 'https://'.$domain.'/rest/1/'.$auth.'/crm.deal.list?'
                .http_build_query(array(
                    'order' => array('DATE_CREATE' => 'DESC'),
                    'select' => array('ID', 'ASSIGNED_BY_ID'),
                    'filter' => array('ASSIGNED_BY_ID' => $assigned_ids),
                    'limit' => 1,
                ));

// Отправляем запрос
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

// Обрабатываем результат
            $data = json_decode($result, true);

// Выводим информацию на экран
            if (isset($data['error']))
            {
                $res_deal =  'Ошибка: '.$data['error_description'];
            }
            else
            {
                $deal_id = $data['result'][0]['ID']; // ID последней сделки
                $assigned_by_id = $data['result'][0]['ASSIGNED_BY_ID']; // ID ответственного за последнюю сделку

                $current_index = array_search($assigned_by_id, $assigned_ids);
                $next_index = ($current_index + 1) % count($assigned_ids);
                $next_assigned = $assigned_ids[$next_index];
            }


            // Привожу телефон в определенный формат "+79332838382"

            $user_phone = $arFieldsProp["PHONE"];
            $user_phone = str_replace(['(', ')', '-'], '', $user_phone);
            $user_phone = preg_replace('/\s/', '', $user_phone);

            $user_email = $arFieldsProp["EMAIL"];

            $user_name = $arFieldsProp["NAME"];
// Данные для поиска контакта
            $searchPhone = $user_phone;

// Формируем данные для поиска контакта
            $searchData = array(
                'filter' => array(
                    'PHONE' => $searchPhone,
                ),
                'select' => array('ID')
            );

// Отправляем запрос на поиск контакта
            /*$searchUrl = 'https://b24-ukf3ab.bitrix24.ru/rest/1/cnmm0j71xiednb7m/crm.contact.list.json';*/
            $searchUrl = 'https://glashatay.bitrix24.ru/rest/1/vczrzrlvx1owth1h/crm.contact.list.json';
            $searchResponse = json_decode(file_get_contents($searchUrl . '?' . http_build_query($searchData)), true);

// Проверяем результаты поиска
            if (!empty($searchResponse['result'])) {
                // Контакт уже существует, пропускаем создание нового контакта и используем его ID для создания сделки
                $existingContactId = $searchResponse['result'][0]['ID'];
                $dealData['fields']['CONTACT_ID'] = $existingContactId;
            } else {

                // Контакт не найден, создаем новый контакт
                /*$createContactUrl = 'https://b24-ukf3ab.bitrix24.ru/rest/1/cnmm0j71xiednb7m/crm.contact.add.json';*/
                $createContactUrl = 'https://glashatay.bitrix24.ru/rest/1/vczrzrlvx1owth1h/crm.contact.add.json';
                $createContactData = array(
                    'fields' => array(
                        'NAME' => $user_name,
                        'PHONE' => array(array('VALUE' => $user_phone, 'VALUE_TYPE' => 'WORK')),
                        'EMAIL' => array(array('VALUE' => $user_email, 'VALUE_TYPE' => 'WORK')),
                    )
                );
                $createContactResponse = json_decode(file_get_contents($createContactUrl . '?' . http_build_query($createContactData)), true);

                // Проверяем результат создания контакта
                if (!empty($createContactResponse['result'])) {
                    // Контакт успешно создан, получаем его ID и используем его для создания сделки
                    $newContactId = $createContactResponse['result'];
                    $dealData['fields']['CONTACT_ID'] = $newContactId;
                } else {
                    // Ошибка создания контакта, обрабатываем ошибку
                    echo 'Ошибка создания контакта: ' . $createContactResponse['error_description'];
                }
            }


            $comment = '';
            if(isset($arFieldsProp["COMMENT"])){
                $comment = $comment . $arFieldsProp["COMMENT"] . "<br>";
            }
            if(isset($arFieldsProp["LINK"])){
                $comment = $comment . " Страница: ".'<a href="https://' . $arFieldsProp["LINK"] . '">' . $arFieldsProp["LINK"] . '</a><br>';
            }

            // Данные для создания сделки
            $dealData = array(
                'fields' => array(
                    'TITLE' => $title,
                    'CONTACT_ID' => $dealData['fields']['CONTACT_ID'], // ID созданного контакта
                    'OPPORTUNITY' => 0, // Сумма сделки
                    'CURRENCY_ID' => 'RUB', // Валюта сделки
                    'COMMENTS' => $comment, // Комментарий к сделке
                    'ASSIGNED_BY_ID' => $next_assigned,
                    'SOURCE_ID' => 9059885791
                )
            );

            /*            7 - Кокарева;
                        11 - Шершнев;
                        9 - Мерзляков;
                        35 - Михайлик;*/

            $created_application = [
                7 => 'https://glashatay.bitrix24.ru/rest/7/95ta9gl2xbyk6ir0/',
                11 => 'https://glashatay.bitrix24.ru/rest/11/dbgp8mq1pnuqgr40/',
                9 => 'https://glashatay.bitrix24.ru/rest/9/cpdvyj767s056ziv/',
                35 => 'https://glashatay.bitrix24.ru/rest/35/zjcr20l8cwz3agfk/'
            ];
// Отправка POST-запроса на создание сделки
            $curl = curl_init();
            curl_setopt_array($curl, array(
                /*CURLOPT_URL => 'https://glashatay.bitrix24.ru/rest/1/vczrzrlvx1owth1h/crm.deal.add.json',*/
                CURLOPT_URL => $created_application[$next_assigned] . 'crm.deal.add.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($dealData)
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            /*КОНЕЦ*/

            if (!empty($arParams["EVENT_MESSAGE_ID"])) {
                foreach ($arParams["EVENT_MESSAGE_ID"] as $v)
                    if (IntVal($v) > 0)
                        CEvent::Send($arParams["EVENT_MESSAGE_TYPE"], SITE_ID, $arFieldsProp, "N", IntVal($v));//mail to admin
            } else if (!empty($arParams["EVENT_MESSAGE_TYPE"])) {
                CEvent::Send($arParams["EVENT_MESSAGE_TYPE"], SITE_ID, $arFieldsProp);//mail to admin
            }

            if (!empty($arParams["EVENT_MESSAGE_TYPE_USER"])) {
                CEvent::Send($arParams["EVENT_MESSAGE_TYPE_USER"], SITE_ID, $arFieldsProp);//mail to user
            }


            echo rand();
            die();
//            LocalRedirect($APPLICATION->GetCurPageParam("success=" . $arResult["PARAMS_HASH"], Array("success")));
        }
    } else {
        $arResult["ERROR_MESSAGE"][] = GetMessage("FORM_SESS_EXP");
    }
} elseif ($_REQUEST["success"] == $arResult["PARAMS_HASH"]) {
    $arResult["OK_MESSAGE"] = $arParams["OK_MESSAGE"];


    if ($componentTemplate == 'order') {
        foreach ($_SESSION['INNET_BASKET'] as $key => $val) {
            if ($val > 0) {
                $arItem = explode(":", $key);
                $productId = $arItem[1];
                $productCount = $val;
                if ($arItem[0] == "INNET_BASKET_ELEMENT_ID") {
                    $arProducts[] = $productId;
                    $arCount[$productId] = $productCount;

                    $APPLICATION->set_cookie("INNET_BASKET_ELEMENT_ID:" . $productId, 0, time() + 60 * 60 * 24 * 30);
                }
            }
        }

        unset($_SESSION['INNET_BASKET']);
    }
}


if (!empty($arFieldsProp)) {
    foreach ($arFieldsProp as $key => $val) {
        if (is_array($val)) {
            $arResult['INNET_FORM'][$key] = $val['VALUE']['TEXT'];
        } else {
            $arResult['INNET_FORM'][$key] = $val;
        }
    }
}


if ($arParams["USE_CAPTCHA"] == "Y")
    $arResult["capCode"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());


$this->IncludeComponentTemplate();