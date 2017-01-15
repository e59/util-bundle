<?php
namespace Cangulo\UtilBundle\Data;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JansenFelipe\CepGratis\CepGratis;
use JansenFelipe\CidadesGratis\Cidades;


class Service implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const PAGSEGURO_STATUS_VAZIO = null;
    const PAGSEGURO_STATUS_AGUARDANDO_PAGAMENTO = '1';
    const PAGSEGURO_STATUS_EM_ANALISE = '2';
    const PAGSEGURO_STATUS_PAGA = '3';
    const PAGSEGURO_STATUS_DISPONIVEL = '4';
    const PAGSEGURO_STATUS_EM_DISPUTA = '5';
    const PAGSEGURO_STATUS_DEVOLVIDA = '6';
    const PAGSEGURO_STATUS_CANCELADA = '7';

    const PAGSEGURO_MAP_VAZIO = null;
    const PAGSEGURO_MAP_AGUARDANDO_PAGAMENTO = 'aguardando_pagamento';
    const PAGSEGURO_MAP_EM_ANALISE = 'em_analise';
    const PAGSEGURO_MAP_PAGA = 'paga';
    const PAGSEGURO_MAP_DISPONIVEL = 'disponivel';
    const PAGSEGURO_MAP_EM_DISPUTA = 'em_disputa';
    const PAGSEGURO_MAP_DEVOLVIDA = 'devolvida';
    const PAGSEGURO_MAP_CANCELADA = 'cancelada';

    public static $pagseguro_status = [
        self::PAGSEGURO_STATUS_VAZIO => self::PAGSEGURO_MAP_VAZIO,
        self::PAGSEGURO_STATUS_AGUARDANDO_PAGAMENTO => self::PAGSEGURO_MAP_AGUARDANDO_PAGAMENTO,
        self::PAGSEGURO_STATUS_EM_ANALISE => self::PAGSEGURO_MAP_EM_ANALISE,
        self::PAGSEGURO_STATUS_PAGA => self::PAGSEGURO_MAP_PAGA,
        self::PAGSEGURO_STATUS_DISPONIVEL => self::PAGSEGURO_MAP_DISPONIVEL,
        self::PAGSEGURO_STATUS_EM_DISPUTA => self::PAGSEGURO_MAP_EM_DISPUTA,
        self::PAGSEGURO_STATUS_DEVOLVIDA => self::PAGSEGURO_MAP_DEVOLVIDA,
        self::PAGSEGURO_STATUS_CANCELADA => self::PAGSEGURO_MAP_CANCELADA,
    ];

    public static $pagseguro = [
        self::PAGSEGURO_MAP_VAZIO => '',
        self::PAGSEGURO_MAP_AGUARDANDO_PAGAMENTO => 'Aguardando pagamento',
        self::PAGSEGURO_MAP_EM_ANALISE => 'Em análise',
        self::PAGSEGURO_MAP_PAGA => 'Paga',
        self::PAGSEGURO_MAP_DISPONIVEL => 'Disponível',
        self::PAGSEGURO_MAP_EM_DISPUTA => 'Em disputa',
        self::PAGSEGURO_MAP_DEVOLVIDA => 'Devolvida',
        self::PAGSEGURO_MAP_CANCELADA => 'Cancelada',
    ];

    public static $pagseguro_ok = [
        self::PAGSEGURO_STATUS_VAZIO => false,
        self::PAGSEGURO_STATUS_AGUARDANDO_PAGAMENTO => false,
        self::PAGSEGURO_STATUS_EM_ANALISE => false,
        self::PAGSEGURO_STATUS_PAGA => true,
        self::PAGSEGURO_STATUS_DISPONIVEL => true,
        self::PAGSEGURO_STATUS_EM_DISPUTA => false,
        self::PAGSEGURO_STATUS_DEVOLVIDA => false,
        self::PAGSEGURO_STATUS_CANCELADA => false,
    ];


    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }


    /**
     * Lista de estados do Brasil.
     *
     * @return array nome => sigla
     */
    public function ufs()
    {
        $cache = $this->container->get('cache.app');

        $ufs = $cache->getItem('ufs');

//        $cache->deleteItem('ufs');

        if (!$ufs->isHit()) {
            $ufList = Cidades::getUfs();
            $ufData = array_combine(array_column($ufList, 'nome'), array_column($ufList, 'uf'));
            asort($ufData);
            $ufs->set($ufData);
            $cache->save($ufs);
        }

        return $ufs->get();

    }

    /**
     * O propósito é ter uma estrutura de dados "separada" para armazenar endereços, pois normalmente estes dados são
     * os mesmos e também são usados na modelagem da interface de preenchimento através de consulta de CEP.
     *
     * Esta função cria uma estrutura que deve atender a este propósito.
     *
     * @param mixed $entity
     * @return array
     */
    public function enderecoStructure($entity = null)
    {
        $keys = [
            'cep',
            'logradouro',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
        ];

        if ($entity) {
            $values = array_combine($keys, [
                $entity->getCep(),
                $entity->getLogradouro(),
                $entity->getNumero(),
                $entity->getComplemento(),
                $entity->getBairro(),
                $entity->getCidade(),
                $entity->getUf(),
            ]);
        } else {
            $values = array_fill_keys($keys, null);
        }

        return $values;
    }

    /**
     * @param $cep Toda pontuação é extraída pela própria função. Deixa apenas os números
     * @return array logradouro, bairro, cidade, uf
     */
    public function cep($cep)
    {
        if (!$cep) {
            return [];
        }
        return array_map('html_entity_decode', CepGratis::consulta(preg_replace('#\D#', '', $cep)));
    }
}
