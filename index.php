<?php

include_once __DIR__ . "/Core/Loader/Loader.php";

use Core\Loader;


Loader::explore(__DIR__, "Interface");
Loader::explore(__DIR__, "", "Interface");

\Core\Kernel::initialize();