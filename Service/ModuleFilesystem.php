<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Service;

use Regniblod\SymfonyDDD\GeneratorBundle\Helper\PathHelper;
use Symfony\Component\Filesystem\Filesystem;

class ModuleFilesystem
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $rootDir;

    /** @var string */
    private $moduleName;

    /**
     * @param Filesystem $filesystem
     * @param string     $rootDir
     * @param string     $moduleName
     */
    public function __construct(Filesystem $filesystem, $rootDir, $moduleName)
    {
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
        $this->moduleName = $moduleName;
    }

    /**
     * @param string $dirName
     */
    public function createSrcDir($dirName)
    {
        $this->filesystem->mkdir(
            PathHelper::buildSrcPath($this->rootDir, $this->moduleName, $dirName)
        );
    }

    /**
     * @param string $dirName
     */
    public function removeSrcDir($dirName)
    {
        $this->filesystem->remove(
            PathHelper::buildSrcPath($this->rootDir, $this->moduleName, $dirName)
        );
    }

    /**
     * @param string $dirName
     */
    public function createSrcDirWithGitkeep($dirName)
    {
        $fullPath = PathHelper::buildSrcPath($this->rootDir, $this->moduleName, $dirName);

        $this->filesystem->mkdir($fullPath);
        $this->filesystem->touch($fullPath.'/.gitkeep');
    }

    /**
     * @param string $dirName
     */
    public function createTestsDir($dirName)
    {
        $this->filesystem->mkdir(
            PathHelper::buildTestsPath($this->rootDir, $this->moduleName, $dirName)
        );
    }

    /**
     * @param string $dirName
     */
    public function removeTestsDir($dirName)
    {
        $this->filesystem->remove(
            PathHelper::buildTestsPath($this->rootDir, $this->moduleName, $dirName)
        );
    }

    /**
     * @param string $dirName
     */
    public function createTestsDirWithGitkeep($dirName)
    {
        $fullPath = PathHelper::buildTestsPath($this->rootDir, $this->moduleName, $dirName);

        $this->filesystem->mkdir($fullPath);
        $this->filesystem->touch($fullPath.'/.gitkeep');
    }
}
