<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Excercise;
use App\Entity\User;

class ExcerciseVoter extends Voter
{
    const EDIT = 'edit';
    
    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Excercise) {
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

        /** @var Excercise $excercise */
        $excercise = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($excercise, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Excercise $excercise, User $user)
    {
        return $user === $excercise->getOwner();
    }
}
