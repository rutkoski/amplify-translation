<?php 

namespace Amplify\Translation;

use Amplify\Translation\Console\CleanCommand;
use Amplify\Translation\Console\CloneCommand;
use Amplify\Translation\Console\ExportCommand;
use Amplify\Translation\Console\FindCommand;
use Amplify\Translation\Console\ImportCommand;
use Amplify\Translation\Console\ResetCommand;
use Amplify\Translation\Console\SuffixCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->_basicRegister();

        $this->_commandsRegister();        

        $this->_managerRegister();    
    }

    private function _basicRegister() 
    {
        $configPath = __DIR__ . '/../config/amplify-translation.php';
        $this->mergeConfigFrom($configPath, 'amplify-translation');
        $this->publishes([
            $configPath => config_path('amplify-translation.php')
        ], 'config');
    }

    private function _commandsRegister() 
    {
        foreach($this->commandsList() as $name => $class) {
            $this->initCommand($name, $class);
        }
    }

    protected function commandsList()
    {
        return [
            'reset' => ResetCommand::class,
            'import' => ImportCommand::class,
            'find' => FindCommand::class,
            'export' => ExportCommand::class,
            'clean' => CleanCommand::class,
            'clone' => CloneCommand::class,
            'suffix' => SuffixCommand::class,
        ];
    }

    private function initCommand($name, $class)
    {
        $this->app->singleton("command.amplify-translation.{$name}", function($app) use ($class) {
            return new $class($app['amplify-translation']);
        });

        $this->commands("command.amplify-translation.{$name}");
    }

    private function _managerRegister() 
    {
        $this->app->singleton('amplify-translation', function($app) {
            return $app->make(Manager::class);
        });
    }

    /**
	 * Bootstrap the application events.
	 *
     * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
        $this->loadViews();
        $this->loadMigrations();
        $this->loadTranslations();
        $this->loadRoutes($router);
	}

    protected function loadViews()
    {
        $viewPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewPath, 'amplify-translation');
        $this->publishes([
            $viewPath => resource_path('views/vendor/amplify-translation'),
        ], 'views');
    }

    protected function loadMigrations()
    {
        $migrationPath = __DIR__.'/../database/migrations';
        $this->publishes([
            $migrationPath => base_path('database/migrations'),
        ], 'migrations');
    }

    protected function loadTranslations()
    {
        $translationPath = __DIR__.'/../resources/lang';
        $this->loadTranslationsFrom($translationPath, 'amplify-translation');
        
        $this->publishes([
            $translationPath => resource_path('lang/vendor/amplify-translation'),
        ], 'translations');
    }

    public function loadRoutes($router) {        
        $config = $this->routeConfig();

        $router->group($config, function($router) {
            $router->get('/', 'Controller@getIndex')->name('amplify-translation.index');
            $router->get('/view/{group?}/{group2?}/{group3?}/{group4?}/{group5?}', 'Controller@getView')->name('amplify-translation.view');
            $router->post('/add/{group}/{group2?}/{group3?}/{group4?}/{group5?}', 'Controller@postAdd')->name('amplify-translation.add');
            $router->post('/edit/{group}/{group2?}/{group3?}/{group4?}/{group5?}', 'Controller@postEdit')->name('amplify-translation.edit');
            $router->post('/delete/{key}/{group}/{group2?}/{group3?}/{group4?}/{group5?}', 'Controller@postDelete')->name('amplify-translation.delete');
            $router->post('/publish/{group}/{group2?}/{group3?}/{group4?}/{group5?}', 'Controller@postPublish')->name('amplify-translation.publish');
            $router->post('/import', 'Controller@postImport')->name('amplify-translation.import');
            $router->post('/clean', 'Controller@postClean')->name('amplify-translation.clean');
            $router->post('/find', 'Controller@postFind')->name('amplify-translation.find');

            $router->post('custom-update', 'Controller@postEditAndExport')->name('amplify-translation.update');
        });
    }

    private function routeConfig() {
        return $this->app['config']->get('amplify-translation.route', []);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
            'amplify-translation',
            'command.amplify-translation.reset',
            'command.amplify-translation.import',
            'command.amplify-translation.find',
            'command.amplify-translation.export',
            'command.amplify-translation.clean',
            'command.amplify-translation.clone',
            'command.amplify-translation.suffix',
        ];
	}

}
