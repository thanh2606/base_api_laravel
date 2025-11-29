<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:make-service {name} {--force}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new service and interface class';

    /**
     * Filesystem instance
     */
    protected $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $force = $this->option('force');

        // Parse path and class name
        $pathInfo = $this->parsePath($name);

        // Generate Interface
        $this->generateInterface($pathInfo, $force);

        // Generate Service
        $this->generateService($pathInfo, $force);

        $this->info("Service and Interface for {$pathInfo['className']} created successfully!");
    }

    /**
     * Parse the path and extract information
     */
    protected function parsePath($name)
    {
        // Remove .php extension if provided
        $name = str_replace('.php', '', $name);

        // Split path and class name
        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subPath = implode('/', $parts);

        // Remove 'Service' suffix if exists to get base name
        $baseName = str_replace('Service', '', $className);

        return [
            'className' => $className,
            'baseName' => $baseName,
            'subPath' => $subPath,
            'interfaceName' => $baseName.'ServiceInterface',
            'serviceNamespace' => $this->buildNamespace('App\\Services', $subPath),
            'interfaceNamespace' => $this->buildNamespace('App\\Services', $subPath),
            'servicePath' => $this->buildFilePath('Services', $subPath, $className.'.php'),
            'interfacePath' => $this->buildFilePath('Services', $subPath, $baseName.'ServiceInterface.php'),
        ];
    }

    /**
     * Build namespace
     */
    protected function buildNamespace($baseNamespace, $subPath)
    {
        if (empty($subPath)) {
            return $baseNamespace;
        }

        $subNamespace = str_replace('/', '\\', $subPath);

        return $baseNamespace.'\\'.$subNamespace;
    }

    /**
     * Build file path
     */
    protected function buildFilePath($baseDir, $subPath, $fileName): string
    {
        $basePath = app_path($baseDir);

        if (!empty($subPath)) {
            $basePath .= '/'.$subPath;
        }

        return $basePath.'/'.$fileName;
    }

    /**
     * Generate Interface
     */
    protected function generateInterface($pathInfo, $force): void
    {
        $path = $pathInfo['interfacePath'];

        if ($this->files->exists($path) && !$force) {
            $this->error("Interface {$pathInfo['interfaceName']} already exists!");

            return;
        }

        $this->makeDirectory($path);

        $stub = $this->getInterfaceStub();
        $content = $this->replaceStubVariables($stub, [
            'NAMESPACE' => $pathInfo['interfaceNamespace'],
            'CLASS_NAME' => $pathInfo['interfaceName'],
        ]);

        $this->files->put($path, $content);
        $this->info("Interface created: {$path}");
    }

    /**
     * Generate Service
     */
    protected function generateService($pathInfo, $force): void
    {
        $path = $pathInfo['servicePath'];

        if ($this->files->exists($path) && !$force) {
            $this->error("Service {$pathInfo['className']} already exists!");

            return;
        }

        $this->makeDirectory($path);

        $stub = $this->getServiceStub();
        $content = $this->replaceStubVariables($stub, [
            'NAMESPACE' => $pathInfo['serviceNamespace'],
            'CLASS_NAME' => $pathInfo['className'],
            'INTERFACE_NAME' => $pathInfo['interfaceName'],
            'INTERFACE_NAMESPACE' => $pathInfo['interfaceNamespace'],
        ]);

        $this->files->put($path, $content);
        $this->info("Service created: {$path}");
    }

    /**
     * Create directory if not exists
     */
    protected function makeDirectory($path): void
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }

    /**
     * Replace stub variables
     */
    protected function replaceStubVariables($stub, $variables)
    {
        foreach ($variables as $key => $value) {
            $stub = str_replace("{{ {$key} }}", $value, $stub);
        }

        return $stub;
    }

    /**
     * Get Interface Stub
     */
    protected function getInterfaceStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

interface {{ CLASS_NAME }}
{
    //
}
STUB;
    }

    /**
     * Get Service Stub
     */
    protected function getServiceStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use {{ INTERFACE_NAMESPACE }}\{{ INTERFACE_NAME }};

class {{ CLASS_NAME }} implements {{ INTERFACE_NAME }}
{
    //
}
STUB;
    }
}
