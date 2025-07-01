<?php
namespace Src\Service;

use SplSubject;
use SplObserver;
use Src\Repository\LeadRepository;

class ActiveCampaignObserver implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof LeadService) {
            return;
        }

        $data  = $subject->getLastSubmittedLead();

        $acService = new ActiveCampaignService(new LeadRepository);
        $acService->addContact($data->getName(), $data->getEmail());
    }
}