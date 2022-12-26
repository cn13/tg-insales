<?php

namespace app\helpers;

use app\models\User;

class CardHelper
{
    private static $url = 'https://lk.aqsi.ru/api/v2/clients/{aqsi_id}';

    public static function setCard(User $user)
    {
        $url = str_replace('{aqsi_id}', $user->aqsi_id, self::$url);
        $client = CurlAqsi::get($url)->getData();
        unset($client['group']);
        unset($client['loyaltyCard']);
        unset($client['age']);
        $client['loyaltyCard'] = self::getCardIdFromAqsi($user->getCard()->number);
        $client = CurlAqsi::put($url, json_encode($client))->getData();
    }

    public static function getCardIdFromAqsi($num)
    {
        $result = CurlAqsi::get('https://lk.aqsi.ru/api/v1/clients/groups')->getData();
        foreach ($result as $group) {
            if ($group['loyaltyCards']) {
                foreach ($group['loyaltyCards'] as $card) {
                    if ($card['fullNumber'] === trim($num) && $card['isGenerated'] == 1) {
                        return $card;
                    }
                }
            }
        }
        return null;
    }
}