<?php 

namespace Amplify\Translation\Console;

use Amplify\Translation\Manager;
use Illuminate\Console\Command;

class CleanCommand extends Command 
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean empty translations';

    /** @var \Amplify\Translation\Manager  */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->manager->cleanTranslations();
        $this->info("Done cleaning translations");
    }

}
