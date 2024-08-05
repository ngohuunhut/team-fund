<?php

namespace Nhn\Demo\Actions;

class Gsheet
{
    public function __construct(
        public string $sheetId,
        public string $month,
        public Money $money
    ) {
        // TODO:
    }

    private function getBaseUrl()
    {
        return "https://script.google.com/macros/s/{$this->sheetId}/exec";
    }

    public function post($data)
    {
        $payload = json_encode($data);

        $ch = curl_init($this->getBaseUrl());
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_encode($result);

        return true;
    }

    public function getMembers($from = "B6", $to = "B17", $sheetName = 'Thu_2024')
    {
        $params = [
            'from' => $from,
            'to' => $to,
            'sheetId' => $sheetName
        ];

        return $this->get(http_build_query(($params)));
    }

    public function fill($value, $cell)
    {
        $data = array(
            'cell' => $this->getCell($cell),
            'value' => $this->money->format($value)
        );

        return $this->post($data);
    }

    public function get($params)
    {
        $url = $this->getBaseUrl() . "?{$params}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);

        curl_close($curl);

        return collect(json_decode($response))->filter();
    }

    public function getCell($cell)
    {
        $months = config('nhn.months');

        return "{$months[$this->month]}{$cell}";
    }
}
