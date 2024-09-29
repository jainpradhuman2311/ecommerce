<?php

namespace Drupal\commerce_square;

use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Drupal\commerce_payment\Exception\HardDeclineException;
use Drupal\commerce_payment\Exception\InvalidResponseException;
use Drupal\commerce_payment\Exception\SoftDeclineException;
use Drupal\Component\Serialization\Json;
use Square\Exceptions\ApiException;

/**
 * Translates Square exceptions and errors into Commerce exceptions.
 *
 * @see https://docs.connect.squareup.com/api/connect/v2/#type-errorcategory
 * @see https://docs.connect.squareup.com/api/connect/v2/#type-errorcode
 */
class ErrorHelper {

  /**
   * Translates Square exceptions into Commerce exceptions.
   *
   * @param \Square\Exceptions\ApiException $exception
   *   The Square exception.
   * @param \Drupal\commerce_payment\Entity\PaymentMethodInterface|\Drupal\commerce_payment\Entity\PaymentInterface|null $payment
   *   The payment or payment method, if available.
   *
   * @return \Drupal\commerce_payment\Exception\PaymentGatewayException
   *   The Commerce exception.
   */
  public static function convertException(ApiException $exception, PaymentMethodInterface|PaymentInterface|null $payment = NULL) {
    $errors = Json::decode($exception->getMessage());
    $error = isset($errors['errors']) ? reset($errors['errors']) : [];

    if (!empty($error)) {
      switch ($error['category']) {
        case 'PAYMENT_METHOD_ERROR':
          return SoftDeclineException::createForPayment($payment, $error['detail']);

        case 'REFUND_ERROR':
          return HardDeclineException::createForPayment($payment, $error['detail']);

        default:
          // All other error categories are API request related.
          return InvalidResponseException::createForPayment($payment, $exception->getMessage(), $exception->getCode(), $exception);
      }
    }
    else {
      return InvalidResponseException::createForPayment($payment, $exception->getMessage(), $exception->getCode(), $exception);
    }
  }

}
