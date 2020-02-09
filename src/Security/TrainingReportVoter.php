<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\TrainingReport;
use App\Entity\User;

class TrainingReportVoter extends Voter
{
    const SHOW = 'show';
    
    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::SHOW])) {
            return false;
        }

        if (!$subject instanceof TrainingReport) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof User) {
            return false;
        }

        /** @var TrainingReport $trainingReport */
        $trainingReport = $subject;

        switch ($attribute) {
            case self::SHOW:
                return $this->canShow($trainingReport, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canShow(TrainingReport $trainingReport, User $user)
    {
        return $user === $trainingReport->getOwner();
    }  
    
}
