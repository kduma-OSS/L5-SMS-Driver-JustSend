<?php
namespace KDuma\SMS\Drivers\JustSend;

use Exception;
use KDuma\SMS\Drivers\SMSSenderDriverInterface;
use KDuma\SMS\Drivers\SMSChecksBalanceDriverInterface;

class JustSendDriver implements SMSSenderDriverInterface, SMSChecksBalanceDriverInterface
{
    /**
     * The API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $config;

    /**
     * JustSendDriver constructor.
     *
     * @param $apiKey
     * @param array $config
     */
    public function __construct($apiKey, array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->config = collect($config);
    }

    /**
     * @return string
     */
    protected function getVariant()
    {
        if ($this->config->get('eco', true)) {
            return 'ECO';
        } elseif (in_array($this->config->get('sender', null), ['INFO', 'INFORMACJA', 'KONKURS', 'NOWOSC', 'OFERTA', 'OKAZJA', 'PROMOCJA', 'SMS'])) {
            return 'FULL';
        } else {
            return 'PRO';
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    protected function SendSMSRequest($data)
    {
        $curl = curl_init();

        $options = [
            CURLOPT_URL            => "https://justsend.pl/api/rest/message/send/{$this->apiKey}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                'content-type: application/json',
            ],
        ];
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception('cURL Error #:'.$err);
        }

        return json_decode($response, true);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function SendBalanceRequest()
    {
        $curl = curl_init();

        $options = [
            CURLOPT_URL            => "https://justsend.pl/api/rest/payment/points/{$this->apiKey}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER     => [
                'content-type: application/json',
            ],
        ];
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception('cURL Error #:'.$err);
        }

        return json_decode($response, true);
    }

    /**
     * Checks if the transaction has an error.
     *
     * @param $response
     *
     * @return bool
     */
    protected function hasError($response)
    {
        return $response['responseCode'] != 'OK';
    }

    /**
     * @param $to      string   Recipient phone number
     * @param $message string   Message to send
     *
     * @return string
     * @throws Exception
     */
    public function send($to, $message)
    {
        $data = [
            'from'        => $this->config->get('eco', true) ? null : $this->config->get('sender'),
            'bulkVariant' => $this->getVariant(),
            'message'     => $message,
            'to'          => $to,
        ];

        $response = $this->SendSMSRequest($data);

        dd($response);

        if ($this->hasError($response)) {
            throw new \Exception('Something went wrong: '.$response['responseCode'].' ('.$response['errorId'].'): '.$response['message']);
        }

        return $response;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function balance()
    {
        $result = $this->SendBalanceRequest()['data'];

        switch ($this->getVariant()){
            case 'ECO':
                return (int) floor($result/3);

            case 'FULL':
                return (int) floor($result/6);

            case 'PRO':
                return (int) floor($result/7);
        }

        return 0;
    }
}