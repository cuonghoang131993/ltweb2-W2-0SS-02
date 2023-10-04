<?php

declare(strict_types=1);

namespace Application;

// Add these import statements:
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Application\Model;
use Application\Controller;

class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\LopTable::class => function($container) {
                    $tableGateway = $container->get(LopTableGateway::class);
                    return new Model\LopTable($tableGateway);
                },
                LopTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\LopEntity());
                    return new TableGateway('lophoc', $dbAdapter, null, $resultSetPrototype);
                },
                MonHocTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\MonHocEntity());
                    return new TableGateway('monhoc', $dbAdapter, null, $resultSetPrototype);
                },
                Model\MonHocTable::class => function($sm) {
                    $tableGateway = $sm->get(MonHocTableGateway::class);
                    return new Model\MonHocTable($tableGateway);
                },
                HocVienTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\HocVienEntity());
                    return new TableGateway('sinhvien', $dbAdapter, null, $resultSetPrototype);
                },
                Model\HocVienTable::class => function($container) {
                    $tableGateway = $container->get(HocVienTableGateway::class);
                    return new Model\HocVienTable($tableGateway);
                },
                DangKyTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\DangKyEntity());
                    return new TableGateway('dkhoc', $dbAdapter, null, $resultSetPrototype);
                },
                Model\DangKyTable::class => function($container) {
                    $tableGateway = $container->get(DangKyTableGateway::class);
                    return new Model\DangKyTable($tableGateway);
                },
            ],
        ];
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(Model\LopTable::class),
                        $container->get(Model\MonHocTable::class)
                    );
                },
                Controller\MonHocController::class => function($container) {
                    return new Controller\MonHocController(
                        $container->get(Model\MonHocTable::class)
                    );
                },
                Controller\HocVienController::class => function($container) {
                    return new Controller\HocVienController(
                        $container->get(Model\HocVienTable::class)
                    );
                },
                Controller\DangKyController::class => function($container) {
                    return new Controller\DangKyController(
                        $container->get(Model\DangKyTable::class)
                    );
                },
            ],
        ];
    }
}
