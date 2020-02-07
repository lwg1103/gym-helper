<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Training;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TrainingVoter extends Voter
{
    const EDIT = 'edit';
    
    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Training) {
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

        /** @var Training $training */
        $training = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($training, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Training $training, User $user)
    {
        return $user === $training->getUser();
    }  
}
