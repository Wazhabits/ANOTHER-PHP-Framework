<?php

include_once __DIR__ . "/Core/Loader/Loader.php";

<<<<<<< HEAD
\Core\Kernel::initialize(__DIR__);
=======
use Core\Loader;


Loader::explore(__DIR__, "Interface");
Loader::explore(__DIR__, "", "Interface");

\Core\Kernel::initialize();
>>>>>>> ecd5cdecf7062adcf502aa921bd092730ee2cebe
