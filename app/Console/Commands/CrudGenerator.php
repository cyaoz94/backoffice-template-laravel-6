<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate {name : Class (singular) for example User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->model($name);
        $this->controller($name);
        $this->resource($name);

        // take note to modify the output if route resources need to be in a group / assigned with a middleware
        File::append(base_path('custom/routes/api.php'),
            'Route::apiResource(\'' . Str::plural(strtolower($name)) . "', '{$name}Controller');");
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(['{{modelName}}'], [$name], $this->getStub('Model'));

        if (!file_exists(customPath('Models'))) {
            mkdir(customPath('Models'));
        }

        file_put_contents(customPath("Models/$name.php"), $modelTemplate);
    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace(['{{modelName}}'], [$name], $this->getStub('Controller'));

        if (!file_exists(customPath('Controllers'))) {
            mkdir(customPath('Controllers'));
        }

        file_put_contents(customPath("Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function resource($name)
    {
        $resourceTemplate = str_replace(['{{modelName}}'], [$name], $this->getStub('Resource'));

        if (!file_exists(customPath('Resources'))) {
            mkdir(customPath('Resources'));
        }

        file_put_contents(customPath("Resources/{$name}Resource.php"), $resourceTemplate);
    }

}
