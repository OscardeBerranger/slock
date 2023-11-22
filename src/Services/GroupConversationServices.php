<?php

namespace App\Services;

use App\Entity\GroupConversation;
use App\Entity\Profile;
use phpDocumentor\Reflection\Types\Boolean;

class GroupConversationServices
{
    public function isInConv(Profile $profile, GroupConversation $groupConversation) :bool
    {
        $gCreator = $groupConversation->getConvCreator();
        $gRecipient = $groupConversation->getConvRecipient();

        foreach ($gRecipient as $u){
            if ($u === $profile){
                return true;
            }
        }
        foreach ($gCreator as $u){
            if ($u === $profile){
                return true;
            }
        }
        return false;
    }
}