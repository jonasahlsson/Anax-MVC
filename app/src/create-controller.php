<?php

// Retrieve filename from supplied arguments
$name = ucfirst($argv[1]);
$filename = "$name/{$name}Controller.php";

// Validation

// Name missing
if (empty($name)) {
    die ("Expected argument for name missing!");
}

// Check directory
else if (!is_dir($name)) {
    echo "Directory $name does not already exist. Creating...\n";
    mkdir($name);
}

// Check filename
else if (is_file($filename)) {
    die ("Controller {$name}Controller already exists.\n");
}

// Create Scaffolding for Controller.
echo "Creating scaffolding for controller class {$name}Controller.\n";

// Create file
file_put_contents($filename, 
"<?php
namespace Joah\\$name;

/**
* $name does this..
*
*/
class {$name}Controller implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectionaware;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
    }


    /**
    * Index action, default action.
    *
    */
    public function indexAction()
    {
    }
    

}
");