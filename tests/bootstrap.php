<?php


use Magento\Framework\Code\Generator\ClassGenerator;

class NamespaceGenerator implements \Magento\Framework\TestFramework\Unit\Autoloader\GeneratorInterface
{
    private $namespace = '';

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function generate($className)
    {
        if (strpos($className, $this->namespace) === 0) {
            $classGenerator = new ClassGenerator();
            $classGenerator->setName($className);

            return $classGenerator->generate();
        }

        return false;
    }
}




$autoloader = new \Magento\Framework\TestFramework\Unit\Autoloader\GeneratedClassesAutoloader(
    [
        new \Magento\Framework\TestFramework\Unit\Autoloader\FactoryGenerator(),
        new NamespaceGenerator('jsiefer\\'),
    ],
    new \Magento\Framework\Code\Generator\Io(
        new \Magento\Framework\Filesystem\Driver\File(),
         __DIR__ . '/generation'
    )
);


spl_autoload_register([$autoloader, 'load']);