<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Generator;

use Regniblod\SymfonyDDD\GeneratorBundle\Value\Bundle;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class RepositoryServicesGenerator extends Generator
{
    /**
     * @param Bundle $bundle
     */
    public function generateRepositoryServices(Bundle $bundle)
    {
        $parameters = [
            'entity_name' => 'user',
            'project_name' => $bundle->getProjectName(),
            'module_name' => $bundle->getModuleName(),
        ];

        $this->renderFile(
            'bundle/repositories.yml.twig',
            $bundle->getTargetDirectory().'/Resources/config/repositories.yml',
            $parameters
        );
    }
}
