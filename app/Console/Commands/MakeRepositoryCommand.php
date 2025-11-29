<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-repository {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository and interface class';

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

        // Generate Repository
        $this->generateRepository($pathInfo, $force);

        $this->info("Repository and Interface for {$pathInfo['className']} created successfully!");
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

        // Remove 'Repository' suffix if exists to get base name
        $baseName = str_replace('Repository', '', $className);

        return [
            'className' => $className,
            'baseName' => $baseName,
            'subPath' => $subPath,
            'interfaceName' => $baseName.'RepositoryInterface',
            'repoNamespace' => $this->buildNamespace('App\\Repositories', $subPath),
            'interfaceNamespace' => $this->buildNamespace('App\\Repositories', $subPath),
            'repoPath' => $this->buildFilePath('Repositories', $subPath, $className.'.php'),
            'interfacePath' => $this->buildFilePath('Repositories', $subPath, $baseName.'RepositoryInterface.php'),
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
     * Generate Repository
     */
    protected function generateRepository($pathInfo, $force): void
    {
        $path = $pathInfo['repoPath'];

        if ($this->files->exists($path) && !$force) {
            $this->error("Repository {$pathInfo['className']} already exists!");

            return;
        }

        $this->makeDirectory($path);

        $stub = $this->getRepositoryStub();
        $content = $this->replaceStubVariables($stub, [
            'NAMESPACE' => $pathInfo['repoNamespace'],
            'CLASS_NAME' => $pathInfo['className'],
            'INTERFACE_NAME' => $pathInfo['interfaceName'],
            'INTERFACE_NAMESPACE' => $pathInfo['interfaceNamespace'],
        ]);

        $this->files->put($path, $content);
        $this->info("Repository created: {$path}");
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
     * Get Repository Stub
     */
    protected function getRepositoryStub(): string
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
