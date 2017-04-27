<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Value;

use Sensio\Bundle\GeneratorBundle\Model\Bundle as SensioGeneratorBundle;

class Bundle extends SensioGeneratorBundle
{
    /** @var string */
    private $projectName;

    /** @var string */
    private $moduleName;

    /**
     * @param string $namespace
     * @param string $name
     * @param string $targetDirectory
     * @param string $configurationFormat
     * @param string $isShared
     * @param string $projectName
     * @param string $moduleName
     */
    public function __construct(
        $namespace,
        $name,
        $targetDirectory,
        $configurationFormat,
        $isShared,
        $projectName,
        $moduleName
    ) {
        parent::__construct(
            $namespace,
            $name,
            $targetDirectory,
            $configurationFormat,
            $isShared
        );
        $this->projectName = $projectName;
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }
}
