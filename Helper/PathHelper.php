<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Helper;

class PathHelper
{
    /**
     * @param string $rootDir
     * @param string $moduleName
     * @param string $dirName
     * @return string
     */
    public static function buildSrcPath($rootDir, $moduleName, $dirName)
    {
        return sprintf(
            '%s/src/%s/%s/%s',
            $rootDir,
            self::getProjectName($rootDir),
            $moduleName,
            $dirName
        );
    }

    /**
     * @param string $rootDir
     * @param string $moduleName
     * @param string $dirName
     * @return string
     */
    public static function buildTestsPath($rootDir, $moduleName, $dirName)
    {
        return sprintf(
            '%s/tests/%s/%s/%s',
            $rootDir,
            self::getProjectName($rootDir),
            $moduleName,
            $dirName
        );
    }

    /**
     * @param string $rootDir
     * @return string
     */
    public static function getProjectName($rootDir)
    {
        $fullPath = glob($rootDir.'/src/*');
        $pathArray = explode('/', $fullPath[0]);

        return end($pathArray);
    }
}
