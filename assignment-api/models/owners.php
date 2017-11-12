<?php

use Phalcon\Mvc\Model;
use Phalcon\Http\Request;

/**
 * @SWG\Definition(@SWG\Xml(name="owners"))
 */
class owners extends Model {

    public function getSource() {
        return "owner";
    }

    public $po_id;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $po_name;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $po_email;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $po_phone;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $po_description;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $po_booking_limit;

    /**
     * @SWG\Get(
     *     path="/assignment-api/getAllPropertyOwner",
     *     summary="get all property owner",
     *     tags={"owner"},
     *     description="get all property owner",
     *     operationId="getAllPropertyOwner",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid tag value",
     *     )
     * )
     */
    public function getAllPropertyOwner($query) {
        global $const, $db;
        $data = array();
        try {
             $status = 1;
             $errorCode = 0;
             $errorMessage = "";
             $finalResult=array();
             $allOwners = owners::find(array(
                            "conditions" =>  'po_name' . " LIKE '" . $query . "%'"
                        ));
            if (!empty($allOwners)) {
                $data=array();
                $i=0;
                foreach($allOwners  as $allOwnerData){
                  $ownerData = json_decode(json_encode($allOwnerData), true);
                     $data[$i]['po_id']=$ownerData['po_id'];
                     $data[$i]['po_name']=$ownerData['po_name'];
                     $data[$i]['po_email']=$ownerData['po_email'];
                     $data[$i]['po_booking_limit']=$ownerData['po_phone'];
                     $data[$i]['po_description']=$ownerData['po_description'];
                     $i++;
                }
                $data =  $data;
            } else {
                $utils->errorHandler('11');
            }
        } catch (Exception $e) {
            //set the status by using const object variable which is global
            $status = 0;
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
        }
        // setting the return array
        $finalResult['status'] = $status;
        $finalResult['error_code'] = $errorCode;
        $finalResult['errormessage'] = $errorMessage;
        $finalResult['result']['data'] = $data;
        // returning the json data
        return $finalResult;
    }

    /**
 * @SWG\Post(path="/assignment-api/addUpdateOwner",
 *     tags={"owner"},
 *     summary="add a new owner",
 *     description="add a new owner",
 *     operationId="addUpdateOwner",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="body",
 *         in="body",
 *         description="Add a new owner",
 *         required=false,
 *         @SWG\Schema(ref="#/definitions"),
 *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="successful operation"
 *   ),
 *   @SWG\Response(response=400,  description="Invalid Data")
 * )
 */
public function addUpdateOwner() {
    global $const;
    $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');
    $status = 01;
    $request = new \Phalcon\Http\Request();
    $jsonData = $request->getJsonRawBody();
    $jsonArr = (array) $jsonData;
    $allowParam = array('po_name', 'po_email');
    try {
            $resultData = json_decode(json_encode($jsonArr), true);
            if (isset($jsonData->po_booking_limit)) {
                $owners = new owners();
                if($jsonData->po_booking_limit > 1000){
                  $status = 1;
                  $errorCode = 1;
                  $errorMessage = 'limit value is not exceeded';
                  $data = "";
                  $utils->errorHandler(19);

                }
                $addNewOwner = $owners->create(array(
                    "po_name" => $jsonData->po_name,
                    "po_email" => $jsonData->po_email,
                    "po_phone" => $jsonData->po_phone,
                    "po_booking_limit" => $jsonData->po_booking_limit,
                    "po_description" => $jsonData->po_description
                  )
                );
                $status = 02;
                if ($addNewOwner == true) {
                    $message = $const->data->REC_SUCCESS_ADD;
                    $status = $const->data->SUCCESS_STATUS;
                    $data["data"]['message'] = "Created Successfully.";
                    $utils->makeResponse($status, $data);
                }
            }
         } catch (Exception $ex) {
          $message = $ex->getMessage();
          $status = 0;
          $utils->catchErrorHandler($ex->getCode(), 'json', $ex->getMessage());
         }
     }
     /**
     * @SWG\Get(
     *     path="/assignment-api/getOwnerByOwnerId/{ownerId}",
     *     summary="get owner by owner id",
     *     tags={"owner"},
     *     description="get owner by owner id",
     *     operationId="getOwnerByOwnerId",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *   @SWG\Parameter(
     *     name="ownerId",
     *     in="path",
     *     description="The owner detail that needs to be fetched.",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @SWG\Response(response=400,  description="Wrong Parameter")
     * )
     */
    public function getOwnerByOwnerId($ownerId) {
        global $const;
        //Requests
        $request = new \Phalcon\Http\Request();
        $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');
        $jsonData = $request->getJsonRawBody();
        $jsonArr = (array) $jsonData;
        $status = 04;
        $data = [];
        try {
            $ownerId = (int) $ownerId;
            $owners = owners::findFirst(array(
                        'columns' => '*',
                        'conditions' => 'po_id = ?1',
                        'bind' => array(
                            1 => $ownerId
                        )
                      ));
            $ownerData = json_decode(json_encode($owners), true);
            if (isset($ownerData['po_id']) && !empty($ownerData['po_id'])) {
                $message = $const->message->REC_FOUND;
                $status = $const->status->SUCCESS_STATUS;
                $data['data'] = $ownerData;
                $utils->makeResponse($status, $data);
            }
            $utils->makeErrorResponse($status);
        } catch (Exception $ex) {
            $utils->catchErrorHandler($ex->getCode(), 'json', $ex->getMessage());
        }
    }
    /**
      * @SWG\Post(
      *     path="/assignment-api/updateOwnerById/{ownerId}",
      *     tags={"Update Owner Record by owner id"},
      *     operationId="updateOwnerById",
      *     summary="Update Owner Record by owner id",
      *     description="",
      *     consumes={"application/json"},
      *     produces={"application/json"},
        *   @SWG\Parameter(
      *     name="ownerId",
      *     in="path",
      *     description="owner detail update",
      *     required=true,
      *     type="integer"
      *   ),
      *     @SWG\Parameter(
      *         name="body",
      *         in="body",
      *         description="owner detail update",
      *         required=true
      *     ),
      *     @SWG\Response(
      *         response=400,
      *         description="Invalid ID supplied",
      *     ),
      *     @SWG\Response(
      *         response=404,
      *         description="owner id not found",
      *     ),
      *     @SWG\Response(
      *         response=405,
      *         description="Validation exception",
      *     )
      * )
      */
      public function updateOwnerById() {
        global $const;

        try {
        //Requests
        $request = new \Phalcon\Http\Request();
        $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');
        $jsonData = $request->getJsonRawBody();
        $jsonArr = (array) $jsonData;
        $bodyData=$jsonData;
        $status = 04;
        $data = [];
        if (!empty($bodyData)) {
               $result = owners::findFirst(array(
                           'columns' => '*',
                           'conditions' => 'po_id = ?1',
                           'bind' => array(
                               1 => $bodyData->po_id
                           )
               ));
          $ownerData = json_decode(json_encode($result), true);
          $result->po_id = $bodyData->po_id;
          $result->po_name = isset($bodyData->po_name) ? $bodyData->po_name : $ownerData['po_name'];
          $result->po_email = isset($bodyData->po_email) ? $bodyData->po_email : $ownerData['po_email'];
          $result->po_phone = isset($bodyData->po_phone) ? $bodyData->po_phone : $ownerData['po_phone'];
          $result->po_booking_limit = isset($bodyData->po_booking_limit) ? $bodyData->po_booking_limit : $ownerData['po_booking_limit'];
          $result->po_description = isset($bodyData->po_description) ? $bodyData->po_description : $ownerData['po_description'];

          if($result->po_booking_limit > 1000){
            $status = 1;
            $errorCode = 1;
            $errorMessage = 'limit value is not exceeded';
            $data = "";
            $utils->errorHandler(19);

          }
          if ($result->update() === false) {
              $errors = [];
              foreach ($result->getMessages() as $message) {
                  $errors[] = $message->getMessage();
              }
              $status = 0;
              $errorCode = 1;
              $errorMessage = $errors;
              $data = "";
              $utils->errorHandler(04);
          }else{
              $message = $const->data->REC_SUCCESS_UPDATE;
              $status = 1;//$const->data->SUCCESS_STATUS;
              $data['data'] = "updated sucessfully";
              $utils->makeResponse($status, $data);
          }
        }
      } catch (Exception $ex) {
          $utils->catchErrorHandler($ex->getCode(), 'json', $ex->getMessage());
      }
  }

