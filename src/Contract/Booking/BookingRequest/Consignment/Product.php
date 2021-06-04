<?php
namespace Markantnorge\Bring\API\Contract\Booking\BookingRequest\Consignment;
use Markantnorge\Bring\API\Contract\ApiEntity;
use Markantnorge\Bring\API\Contract\ContractValidationException;
use Markantnorge\Bring\API\Data\BringData;

class Product extends ApiEntity
{
    protected $_data = [
        'services' => null,
        'customsDeclaration' => null
    ];

    const ADDITIONAL_SERVICE_CASH_ON_DELIVERY = 'cashOnDelivery';
    const ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION = 'recipientNotification';
    const ADDITIONAL_SERVICE_SOCIAL_CONTROL = 'socialControl';
    const ADDITIONAL_SERVICE_SIMPLE_DELIVERY = 'simpleDelivery';
    const ADDITIONAL_SERVICE_DELIVERY_OPTION = 'deliveryOption';
    const ADDITIONAL_SERVICE_SATURDAY_DELIVERY = 'saturdayDelivery';
    const ADDITIONAL_SERVICE_FLEX_DELIVERY= 'flexDelivery';
    const ADDITIONAL_SERVICE_PHONE_NOTIFICATION= 'phonenotification';
    const ADDITIONAL_SERVICE_DELIVERY_INDOORS= 'deliveryIndoors';

    /**
     * See http://developer.bring.com/api/booking/
     */
    
    static public function serviceMapping () {
        return [
            BringData::PRODUCT_5800 => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_SOCIAL_CONTROL],
            BringData::PRODUCT_PA_DOREN => [self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_SIMPLE_DELIVERY, self::ADDITIONAL_SERVICE_DELIVERY_OPTION],
            BringData::PRODUCT_BPAKKE_DOR_DOR => [self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_SIMPLE_DELIVERY, self::ADDITIONAL_SERVICE_DELIVERY_OPTION],
            BringData::PRODUCT_EKSPRESS09 => [self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_SATURDAY_DELIVERY],
            BringData::PRODUCT_PICKUP_PARCEL => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_OPTION],
            BringData::PRODUCT_PICKUP_PARCEL_BULK => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_OPTION],
            BringData::PRODUCT_HOME_DELIVERY_PARCEL => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION],
            BringData::PRODUCT_BUSINESS_PARCEL => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_BUSINESS_PARCEL_BULK => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_EXPRESS_NORDIC_0900_BULK => [self::ADDITIONAL_SERVICE_CASH_ON_DELIVERY, self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_BUSINESS_PALLET => [self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_BUSINESS_PARCEL_HALFPALLET => [self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_BUSINESS_PARCEL_QUARTERPALLET => [self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS],
            BringData::PRODUCT_EXPRESS_NORDIC_0900 => [self::ADDITIONAL_SERVICE_FLEX_DELIVERY, self::ADDITIONAL_SERVICE_RECIPIENT_NOTIFICATION, self::ADDITIONAL_SERVICE_PHONE_NOTIFICATION, self::ADDITIONAL_SERVICE_DELIVERY_INDOORS]
        ];
    }

    public function setId ($id) {
        if (!in_array($id, BringData::validProducts())) {
            throw new \InvalidArgumentException("$id is not a valid product. Valid products are: " . implode(', ', self::VALID_PRODUCTS));
        }
        $this->setData('id', $id);
    }

    public function setCustomerNumber ($customerNumber) {
        $this->setData('customerNumber', $customerNumber);
    }

    public function addService ($service) {
        $this->addData('services', $service);
    }

    public function setServices ($services) {
        $this->_data['services'] = $services;
    }

    public function validate()
    {
        if (!$this->containsData('id') || !$this->getData('id')) {
            throw new ContractValidationException('BookingRequest\Consignment\Product requires "id" to be set.');
        }
        if (!$this->containsData('customerNumber') || !$this->getData('customerNumber')) {
            throw new ContractValidationException('BookingRequest\Consignment\Product requires "customerNumber" to be set.');
        }

        // Check service mapping..
        $packageId = $this->getData('id');
        if ($services = $this->getData('services')) {
            $map = self::serviceMapping();
            $allowed_services = $map[$packageId];
            foreach ($services as $service => $value) {
                if (!in_array($service, $allowed_services)) {
                    throw new ContractValidationException('BookingRequest\Consignment\Product has invalid service set ("'.$service.'"). Allowed services are: ' . implode(',', $allowed_services));
                }
            }
        }


    }
}