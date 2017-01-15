<?php
namespace Cangulo\UtilBundle\EventSubscriber;

use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InitializationSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container) {
        $this->setContainer($container);
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            KernelEvents::REQUEST => array(
                array('configure', 0),
            )
        );
    }

    public function configure(GetResponseEvent $event)
    {
        $container = $this->container;
        setlocale(LC_ALL, $container->getParameter('system_locale'));
        locale_set_default($container->getParameter('system_locale'));
        \Locale::setDefault($container->getParameter('system_locale'));
        date_default_timezone_set($container->getParameter('timezone'));
        Carbon::setLocale($container->getParameter('locale'));

    }

}
