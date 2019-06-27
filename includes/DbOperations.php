<?php

  class DbOperations{
        //database connection variable
        private $con;

        //inside constructor, getting the connection link
        function __construct(){
            require_once dirname(__FILE__) . '/DbConnect.php';
            $db = new DbConnect;
            $this->con = $db->connect();
        }

          /*
          start a new task
          */
      public function startTask($program_time, $event, $message, $actual_time, $display_message){

        $stmt = $this->con->prepare("INSERT INTO servers (program_time, event, message, actual_time, display_message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $program_time, $event, $message, $actual_time, $display_message);
        if($stmt->execute()){
            return TASK_STARTED;
        }else{
            return TASK_FAILURE;
        }

      }

          /*
          stop a task
          */
      public function stopTask($program_time, $event, $message, $actual_time, $display_message){

        $stmt = $this->con->prepare("INSERT INTO servers (program_time, event, message, actual_time, display_message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $program_time, $event, $message, $actual_time, $display_message);
        if($stmt->execute()){
            return TASK_STOPED;
        }else{
            return TASK_FAILURE;
        }

      }


          /*
          report running tasks
          */
      public function reportTask($program_time, $event, $message, $actual_time, $display_message){

        $stmt = $this->con->prepare("INSERT INTO servers (program_time, event, message, actual_time, display_message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $program_time, $event, $message, $actual_time, $display_message);
        if($stmt->execute()){
            return REPORT_SUCCESSFUL;
        }else{
            return TASK_FAILURE;
        }

      }

      /*
      will delete the tasks from database
      */
      public function deleteTask($id){
          $stmt = $this->con->prepare("DELETE FROM servers WHERE id = ?");
          $stmt->bind_param("i", $id);
          if($stmt->execute())
              return true;
          return false;
      }


      /*
      returning all the tasks from database
      */
      public function getAllTasks(){
          $stmt = $this->con->prepare("SELECT id, program_time, event, message, actual_time, display_message FROM servers;");
          $stmt->execute();
          $stmt->bind_result($id, $program_time, $event, $message, $actual_time, $display_message);
          $tasks = array();
          while($stmt->fetch()){
              $task = array();
              $task['id'] = $id;
              $task['program_time'] = $program_time;
              $task['event']=$event;
              $task['message'] = $message;
              $task['actual_time'] = $actual_time;
              $task['display_message'] = $display_message;
              array_push($tasks, $task);
          }
          return $tasks;
      }

    }
