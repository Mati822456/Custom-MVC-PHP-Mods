<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./public/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>Error</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            width: 100vw;
            height: 100vh;
            background: url('../public/images/response_background.svg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1, h2{
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
        }
        h1{
            font-family: 'Anton', sans-serif;
            font-size: 8em;
            padding: 10px;
            margin-bottom: 20px;
        }
        h2{
            font-family: 'Anton', sans-serif;
            font-size: 7em;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1><?php echo $code ?></h1>
    <?php 
    // https://gist.github.com/danmatthews/1379769
    function _statustext($code = 0) {
        
        // List of HTTP status codes.
        $statuslist = array(
            '100' => 'Continue',
            '101' => 'Switching Protocols',
            '200' => 'OK',
            '201' => 'Created',
            '202' => 'Accepted',
            '203' => 'Non-Authoritative Information',
            '204' => 'No Content',
            '205' => 'Reset Content',
            '206' => 'Partial Content',
            '300' => 'Multiple Choices',
            '302' => 'Found',
            '303' => 'See Other',
            '304' => 'Not Modified',
            '305' => 'Use Proxy',
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '407' => 'Proxy Authentication Required',
            '408' => 'Request Timeout',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '414' => 'Request-URI Too Long',
            '415' => 'Unsupported Media Type',
            '416' => 'Requested Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Unavailable',
            '504' => 'Gateway Timeout',
            '505' => 'HTTP Version Not Supported'
        );

        // Caste the status code to a string.
        $code = (string)$code;

        // Determine if it exists in the array.
        if(array_key_exists($code, $statuslist) ) {
        
            // Return the status text
            return $statuslist[$code];
        
        } else {
            
            // If it doesn't exists, degrade by returning the code.
            return $code;
        
        }

    } 
    ?>
    <h2><?php echo _statustext($code) ?></h2>
</body>
</html>