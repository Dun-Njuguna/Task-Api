<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/DbOperations.php';
$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

$app->post('/starttask', function(Request $request, Response $response){
    if(!haveEmptyParameters(array('program_time', 'event', 'message', 'actual_time', 'display_message'), $request, $response)){
        $request_data = $request->getParsedBody();
        $program_time = $request_data['program_time'];
        $event = $request_data['event'];
        $message = $request_data['message'];
        $actual_time = $request_data['actual_time'];
        $display_message = $request_data['display_message'];
        $db = new DbOperations;
        $result = $db->startTask($program_time, $event, $message, $actual_time, $display_message);

        if($result == TASK_STARTED){
            $message = array();
            $message['error'] = false;
            $message['message'] = 'Servers started successfully';
            $message['color'] = "-65536";
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(201);
        }else if($result == TASK_FAILURE){
            $message = array();
            $message['error'] = true;
            $message['message'] = 'An error occurred';
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(422);
        }
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);
});

$app->post('/stoptask', function(Request $request, Response $response){
    if(!haveEmptyParameters(array('program_time', 'event', 'message', 'actual_time', 'display_message'), $request, $response)){
        $request_data = $request->getParsedBody();
        $program_time = $request_data['program_time'];
        $event = $request_data['event'];
        $message = $request_data['message'];
        $actual_time = $request_data['actual_time'];
        $display_message = $request_data['display_message'];
        $db = new DbOperations;
        $result = $db->stopTask($program_time, $event, $message, $actual_time, $display_message);

        if($result == TASK_STOPED){
            $message = array();
            $message['error'] = false;
            $message['message'] = 'Servers stoped successfully';
            $message['color'] = "-1";
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(201);
        }else if($result == TASK_FAILURE){
            $message = array();
            $message['error'] = true;
            $message['message'] = 'An error occurred';
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(422);
        }
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);
});

$app->post('/report', function(Request $request, Response $response){
    if(!haveEmptyParameters(array('program_time', 'event', 'message', 'actual_time', 'display_message'), $request, $response)){
        $request_data = $request->getParsedBody();
        $program_time = $request_data['program_time'];
        $event = $request_data['event'];
        $message = $request_data['message'];
        $actual_time = $request_data['actual_time'];
        $display_message = $request_data['display_message'];
        $db = new DbOperations;
        $result = $db->reportTask($program_time, $event, $message, $actual_time, $display_message);

        if($result == REPORT_SUCCESSFUL){
            $message = array();
            $message['error'] = false;
            $message['message'] = 'Report successfully';
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(201);
        }else if($result == TASK_FAILURE){
            $message = array();
            $message['error'] = true;
            $message['message'] = 'An error occurred';
            $response->write(json_encode($message));
            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(422);
        }
    }
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);
});

$app->get('/allTasks', function(Request $request, Response $response){
    $db = new DbOperations;
    $tasks = $db->getAllTasks();
    $response_data = array();
    $response_data['error'] = false;
    $response_data['tasks'] = $tasks;
    $response->write(json_encode($response_data));
    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});

function haveEmptyParameters($required_params, $request, $response){
    $error = false;
    $error_params = '';
    $request_params = $request->getParsedBody();
    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true;
            $error_params .= $param . ', ';
        }
    }
    if($error){
        $error_detail = array();
        $error_detail['error'] = true;
        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error;
}

$app->run();
