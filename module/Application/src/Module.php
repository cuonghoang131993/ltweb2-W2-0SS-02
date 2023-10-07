<?php

declare(strict_types=1);

namespace Application;

// Add these import statements:
use Laminas\Db\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session;
use \Application\Model;
use \Application\Controller;
use \Application\Util;

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
                Model\LopTable::class => function ($container) {
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
                Model\MonHocTable::class => function ($sm) {
                    $tableGateway = $sm->get(MonHocTableGateway::class);
                    return new Model\MonHocTable($tableGateway);
                },
                HocVienTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\HocVienEntity());
                    return new TableGateway('sinhvien', $dbAdapter, null, $resultSetPrototype);
                },
                Model\HocVienTable::class => function ($container) {
                    $tableGateway = $container->get(HocVienTableGateway::class);
                    return new Model\HocVienTable($tableGateway);
                },
                DangKyTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\DangKyEntity());
                    return new TableGateway('dkhoc', $dbAdapter, null, $resultSetPrototype);
                },
                Model\DangKyTable::class => function ($container) {
                    $tableGateway = $container->get(DangKyTableGateway::class);
                    return new Model\DangKyTable($tableGateway);
                },

                    // Authentication
                UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();

                    //pass base url via cnstructor to the User class
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserEntity());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                Model\UserTable::class => function ($container) {
                    $tableGateway = $container->get(UserTableGateway::class);
                    $table = new Model\UserTable($tableGateway);

                    return $table;
                },
                Util\Authentication::class => function ($container) {
                    $auth = new Util\Authentication(
                        $container->get(Adapter\Adapter::class)
                    );
                    return $auth;
                },
                Helper\Password::class => InvokableFactory::class,
                SessionManager::class => function ($container) {
                    $config = $container->get('config');
                    $session = $config['session'];
                    $sessionConfig = new $session['config']['class']();
                    $sessionConfig->setOptions($session['config']['options']);
                    $sessionManager = new Session\SessionManager(
                        $sessionConfig,
                        new $session['storage'](),
                        null
                    );
                    Session\Container::setDefaultManager($sessionManager);

                    return $sessionManager;
                },
            ],
        ];
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new Controller\IndexController(
                        $container->get(Model\LopTable::class),
                        $container->get(Model\MonHocTable::class)
                    );
                },
                Controller\MonHocController::class => function ($container) {
                    return new Controller\MonHocController(
                        $container->get(Model\MonHocTable::class)
                    );
                },
                Controller\HocVienController::class => function ($container) {
                    return new Controller\HocVienController(
                        $container->get(Model\HocVienTable::class)
                    );
                },
                Controller\DangKyController::class => function ($container) {
                    return new Controller\DangKyController(
                        $container->get(Model\DangKyTable::class)
                    );
                },
                Controller\LoginController::class => function ($sm) {
                    return new Controller\LoginController(
                        $sm->get(Util\Authentication::class)
                    );
                },
                Controller\RegisterController::class => function ($container) {
                    return new Controller\RegisterController(
                        $container->get(Model\UserTable::class),
                        $container->get(Util\Authentication::class),
                        $container->get(Helper\Password::class)
                    );
                },
                Controller\LoginController::class => function ($container) {
                    return new Controller\LoginController(
                        $container->get(Util\Authentication::class)
                    );
                },
                Controller\LogoutController::class => InvokableFactory::class,
            ],
        ];
    }

    public function onBootstrap($e)
    {
        $this->bootstrapSession($e);
    }

    public function bootstrapSession($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $session = $serviceManager->get(SessionManager::class);
        $session->start();
        $container = new Session\Container('initialized');

        //let’s check if our session is not already created (for the guest or user)
        if (isset($container->init)) {
            return;
        }

        //new session creation
        $request = $serviceManager->get('Request');
        $session->regenerateId(true);
        $container->init = 1;
        $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
        $config = $serviceManager->get('Config');
        $sessionConfig = $config['session'];
        $chain = $session->getValidatorChain();

        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
                case Validator\HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                case Validator\RemoteAddr::class:
                    $validator = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }
            $chain->attach('session.validate', array($validator, 'isValid'));
        }
    }
}