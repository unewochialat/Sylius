<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\EventListener;

use PhpSpec\ObjectBehavior;

/**
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OrderShippingListenerSpec extends ObjectBehavior
{
    /**
     * @param Sylius\Bundle\CoreBundle\OrderProcessing\ShipmentFactoryInterface          $shipmentFactory
     * @param Sylius\Bundle\ShippingBundle\Processor\ShipmentProcessorInterface          $shippingProcessor
     * @param Sylius\Bundle\CoreBundle\OrderProcessing\ShippingChargesProcessorInterface $shippingChargesProcessor
     */
    function let($shipmentFactory, $shippingProcessor, $shippingChargesProcessor)
    {
        $this->beConstructedWith($shipmentFactory, $shippingProcessor, $shippingChargesProcessor);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\EventListener\OrderShippingListener');
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     * @param \stdClass                                      $invalidSubject
     */
    function it_throws_exception_if_event_has_non_order_subject($event, $invalidSubject)
    {
        $event->getSubject()->willReturn($invalidSubject);

        $this
            ->shouldThrow('InvalidArgumentException')
            ->duringProcessOrderShippingCharges($event)
        ;
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     * @param Sylius\Bundle\CoreBundle\Model\OrderInterface  $order
     */
    function it_calls_shipping_processor_on_order($shippingChargesProcessor, $event, $order)
    {
        $event->getSubject()->willReturn($order);
        $shippingChargesProcessor->applyShippingCharges($order)->shouldBeCalled();

        $this->processOrderShippingCharges($event);
    }
}
