<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Generator;

use Regniblod\SymfonyDDD\GeneratorBundle\Value\Bundle;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class ServicesGenerator extends Generator
{
    /**
     * @param Bundle $bundle
     */
    public function generateServices(Bundle $bundle)
    {
        $parameters = [
            'project_name' => $bundle->getProjectName(),
            'module_name' => $bundle->getModuleName(),
        ];

        $this->renderFile(
            'bundle/services_ddd.yml.twig',
            $bundle->getTargetDirectory().'/Resources/config/services.yml',
            $parameters
        );
    }
}
