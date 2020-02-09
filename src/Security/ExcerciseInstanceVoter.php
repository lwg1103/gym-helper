<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\ExcerciseInstance;
use App\Entity\User;

class ExcerciseInstanceVoter extends Voter
{
    const EDIT = 'edit';
    
    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof ExcerciseInstance) {
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

        /** @var ExcerciseInstance $excerciseInstance */
        $excerciseInstance = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($excerciseInstance, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(ExcerciseInstance $excerciseInstance, User $user)
    {
        return $user === $excerciseInstance->getOwner();
    }  
}
