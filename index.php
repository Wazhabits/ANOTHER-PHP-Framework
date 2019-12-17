<?php

/**
 * This file is part of the Framework project
 * Copyright 2019 - Core team
 * Authors :
 *  - PIVETEAU Anatole<anatole.piveteau@gmail.com>
 *  - GAZAUBE François<>
 */

/**
 * Base environment vars
 */
define("PATH_CORE", __DIR__ . DIRECTORY_SEPARATOR . "Core");
define("PATH_SITE", __DIR__ . DIRECTORY_SEPARATOR . "Site");
define("PATH_ROOT", __DIR__ . DIRECTORY_SEPARATOR);
define("PATH_LOG", __DIR__ . DIRECTORY_SEPARATOR . "Log");
define("PATH_CACHE", __DIR__ . DIRECTORY_SEPARATOR . "Cache");

/**
 * Including loader
 */
include_once __DIR__ . "/Core/Loader/Loader.php";
use Core\Loader;

/**
 * Including classes sorting by constraints
 */
Loader::explore(PATH_CORE, "Interface");
Loader::explore(PATH_CORE, "", "Interface");

/**
 * Initialize kernel
 */
\Core\Kernel::boot();

echo "<br>Exec. Time: " . \Core\Kernel::getEnvironment()->getExecutionTime() . "ms<br>";