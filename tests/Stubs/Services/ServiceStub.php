<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\Services;

use EoneoPay\Utils\Interfaces\ArrInterface;

final class ServiceStub
{
    /**
     * @var \EoneoPay\Utils\Interfaces\ArrInterface
     */
    private $arr;

    /**
     * ServiceStub constructor.
     *
     * @param \EoneoPay\Utils\Interfaces\ArrInterface $arr
     */
    public function __construct(ArrInterface $arr)
    {
        $this->arr = $arr;
    }

    /**
     * Get array units instance.
     *
     * @return \EoneoPay\Utils\Interfaces\ArrInterface
     */
    public function getArr(): ArrInterface
    {
        return $this->arr;
    }
}
