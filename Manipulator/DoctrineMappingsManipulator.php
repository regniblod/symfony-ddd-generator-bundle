<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Manipulator;

use Regniblod\SymfonyDDD\GeneratorBundle\Value\Bundle;

class DoctrineMappingsManipulator
{
    /**
     * @param string $configPath
     * @param Bundle $bundle
     */
    public function addMappings($configPath, Bundle $bundle)
    {
        $modelPath = sprintf(
            '%s\\%s\\Domain\\Model',
            $bundle->getProjectName(),
            $bundle->getModuleName()
        );

        // Commented this since Yaml component doesn't support parsing comments yet.
        // See https://github.com/symfony/symfony/issues/22516
        // $config = Yaml::parse(file_get_contents($configPath));
        // $config['doctrine']['orm']['mappings'][$bundle->getName()]['prefix'] = $modelPath;
        // file_put_contents($configPath, Yaml::dump($config));

        $config = file_get_contents($configPath);
        $mapping = "\n            {$bundle->getName()}: { prefix: $modelPath }";
        $pos = strpos($config, 'mappings:') + strlen('mappings:');
        $config = substr_replace($config, $mapping, $pos, 0);
        file_put_contents($configPath, $config);
    }
}
