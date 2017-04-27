<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Service;

use Regniblod\SymfonyDDD\GeneratorBundle\Factory\BundleFactory;
use Regniblod\SymfonyDDD\GeneratorBundle\Generator\RepositoryServicesGenerator;
use Regniblod\SymfonyDDD\GeneratorBundle\Generator\ServicesGenerator;
use Regniblod\SymfonyDDD\GeneratorBundle\Manipulator\DoctrineMappingsManipulator;
use Regniblod\SymfonyDDD\GeneratorBundle\Value\Bundle;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleManager
{
    /** @var Filesystem */
    private $filesystem;

    /** @var BundleFactory */
    private $bundleFactory;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->bundleFactory = new BundleFactory($filesystem);
    }

    /**
     * @param string $projectName
     * @param string $moduleName
     * @return Bundle
     */
    public function createBundle($projectName, $moduleName)
    {
        return $this->bundleFactory->createBundle($projectName, $moduleName);
    }

    /**
     * @param Bundle          $bundle
     * @param KernelInterface $kernel
     */
    public function addToKernel(Bundle $bundle, KernelInterface $kernel)
    {
        $kernelManipulator = new KernelManipulator($kernel);
        $kernelManipulator->addBundle($bundle->getBundleClassName());
    }

    /**
     * @param string $rootDir
     * @param Bundle $bundle
     */
    public function addRouting($rootDir, Bundle $bundle)
    {
        $routingPath = $rootDir.'/app/config/routing.yml';

        $routingManipulator = new RoutingManipulator($routingPath);
        $routingManipulator->addResource(
            $bundle->getName(),
            $bundle->getConfigurationFormat()
        );
    }

    /**
     * @param string $rootDir
     * @param Bundle $bundle
     */
    public function addDoctrineMapping($rootDir, Bundle $bundle)
    {
        $configPath = $rootDir.'/app/config/config.yml';

        $doctrineMappingsManipulator = new DoctrineMappingsManipulator();
        $doctrineMappingsManipulator->addMappings($configPath, $bundle);
    }

    /**
     * @param Bundle $bundle
     */
    public function addRepositoryServices(Bundle $bundle)
    {
        $repositoryServicesGenerator = new RepositoryServicesGenerator();
        $repositoryServicesGenerator->setSkeletonDirs([__DIR__.'/../Resources/skeleton']);
        $repositoryServicesGenerator->generateRepositoryServices($bundle);
    }

    /**
     * @param Bundle $bundle
     */
    public function addServices(Bundle $bundle)
    {
        $repositoryServicesGenerator = new ServicesGenerator();
        $repositoryServicesGenerator->setSkeletonDirs([__DIR__.'/../Resources/skeleton']);
        $repositoryServicesGenerator->generateServices($bundle);
    }
}
