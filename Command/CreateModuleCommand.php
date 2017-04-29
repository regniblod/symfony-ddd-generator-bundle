<?php

namespace Regniblod\SymfonyDDD\GeneratorBundle\Command;

use Regniblod\SymfonyDDD\GeneratorBundle\Helper\PathHelper;
use Regniblod\SymfonyDDD\GeneratorBundle\Service\BundleManager;
use Regniblod\SymfonyDDD\GeneratorBundle\Service\ModuleFilesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class CreateModuleCommand extends ContainerAwareCommand
{
    /** @var ModuleFilesystem */
    private $moduleFilesystem;

    /** @var string */
    private $rootDir;

    /** @var string */
    private $projectName;

    /** @var string */
    private $moduleName;

    /** @var BundleManager */
    private $bundleManager;

    /** @var SymfonyStyle */
    private $io;

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
        $this->rootDir = $this->getContainer()->getParameter('kernel.root_dir').'/..';
        $this->projectName = PathHelper::getProjectName($this->rootDir);
        $this->moduleName = ucfirst(strtolower($input->getArgument('module-name')));
        $this->bundleManager = new BundleManager(new Filesystem());
        $this->moduleFilesystem = new ModuleFilesystem(new Filesystem(), $this->rootDir, $this->moduleName);
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title(sprintf('Creating new module [%s]', $this->moduleName));

        $this->createApplicationDirectory();
        $this->createDomainDirectory();
        $this->createInfrastructureDirectory();
        $this->createTestsDirectory();

        $this->io->success(sprintf('Module [%s] created!', $this->moduleName));
    }

    private function createApplicationDirectory()
    {
        $this->moduleFilesystem->createSrcDir('Application');

        $bundle = $this->bundleManager->createBundle($this->projectName, $this->moduleName);
        $this->io->text('Bundle created in '.$bundle->getTargetDirectory());
        $this->bundleManager->addToKernel($bundle, $this->getContainer()->get('kernel'));
        $this->io->text('Bundle added to the kernel.');
        $this->bundleManager->addRouting($this->rootDir, $bundle);
        $this->io->text('Bundle routing added to app/routing.yml');
        $this->bundleManager->addDoctrineMapping($this->rootDir, $bundle);
        $this->io->text('Bundle Doctrine mappings added to app/config.yml');
        $this->bundleManager->addRepositoryServices($bundle);
        $this->io->text('Bundle repository services created in '.$bundle->getName().'/Resources/config/repositories.yml');
        $this->bundleManager->addServices($bundle);
        $this->io->text('Bundle services created in '.$bundle->getName().'/Resources/config/services.yml');

        $bundlePath = 'Application/Bundle/'.$bundle->getName();
        $this->moduleFilesystem->createSrcDirWithGitkeep("$bundlePath/Command");
        $this->moduleFilesystem->createSrcDirWithGitkeep("$bundlePath/Resources/config/doctrine");
        $this->moduleFilesystem->removeSrcDir("$bundlePath/Tests");

        $this->io->block('Application directories created.');
    }

    private function createDomainDirectory()
    {
        $this->moduleFilesystem->createSrcDir('Domain');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Component');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Event');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Exception');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Model');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Repository');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Service');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Domain/Value');

        $this->io->block('Domain directories created.');
    }

    private function createInfrastructureDirectory()
    {
        $this->moduleFilesystem->createSrcDir('Infrastructure');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Infrastructure/Migrations/Doctrine');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Infrastructure/Repository/Doctrine/ORM');
        $this->moduleFilesystem->createSrcDirWithGitkeep('Infrastructure/Service');

        $this->io->block('Infrastructure directories created.');
    }

    private function createTestsDirectory()
    {
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Component');
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Event');
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Exception');
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Model');
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Service');
        $this->moduleFilesystem->createTestsDirWithGitkeep('Domain/Value');

        $this->moduleFilesystem->createTestsDirWithGitkeep('Infrastructure/Service');

        $this->io->block('Tests directories created.');
    }
}
