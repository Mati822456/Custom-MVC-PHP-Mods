<?php

namespace App;

class Router{
    
    public String $path;
    public Array $query_string; 
    public String $method;

    public function __construct()
    {
        // Assigns the current request URI
        $this->path = $_SERVER['REQUEST_URI']; 

        // Assigns the current request method
        $this->method = isset($_REQUEST['_method']) ? $_REQUEST['_method'] : $_SERVER['REQUEST_METHOD'];

        // Initializes an empty array
        $this->query_string = [];

        // If the query string is present in the current request, it is parsed and assigned to the $this->query_string property as an array.
        if(isset($_SERVER['QUERY_STRING'])){
            parse_str($_SERVER['QUERY_STRING'], $this->query_string);
        }
    }
    public function get(String $path, String $controller, String $function, ?Array $variables = null)
    {
        // Create a session route with controller, function and variables for the given path.
        $_SESSION['routes'][$path] = ['method' => 'GET', 'controller' => $controller, 'function' => $function, 'variables' => $variables];
    }
    public function post(String $path, String $controller, String $function, ?Array $variables = null)
    {
        // Create a session route with controller, function and variables for the given path.
        $_SESSION['routes'][$path] = ['method' => 'POST', 'controller' => $controller, 'function' => $function, 'variables' => $variables];
    }
    public function put(String $path, String $controller, String $function, ?Array $variables = null)
    {
        // Create a session route with controller, function and variables for the given path.
        $_SESSION['routes'][$path] = ['method' => 'PUT', 'controller' => $controller, 'function' => $function, 'variables' => $variables];
    }
    public function patch(String $path, String $controller, String $function, ?Array $variables = null)
    {
        // Create a session route with controller, function and variables for the given path.
        $_SESSION['routes'][$path] = ['method' => 'PATCH', 'controller' => $controller, 'function' => $function, 'variables' => $variables];
    }
    public function delete(String $path, String $controller, String $function, ?Array $variables = null)
    {
        // Create a session route with controller, function and variables for the given path.
        $_SESSION['routes'][$path] = ['method' => 'DELETE', 'controller' => $controller, 'function' => $function, 'variables' => $variables];
    }
        
    /**
     * Render View with variables
     *
     * @param  String $filename
     * @param  Array $variables
     * @param  Int $status
     * @return void
     */
    public function render(String $filename, Array $variables = null, Int $status = 200)
    {
        $output = NULL;

        // Build the file path based on the filename passed as argument and the path to the views directory
        $filePath = './views//' . $filename . '.php';

        // Check if the file exists
        if(file_exists($filePath)){

            // If the $variables argument is set, extract the variables so that they can be used in the included file
            if(isset($variables)){
                extract($variables);
            }

            // Start output buffering
            ob_start();

            // Include the file specified by the file path
            include $filePath;

            // Get the contents of the output buffer and store them in the $output variable
            $output = ob_get_clean();
        }
        // Output the contents of the $output variable
        echo $output;
        $this->setStatus($status);
        exit;
    }

    public function redirect(String $route)
    {
        // Sends an HTTP header to redirect the browser to a new URL specified in $route using HTTP status code 302.
        header('Location: '. $route, true, 302);
        exit;
    }

    public function run()
    {
        // Get the path component of the current URL
        $path = parse_url($this->path)['path'];

        // Check if the path is defined in the registered routes
        if(array_key_exists($path, $_SESSION['routes'])){
            // Check if the HTTP method used for the request matches the one defined for the registered path in the session routes
            if($this->method == $_SESSION['routes'][$path]['method']){
                header("Access-Control-Allow-Methods: ". $_SESSION['routes'][$path]['method']);

                // Get the controller name for the requested route
                $controllerName = $_SESSION['routes'][$path]['controller'];

                // Get the function name for the requested route
                $function = $_SESSION['routes'][$path]['function'];
                
                // Get the variables passed to the requested route
                $variables = $_SESSION['routes'][$path]['variables'];
                
                // Build the path to the controller file
                $filename = './App/Controllers//' . $controllerName;
                
                // Check if the controller file exists
                if(file_exists($filename . '.php')){
                    // Include the controller file
                    include_once $filename . '.php';

                    // Build the class name for the controller
                    $className = 'App\\Controllers\\' . $controllerName;

                    // Instantiate the controller class
                    $controller = new $className;

                    // Call the function of the instantiated controller class with variables
                    $controller->{$function}($variables);
                }
                // Return true indicating that the requested route was found and processed
                return true;
            }else{
                $this->render('response', ['code' => 405], 405);
            }
        }
        // Render the 404 page if the requested route was not found
        $this->render('response', ['code' => 404], 404);
    }
    public function setStatus(Int $status)
    {
        // Set the HTTP response status code.
        http_response_code($status);
    }
}
