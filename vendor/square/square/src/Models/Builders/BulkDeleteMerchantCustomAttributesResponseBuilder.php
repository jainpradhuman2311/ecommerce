<?php

declare(strict_types=1);

namespace Square\Models\Builders;

use Core\Utils\CoreHelper;
use Square\Models\BulkDeleteMerchantCustomAttributesResponse;

/**
 * Builder for model BulkDeleteMerchantCustomAttributesResponse
 *
 * @see BulkDeleteMerchantCustomAttributesResponse
 */
class BulkDeleteMerchantCustomAttributesResponseBuilder
{
    /**
     * @var BulkDeleteMerchantCustomAttributesResponse
     */
    private $instance;

    private function __construct(BulkDeleteMerchantCustomAttributesResponse $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Initializes a new bulk delete merchant custom attributes response Builder object.
     */
    public static function init(array $values): self
    {
        return new self(new BulkDeleteMerchantCustomAttributesResponse($values));
    }

    /**
     * Sets errors field.
     */
    public function errors(?array $value): self
    {
        $this->instance->setErrors($value);
        return $this;
    }

    /**
     * Initializes a new bulk delete merchant custom attributes response object.
     */
    public function build(): BulkDeleteMerchantCustomAttributesResponse
    {
        return CoreHelper::clone($this->instance);
    }
}
