<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());
if (CModule::IncludeModule('iblock')) {
    $el = new CIBlockElement;
}
$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
if($arParams["EVENT_NAME"] == '')
	$arParams["EVENT_NAME"] = "FEEDBACK_FORM";
$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] <> '' && (!isset($_POST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_POST["PARAMS_HASH"]))
{
	$arResult["ERROR_MESSAGE"] = array();
	if(check_bitrix_sessid())
	{
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"]))
		{
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_name"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_NAME");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_phone"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_PHONE");		
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_email"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_EMAIL");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["MESSAGE"]) <= 3)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");			
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("FILE", $arParams["REQUIRED_FIELDS"])) && (empty($_FILES["file"])))
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_FILE");
		}
		if(strlen($_POST["user_email"]) > 1 && !check_email($_POST["user_email"]))
			$arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");
		if($arParams["USE_CAPTCHA"] == "Y")
		{
			$captcha_code = $_POST["captcha_sid"];
			$captcha_word = $_POST["captcha_word"];
			$cpt = new CCaptcha();
			$captchaPass = COption::GetOptionString("main", "captcha_password", "");
			if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0)
			{
				if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
					$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTCHA_WRONG");
			}
			else
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTHCA_EMPTY");

		}
			
		$file = array_merge($_FILES['file'], array('del' => 'Y', 'MODULE_ID' => 'bitrix:main.feedback'));
		$fid = CFile::SaveFile($file, 'file');
		$file_array = array();
		$file_path = '';
		if (intval($fid) > 0) {
			$file_path = CFile::GetPath($fid);
			$file_array[] = $fid;
		}

		if(empty($arResult["ERROR_MESSAGE"]))
		{
            $keys = ["user_name" => "Имя", "user_phone" => "Телефон", "user_email" => "Почта",  "element_title" => "Название",  "MESSAGE" => "Сообщение", "submit" => "Форма"];
            foreach ($_POST as $key => $text_form):

                if ($key == "bxajaxid" or $key == "AJAX_CALL" or $key == "sessid" or $key == "PARAMS_HASH" or $key == "captcha_word" or $key == "PARAMS_HASH" or $key == "captcha_sid") continue;
                $v = $keys[$key];
                $info_form .= $v.": ".$text_form.PHP_EOL;
            endforeach;

            /*Отправка лида начало*/

            // формируем URL, на который будем отправлять запрос в битрикс24

            $queryURL = "https://inox22.bitrix24.ru/rest/1/yzrbli7nbvw47ozb/crm.lead.add.json";

            //собираем данные из формы

            $sPhone = htmlspecialchars($_POST["user_phone"]);
            $sName = htmlspecialchars($_POST["user_name"]);
            $sLastName = htmlspecialchars($_POST["LAST_NAME"]);
            $arPhone = (!empty($sPhone)) ? array(array('VALUE' => $sPhone, 'VALUE_TYPE' => 'MOBILE')) : array();
            $Email24 = $_POST["user_email"];
            $info_forms = $_POST["element_title"];
            // формируем параметры для создания лида
            $queryData = http_build_query(array(
                "fields" => array(
                    "TITLE" => 'Лид с сайта', //Заголовок лида
                    "SOURCE_ID" => 'WEB',
                    "NAME" => $sName,    // имя
                    "PHONE" => $arPhone, // телефон
                    'EMAIL' => [['VALUE' => $_POST['user_email'], 'VALUE_TYPE' => 'WORK']],
                    "COMMENTS" => $info_forms, //Комментарий
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")    // Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
            ));
            // отправляем запрос в Б24 и обрабатываем ответ
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $queryURL,
                CURLOPT_POSTFIELDS => $queryData,
            ));

            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result, 1);

            // если произошла какая-то ошибка - выведем её
            if (array_key_exists('error', $result)) {
                die("Ошибка при сохранении лида: " . $result['error_description']);
            }



            /*Отправка лида конец*/
            $arLoadProductArray = Array(
                "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                "IBLOCK_ID" => 10,
                "PROPERTY_VALUES" => $PROP,
                "NAME" => $_POST["user_name"],
                "PREVIEW_TEXT" => $info_form,
                "ACTIVE" => "Y",            // активен
            );
			$arFields = Array(
				"AUTHOR" => $_POST["user_name"],				
				"AUTHOR_EMAIL" => $_POST["user_email"],
				"EMAIL_TO" => $arParams["EMAIL_TO"],
				"TEXT" => $_POST["MESSAGE"],
				"AUTHOR_PHONE" => $_POST["user_phone"],
				"ELEMENT_TITLE" => $_POST["element_title"]
			);

			if(!empty($arParams["EVENT_MESSAGE_ID"]))
			{
				foreach($arParams["EVENT_MESSAGE_ID"] as $v)
					if(IntVal($v) > 0)
						CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v), $file_array);
                        $PRODUCT_ID = $el->Add($arLoadProductArray);
			}
			else
				CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", "", $file_array);
			$_SESSION["MF_NAME"] = htmlspecialcharsbx($_POST["user_name"]);			
			$_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
			$_SESSION["MF_PHONE"] = htmlspecialcharsbx($_POST["user_phone"]);
			$_SESSION["MF_ELEMENT_TITLE"] = htmlspecialcharsbx($_POST["element_title"]);
			LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
		}
		
		$arResult["MESSAGE"] = htmlspecialcharsbx($_POST["MESSAGE"]);
		$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_POST["user_name"]);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
		$arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($_POST["user_phone"]);
		$arResult["ELEMENT_TITLE"] = htmlspecialcharsbx($_POST["element_title"]);
	}
	else
		$arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}

if(empty($arResult["ERROR_MESSAGE"]))
{
	if($USER->IsAuthorized())
	{
		$arResult["AUTHOR_NAME"] = $USER->GetFormattedName(false);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($USER->GetEmail());
	}
	else
	{
		if(strlen($_SESSION["MF_NAME"]) > 0)
			$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_NAME"]);
		if(strlen($_SESSION["MF_EMAIL"]) > 0)
			$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_SESSION["MF_EMAIL"]);
		if(strlen($_SESSION["MF_PHONE"]) > 0)
			$arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($_SESSION["MF_PHONE"]);
	}
}

if($arParams["USE_CAPTCHA"] == "Y")
	$arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

$this->IncludeComponentTemplate();
