<?php
namespace Src\Service;

class ActiveCampaignService extends BaseService
{
    private string $apiUrl;
    private string $apiKey;
    private string $listId;

    public function __construct() {
        $this->apiUrl = getenv('AC_API_URL');
        $this->apiKey = getenv('AC_API_KEY');
        $this->listId = getenv('AC_LIST_ID');
    }

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