  //get All owners

  public function getAllOwners() {
      global $const, $db;
      $data = array();
      try {
           $status = 1;
           $errorCode = 0;
           $errorMessage = "";
           $finalResult=array();
           $allowners= owners::find();
          if (!empty($allowners)) {
              $data=array();
              $i=0;
              foreach($allowners  as $allOwnersData){
                $allOwnersData = json_decode(json_encode($allOwnersData), true);
                   $data[$i]['po_id']=$allOwnersData['po_id'];
                   $data[$i]['po_name']=$allOwnersData['po_name'];
                   $i++;
              }

              $data =  $data;
          } else {
              $utils->errorHandler('11');
          }
      } catch (Exception $e) {
          //set the status by using const object variable which is global
          $status = 0;
          $errorCode = $e->getCode();
          $errorMessage = $e->getMessage();
      }
      $finalResult['result'] = $data;
      // returning the json data
      return $finalResult;
  }


  /**
     * @SWG\Delete(path="/assignment-api/deleteBookingById/{id}",
     *   tags={"user"},
     *   summary="Delete user",
     *   description="This can only be done by the logged in user.",
     *   operationId="deleteUser",
     *   produces={"application/xml", "application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="The name that needs to be deleted",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=400, description="Invalid user id supplied"),
     *   @SWG\Response(response=404, description="User not found")
     * )
     */
    public function deleteOwnerByOwnerId($ownerId) {
        //$utils = \Phalcon\Di::getDefault()->get('utils');
        global $const, $db;
        $request = new \Phalcon\Http\Request();
        $data = array();
        try {
            $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');

            $ownerData = owners::findFirst(
                            "po_id = '$ownerId'"
            );
                if ($ownerData->delete() === false) {
                    $status = 0;
                    $errorCode = 1;
                    $errorMessage = "Unable to Deleted Record";
                } else {
                    $status = 1;
                    $errorCode = 0;
                    $errorMessage = "";
                    $data = "Record Deleted Sucessfully";
                }

        } catch (Exception $e) {

            //set the status by using const object variable which is global
            $status = 0;
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
        }
        // setting the return array
        $ret['status'] = $status;
        $ret['error_code'] = $errorCode;
        $ret['error_message'] = $errorMessage;
        $ret['data'] = $data;
        // returning the json data
        echo json_encode($ret);
    }



}
