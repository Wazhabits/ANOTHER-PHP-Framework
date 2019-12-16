<?php

/**
 * This file is part of the Framework project
 * Copyright 2019 - Core team
 */

include_once __DIR__ . "/Core/Loader/Loader.php";

use Core\Loader;

define("ROOT_DIRECTORY", getcwd() . DIRECTORY_SEPARATOR);

/**
 * Including classes sorting by constraints
 */
Loader::explore(__DIR__, "Interface");
Loader::explore(__DIR__, "", "Interface");

/**
 * Initialize kernel
 */
\Core\Kernel::initialize();