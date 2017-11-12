<?php

use Phalcon\Mvc\Model;
use Phalcon\Http\Request;

/**
 * @SWG\Definition(@SWG\Xml(name="owners"))
 */
class bookings extends Model {

    public function getSource() {
        return "booking";
    }

    public $bo_id;
    /**
     * @SWG\Property(format="string")
     * @var string
     */

     public $po_id;
     /**
      * @SWG\Property(format="string")
      * @var string
      */

    public $bo_name;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $bo_email;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $bo_phone;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $bo_description;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $bo_amount;

    /**
     * @SWG\Get(
     *     path="/assignment-api/getAllBookings",
     *     summary="get all bookings",
     *     tags={"booking"},
     *     description="get all bookings",
     *     operationId="getAllBooking",
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
    public function getAllBookings($query=null) {
        global $const, $db;
        $data = array();
        try {
             $status = 1;
             $errorCode = 0;
             $errorMessage = "";
             $finalResult=array();
             $conditions='';
             $allBookings= bookings::find(array(
                            "conditions" =>  'bo_name' . " LIKE '" . $query . "%'"
                        ));
            if (!empty($allBookings)) {
                $data=array();
                $i=0;
                foreach($allBookings  as $allBookingsData){
                  $bookingData = json_decode(json_encode($allBookingsData), true);
                  //print_r($bookingData);die;
                     $data[$i]['bo_id']=$bookingData['bo_id'];
                     $data[$i]['po_id']=$bookingData['po_id'];
                     $data[$i]['bo_name']=$bookingData['bo_name'];
                     $data[$i]['bo_email']=$bookingData['bo_email'];
                     $data[$i]['bo_phone']=$bookingData['bo_phone'];
                     $data[$i]['bo_description']=$bookingData['bo_description'];
                     $data[$i]['bo_amount']=$bookingData['bo_amount'];
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
            if (isset($resultData['po_name'])) {
                $owners = new owners();
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
      *         description="USer id not found",
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



  /**
* @SWG\Post(path="/assignment-api/addUBooking",
*     tags={"booking"},
*     summary="add a new Booking",
*     description="add a new Booking",
*     operationId="addUBooking",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="body",
*         in="body",
*         description="Add a new booking",
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
public function addUBooking() {
  global $const;
  $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');
  $status = 01;
  $request = new \Phalcon\Http\Request();
  $jsonData = $request->getJsonRawBody();
  $jsonArr = (array) $jsonData;
  $allowParam = array('bo_name', 'bo_email');
  try {
          $resultData = json_decode(json_encode($jsonArr), true);
          if (isset($resultData['bo_name'])) {
              $owners = new bookings();

              if(isset($jsonData->po_id) && isset($jsonData->bo_amount)){
                $isvalid=0;
                $ownerLimit = owners::findFirst(array(
                            'columns' => '*',
                            'conditions' => 'po_id = ?1',
                            'bind' => array(
                                1 => $jsonData->po_id
                            )
                      ));
                 $ownerLimit = json_decode(json_encode($ownerLimit), true);
                 if($jsonData->bo_amount >= $ownerLimit['po_booking_limit']){
                       $status = 0;
                       $errorCode = 1;
                       $errorMessage = "Please try less amount";
                       $data = "";
                       $utils->errorHandler(19);
                     }
              }

              $addNewOwner = $owners->create(array(
                  "po_id" => $jsonData->po_id,
                  "bo_name" => $jsonData->bo_name,
                  "bo_email" => $jsonData->bo_email,
                  "bo_phone" => $jsonData->bo_phone,
                  "bo_description" => $jsonData->bo_description,
                  "bo_amount" => $jsonData->bo_amount
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
     * @SWG\Post(
     *     path="/assignment-api/updateBookingById/{bookingId}",
     *     tags={"Update Booking Record by booking id"},
     *     operationId="updateOBookingById",
     *     summary="Update Booking Record by booking id",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
       *   @SWG\Parameter(
     *     name="bookingId",
     *     in="path",
     *     description="booking update",
     *     required=true,
     *     type="integer"
     *   ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="booking detail update",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="booking id not found",
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Validation exception",
     *     )
     * )
     */
     public function updateBookingById() {
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
              $result = bookings::findFirst(array(
                          'columns' => '*',
                          'conditions' => 'bo_id = ?1',
                          'bind' => array(
                              1 => $bodyData->bo_id
                          )
              ));
         $ownerData = json_decode(json_encode($result), true);
         $result->bo_id = $bodyData->bo_id;
         $result->po_id = isset($bodyData->po_id) ? $bodyData->po_id : $ownerData['po_id'];
         $result->bo_name = isset($bodyData->bo_name) ? $bodyData->bo_name : $ownerData['bo_name'];
         $result->bo_email = isset($bodyData->bo_email) ? $bodyData->bo_email : $ownerData['bo_email'];
         $result->bo_phone = isset($bodyData->bo_phone) ? $bodyData->bo_phone : $ownerData['bo_phone'];
         $result->bo_description = isset($bodyData->bo_description) ? $bodyData->bo_description : $ownerData['bo_description'];
         $result->bo_amount = isset($bodyData->bo_amount) ? $bodyData->bo_amount : $ownerData['bo_amount'];


          if(isset($result->po_id) && isset($result->bo_amount)){
            $isvalid=0;
            $ownerLimit = owners::findFirst(array(
                        'columns' => '*',
                        'conditions' => 'po_id = ?1',
                        'bind' => array(
                            1 => $result->po_id
                        )
                       ));
             $ownerLimit = json_decode(json_encode($ownerLimit), true);
             if($result->bo_amount >= $ownerLimit['po_booking_limit']){
                   $status = 0;
                   $errorCode = 1;
                   $errorMessage = "Please try less amount";
                   $data = "";
                   $utils->errorHandler(19);
                 }

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
  public function getBookingByBookingId($bookingId) {
      global $const;
      //Requests
      $request = new \Phalcon\Http\Request();
      $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');
      $jsonData = $request->getJsonRawBody();
      $jsonArr = (array) $jsonData;
      $status = 04;
      $data = [];
      try {
          $bookingId = (int) $bookingId;
          $bookings = bookings::findFirst(array(
                      'columns' => '*',
                      'conditions' => 'bo_id = ?1',
                      'bind' => array(
                          1 => $bookingId
                      )
                    ));
          $bookingData = json_decode(json_encode($bookings), true);
          if (isset($bookingData['bo_id']) && !empty($bookingData['bo_id'])) {
              $message = $const->message->REC_FOUND;
              $status = $const->status->SUCCESS_STATUS;
              $data['data'] = $bookingData;
              $utils->makeResponse($status, $data);
          }
          $utils->makeErrorResponse($status);
      } catch (Exception $ex) {
          $utils->catchErrorHandler($ex->getCode(), 'json', $ex->getMessage());
      }
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
    public function deleteBookingByBookingId($bookingId) {

        //$utils = \Phalcon\Di::getDefault()->get('utils');
        global $const, $db;
        $request = new \Phalcon\Http\Request();
        $data = array();
        try {
            $utils = \Phalcon\Di::getDefault()->get('MyUtilityComponent');

            $bookData = bookings::findFirst(
                            "bo_id = '$bookingId'"
            );
                if ($bookData->delete() === false) {
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
