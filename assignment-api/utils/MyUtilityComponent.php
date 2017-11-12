<?php

/*
 * @description     This component is used for utility functions
 * @package         Component
 * @category        Component
 * @class           MyUtilityComponent
 * @dependencies    Component
 * @author          Diwakar
 */

class MyUtilityComponent {

    /**
     * @method errorHandler
     * @Handle error request
     * @return error response
     * @Created: 10-11--2017
     */
    public function errorHandler($errorCode = '05', $type = 'json', $errorMesg = '') {
        //set message
        switch ($errorCode) {
            case '01':
                $errorMsg = 'Error Try Again!';
                break;
            case '11':
                //Handle error for no data found
                $errorMsg = 'No Data Found.';
                break;
            case '19':
                //Handle error for no data found
                $errorMsg = 'limit value excedded';
                break;
            default:
                $errorMsg = $errorMesg;
                break;
        }

        if ($type == 'json') {
            $errorArr = array();
            $errorArr['status'] = 0;
            $errorArr['error_code'] = $errorCode;
            $errorArr['error_message'] = $errorMsg;

            echo json_encode($errorArr);
        }
        exit;
    }

    /**
     * @method errorHandler
     * @Handle error request
     * @return error response
     * @Created:10-11-2017
     */
    public function catchErrorHandler($errorCode, $type = 'json', $errorMsg = '') {
        global $const;
        if ($type == 'json') {
            $errorArr = array();
            $errorArr['status'] = $const->data->FAIL_STATUS;
            $errorArr['error_code'] = $errorCode;
            $errorArr['error_message'] = $errorMsg;
            $errorArr['result'] = null;
            echo json_encode($errorArr);
        }
        exit;
    }

    /**
     * @method makeResponse
     * @Handle response
     * @return response array
     * @author Diwakar
     * @Created: 22-11-2016
     */
    public function makeResponse($status, $data = null) {
        global $const;
        $resp = array(
            'status' => $status,
            'error_code' => 0,
            'error_message' => null,
            'result' => $data
        );
        echo json_encode($resp);
        exit;
    }

}

?>
