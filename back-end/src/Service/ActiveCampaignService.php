<?php
namespace Src\Service;

class ActiveCampaignService
{
    private $apiUrl   = 'https://developertihee.api-us1.com';
    private $apiKey   = '732beccc0ae9428e8cf5e4c16fc392ad7db34fbf0066aeddf36b01012324b720354e3772';
    private $listId   = 'SEU_LIST_ID';

    public function addContact(string $name, string $email)
    {
        $payload = [
            'contact' => [
                'email' => $email,
                'firstName' => $name,
                'listid' => $this->listId,
            ]
        ];

        $ch = curl_init("{$this->apiUrl}/api/3/contacts");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER  => [
                "Api-Token: {$this->apiKey}",
                'Content-Type: application/json'
            ],
            CURLOPT_POST         => true,
            CURLOPT_POSTFIELDS   => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 201) {
            return true;
        }

        return "ActiveCampaign responded with HTTP {$code}";
    }
}
