<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Headers : content-type");

// including all the required classes
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Manager as ModelsManager;

use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;



// loading the model directory
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(__DIR__ . '/models/', __DIR__ . '/utils/',))->register();

//include __DIR__ . "/../ModelsTraits.php";
// creat object of config file
$config = new ConfigIni(__DIR__ . '/config/config.ini');

//create object of constants ini
$const = new ConfigIni(__DIR__ . '/config/constants.ini');
// setting dependency injector
$di = new FactoryDefault();
// set ini variable with config object
$di->set('config', $config);
// set ini variable with const object
$di->set('const', $const);
$di->set('utils', function () {
    return new Utils();
}
);
//Setup a base URI so that all generated URIs include the "tutorial" folder

// Set up the database service
$di->set('db', function() use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

$di->set('modelsManager', function(){
      return new Phalcon\Mvc\Model\Manager();
  });

// creating the new micro app
$app = new Micro($di);

$origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';
$app->response->setHeader("Access-Control-Allow-Origin", $origin)
      ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
      ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
      ->setHeader("Access-Control-Allow-Credentials", true);
// -------------------- COMMON API START --------------------
/**
 * @api This API is to get user detail by Id.
 *
 * @param integer $id  of user
 *
 * @return json return status, errorCode, errorMessage and data
 * @author Diwakar (diwakar.upadhyay@kelltontech.com)
 */
$app->get('/getAllPropertyOwner?{q:[a-z]*}', function () use ($app) {
    $query=$app->request->getQuery('q');
    $owner = new owners();
    $response = $owner->getAllPropertyOwner($query);
    echo json_encode($response);
}
);

$app->post('/ownerupdate', function () use ($app) {
    $owner = new owners();
    $response = $owner->addUpdateOwner();
}
);

$app->get('/getownerbyid/{ownerId:[0-9]+}', function ($ownerId) use ($app) {
    $owner = new owners();
    $response = $owner->getOwnerByOwnerId($ownerId);
}
);

$app->post('/updateowner', function () use ($app) {
    $bodyData = $app->request->getJsonRawBody();
    $owner = new owners();
    $response = $owner->updateOwnerById();
   }
);

//booking
$app->get('/getAllBookings?{q:[a-z]*}', function () use ($app) {
    $query='';
     $query=$app->request->getQuery('q');
    $booking = new bookings();
    $response = $booking->getAllBookings($query);
    echo json_encode($response);
}
);

$app->post('/addUBooking', function () use ($app) {
    $owner = new bookings();
    $response = $owner->addUBooking();
}
);


$app->get('/getAllOwners', function () use ($app) {
    $owner = new owners();
    $response = $owner->getAllOwners();
    echo json_encode($response);
}
);


$app->get('/getbookingbyid/{bookingId:[0-9]+}', function ($bookingId) use ($app) {

    $booking = new bookings();
    $response = $booking->getBookingByBookingId($bookingId);
   }
);

$app->post('/updatebooking', function () use ($app) {
    $bodyData = $app->request->getJsonRawBody();
    $owner = new bookings();
    $response = $owner->updateBookingById();
   }
);

/**
 * @api This API is to delete booking by id.
 *
 * @return json return status, errorCode, errorMessage and data
 * @author Diwakar (diwakar.upadhyay@kelltontech.com)
 */
$app->get('/deletebooking/{bookingid:[0-9]+}', function ($bookingid) use ($app) {
    $booking = new bookings();
    $response = $booking->deleteBookingByBookingId($bookingid);
    //echo json_encode($response);
}
);

/**
 * @api This API is to delete booking by id.
 *
 * @return json return status, errorCode, errorMessage and data
 * @author Diwakar (diwakar.upadhyay@kelltontech.com)
 */
$app->get('/deleteowner/{ownerid:[0-9]+}', function ($ownerid) use ($app) {
    $owners = new owners();
    $response = $owners->deleteOwnerByOwnerId($ownerid);
    //echo json_encode($response);
}
);

$app->notFound(
        function () use ( $app ) {
    $app->response->setStatusCode(404, "kuch galat hai")->sendHeaders();
    echo 'Page was not found!';
}
);

$app->handle();
