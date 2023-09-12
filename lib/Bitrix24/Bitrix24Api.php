<?php

namespace Bitrix24;

use Http\ICurlWrapper;
use Http\Converter\IConverter;
use Http\Converter\Json;

class Bitrix24Api
{
    public ICurlWrapper $http;
    public IConverter $converter;

    private $url = 'https://b24-dm961m.bitrix24.ru/rest/1/tag7vhqjjv69kyad';

    const CRM_LEAD_ADD = 'crm.lead.add';

    public function __construct(ICurlWrapper $http, IConverter $converter)
    {
        $this->http = $http;
        $this->converter = $converter;
    }


    public function leadAdd(array $params): array
    {
        return $this->call(self::CRM_LEAD_ADD, [
            'fields' => $params
        ])->response();
    }


    protected function call(string $method, array $params): Json
    {
        $url = sprintf(
            '%s/%s',
            $this->url,
            $method
        );

        $httpResponse = $this->http->get($url, $params);

        return (new Json())->input($httpResponse);
    }

}