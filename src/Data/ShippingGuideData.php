<?php
namespace Markantnorge\Bring\API\Data;

/**
 * Created by PhpStorm.
 * User: Markantnorge
 * Date: 5/24/16
 * Time: 10:34 PM
 */

class ShippingGuideData {

    const EVARSLING = 'EVARSLING';
    const POSTOPPKRAV = 'POSTOPPKRAV';
    const LORDAGSUTKJORING = 'LORDAGSUTKJORING';
    const ENVELOPE = 'ENVELOPE';
    const ADVISERING = 'ADVISERING';
    const PICKUP_POINT = 'PICKUP_POINT';
    const EVE_DELIVERY = 'EVE_DELIVERY';
    const SIMPLIFIED_DELIVERY= '1081';



    static public function validAdditionalServices () {
        return [
            self::EVARSLING,
            self::POSTOPPKRAV,
            self::LORDAGSUTKJORING,
            self::ENVELOPE,
            self::ADVISERING,
            self::PICKUP_POINT,
            self::EVE_DELIVERY,
            self::SIMPLIFIED_DELIVERY
        ];
    }
}