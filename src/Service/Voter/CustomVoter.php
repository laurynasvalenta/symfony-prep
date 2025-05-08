<?php

declare(strict_types=1);

namespace App\Service\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || in_array('non_gmail_email', $attributes, true) === false) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (str_ends_with($user->getUserIdentifier(), '@gmail.com')) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
