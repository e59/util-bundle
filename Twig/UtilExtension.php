<?php
namespace Cangulo\UtilBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Environment;
use utilphp\util;

class UtilExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mask', array($this, 'maskFilter')),
            new \Twig_SimpleFilter('dinheiro', array($this, 'dinheiro')),
            new \Twig_SimpleFilter('dia', array($this, 'dia')),
        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('get_parameter', array($this, 'get_parameter')),

        );
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('date', array($this, 'isDate')),
        ];
    }

    public function get_parameter($name) {
        return $this->container->getParameter($name);
    }

    public function isDate($obj)
    {
        return $obj instanceof \DateTime;
    }

    public function dia($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function dinheiro($value, $currency = null, $locale = null)
    {
        $currency = $currency ?? $this->container->getParameter('currency');
        return \twig_localized_currency_filter($value, $currency, $locale);
    }

    public function maskFilter($string, $mask, $placeholder = '#')
    {
        return util::mask($string, $mask, $placeholder);
    }

    public function getName()
    {
        return 'cangulo_utilbundle_extension';
    }
}
