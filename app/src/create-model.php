<?php

// Retrieve filename from supplied arguments
$name = ucfirst($argv[1]);
$filename = "$name/$name.php";

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
    die ("$name.php already exists.\n");
}

// Create Scaffolding for model.
echo "Creating scaffolding for model class $name.\n";

// Create file
file_put_contents($filename, 
"<?php
namespace Joah\\$name;

/**
* Model $name
*
*/
class $name extends \Anax\MVC\CDatabaseModel
{
}    
");