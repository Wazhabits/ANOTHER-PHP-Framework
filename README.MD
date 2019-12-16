**Framework Project**

* Stack 
    
    
    1 : Index
    
    2 : Kernel
    
    
* Norm


    1 : Upper Camel Case for classes
    2 : Lower Camel Case
    3 : Interface : Core\Functionnality\InterfaceName
        Class : Core\Functionnality
        
* Documentation


    0 : Kernel -
        static class
        static function getEnvironment() | Return Env instance

    1 : Loader -
        static class
        static function explore($path, $needle, $constraint) | This function include all PHP files
        
    2 : Env -
        Access from kernel
        public function get($key) | Return the associated value in configuration array or the all array
        
    3 : Files -
        static class
        static function read($path) | Return the content of file given
        static function put($path, $content) | Put at the end of file the $content given
        static function delete($path) | Delete the given file
        static function create($path, $content = null) | Create the file and add content if specified
        static function test($path) | Test if a $path exist, create the missing folder if there are