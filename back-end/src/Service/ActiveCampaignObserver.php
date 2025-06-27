<?php
namespace Src\Service;

use SplSubject;
use SplObserver;
use InvalidArgumentException;

/**
 * Observador que envia lead para o ActiveCampaign após submissão usando SplObserver.
 */
class ActiveCampaignObserver implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof LeadService) {
            return;
        }

        $data  = $subject->getLastSubmittedLead();
        $name  = $data['name']  ?? '';
        $email = $data['email'] ?? '';

        $acService = new ActiveCampaignService();
        $acService->addContact($name, $email);
    }
}