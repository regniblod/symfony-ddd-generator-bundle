<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Factory;

use Regniblod\SymfonyDDD\GeneratorBundle\Value\Bundle;
use Sensio\Bundle\GeneratorBundle\Generator\BundleGenerator;
use Symfony\Component\Filesystem\Filesystem;

class BundleFactory
{
    /** @var Filesystem */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $projectName
     * @param string $moduleName
     * @return Bundle
     */
    public function createBundle($projectName, $moduleName)
    {
        $bundleName = $moduleName.'Bundle';

        $bundle = new Bundle(
            "$projectName\\$moduleName\\Application\\Bundle\\$bundleName",
            $bundleName,
            'src',
            'yml',
            true,
            $projectName,
            $moduleName
        );

        $bundleGenerator = new BundleGenerator($this->filesystem);
        $bundleGenerator->setSkeletonDirs([__DIR__.'/../Resources/skeleton']);
        $bundleGenerator->generateBundle($bundle);

        return $bundle;
    }
}
