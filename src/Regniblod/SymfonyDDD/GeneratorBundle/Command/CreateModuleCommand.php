<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateModuleCommand extends ContainerAwareCommand
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $rootDir;

    /** @var string */
    private $projectName;

    /** @var string */
    private $moduleName;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('symfony-ddd:generator:create-module')
            ->setDescription('Creates a new module folder structure.')
            ->addArgument('module-name', InputArgument::REQUIRED, 'Name of the new module.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->filesystem = new Filesystem();
        $this->rootDir = $this->getContainer()->getParameter('kernel.root_dir').'/..';
        $this->projectName = $this->getProjectName();
        $this->moduleName = ucfirst(strtolower($input->getArgument('module-name')));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createApplicationDirectory();
        $this->createDomainDirectory();
        $this->createInfrastructureDirectory();
        $this->createTestsDirectory();
    }

    private function createApplicationDirectory()
    {
        $this->createDir('Application');
        $this->createDir(sprintf('Application/%sBundle', $this->moduleName));
        $this->createEmptyDir(sprintf('Application/%sBundle/Command', $this->moduleName));
        $this->createEmptyDir(sprintf('Application/%sBundle/Controller', $this->moduleName));
        $this->createDir(sprintf('Application/%sBundle/DependencyInjection', $this->moduleName));
        $this->createDir(sprintf('Application/%sBundle/Resources', $this->moduleName));
        $this->createEmptyDir(sprintf('Application/%sBundle/Resources/config/doctrine', $this->moduleName));
        $this->createEmptyDir(sprintf('Application/%sBundle/Resources/views', $this->moduleName));
    }

    private function createDomainDirectory()
    {
        $this->createDir('Domain');
        $this->createEmptyDir('Domain/Component');
        $this->createEmptyDir('Domain/Event');
        $this->createEmptyDir('Domain/Exception');
        $this->createEmptyDir('Domain/Model');
        $this->createEmptyDir('Domain/Repository');
        $this->createEmptyDir('Domain/Service');
        $this->createEmptyDir('Domain/Value');
    }

    private function createInfrastructureDirectory()
    {
        $this->createDir('Infrastructure');
        $this->createEmptyDir('Infrastructure/Migrations/Doctrine');
        $this->createEmptyDir('Infrastructure/Repository/Doctrine');
        $this->createEmptyDir('Infrastructure/Service');
    }

    private function createTestsDirectory()
    {
    }

    /**
     * @param string $dirName
     */
    private function createDir($dirName)
    {
        $this->filesystem->mkdir($this->getFullPath($dirName));
    }

    /**
     * @param string $dirName
     */
    private function createEmptyDir($dirName)
    {
        $fullPath = $this->getFullPath($dirName);

        $this->filesystem->mkdir($fullPath);
        $this->filesystem->touch($fullPath.'/.gitkeep');
    }

    /**
     * @param string $dirName
     * @return string
     */
    private function getFullPath($dirName)
    {
        return sprintf(
            '%s/src/%s/%s/%s',
            $this->rootDir,
            $this->projectName,
            $this->moduleName,
            $dirName
        );
    }

    /**
     * @return string
     */
    private function getProjectName()
    {
        $fullPath = glob($this->rootDir.'/src/*');
        $pathArray = explode('/', $fullPath[0]);

        return end($pathArray);
    }
}
