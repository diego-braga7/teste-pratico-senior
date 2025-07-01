<?php

namespace Src\Service;

use Src\Entity\Lead;
use Src\LoggerFactory;
use Src\Repository\RepositoryInterface;

class ActiveCampaignService extends BaseService
{

    private string $apiUrl;
    private string $apiKey;
    private string $listId;

    public function __construct(private RepositoryInterface $repository)
    {

        $this->apiUrl = $_ENV['AC_API_URL'] ?? getenv('AC_API_URL');
        $this->apiKey = $_ENV['AC_API_KEY'] ?? getenv('AC_API_KEY');
        $this->listId = $_ENV['AC_LIST_ID'] ?? getenv('AC_LIST_ID');
    }

    public function addContact(string $name, string $email)
    {
        try {
            /** @var Lead $lead */
            $lead = $this->repository->getByCollumn('email', $email);
            if($lead->getSent() == 1){
                LoggerFactory::getLogger()->info("Lead do {$email} já foi enviado.");
                return true;
            }
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
                $lead->setSent(1);
                $this->repository->save($lead);
                LoggerFactory::getLogger()->info("Lead do {$email} enviado com sucesso.", [$resp]);
                return true;
            }

            $message = "ActiveCampaign responded with HTTP {$code}";
            LoggerFactory::getLogger()->error($message, [$resp]);
        } catch (\Throwable $th) {
            LoggerFactory::getLogger()->error($th->getMessage());
        }
    }
}
