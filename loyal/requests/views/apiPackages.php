<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * @OA\Get(
 *   path="/requests/index.php?a=Packages&endpoint=list",
 *   summary="List Available Packages",
 *   description="Get a list of available packages that can be purchased for events",
 *   tags={"Packages"},
 *   @OA\Parameter(
 *     name="endpoint",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     example="list"
 *   ),
 *   @OA\Parameter(
 *     name="language",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"en", "ar"}),
 *     example="en",
 *     description="Language preference (en or ar)"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="List of packages",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="packages", type="array", @OA\Items(
 *           type="object",
 *           @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="title", type="string", example="Basic Package"),
 *           @OA\Property(property="details", type="string", example="<p>Up to 50 invitees</p><p>Basic design</p>"),
 *           @OA\Property(property="attendees", type="integer", example=50),
 *           @OA\Property(property="price", type="number", example=99.99),
 *           @OA\Property(property="discount", type="number", example=0.00),
 *           @OA\Property(property="finalPrice", type="number", example=99.99),
 *           @OA\Property(property="rank", type="integer", example=1)
 *         ))
 *       )
 *     )
 *   )
 * )
 
 * @OA\Get(
 *   path="/requests/index.php?a=Packages&endpoint=details",
 *   summary="Get Package Details",
 *   description="Get details of a specific package by ID",
 *   tags={"Packages"},
 *   @OA\Parameter(
 *     name="endpoint",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     example="details"
 *   ),
 *   @OA\Parameter(
 *     name="id",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="integer"),
 *     example="1",
 *     description="Package ID"
 *   ),
 *   @OA\Parameter(
 *     name="language",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"en", "ar"}),
 *     example="en",
 *     description="Language preference (en or ar)"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Package details",
 *     @OA\JsonContent(
 *       @OA\Property(property="ok", type="boolean", example=true),
 *       @OA\Property(property="data", type="object", 
 *         @OA\Property(property="package", type="object",
 *           @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="title", type="string", example="Basic Package"),
 *           @OA\Property(property="details", type="string", example="<p>Up to 50 invitees</p><p>Basic design</p>"),
 *           @OA\Property(property="attendees", type="integer", example=50),
 *           @OA\Property(property="price", type="number", example=99.99),
 *           @OA\Property(property="discount", type="number", example=0.00),
 *           @OA\Property(property="finalPrice", type="number", example=99.99),
 *           @OA\Property(property="rank", type="integer", example=1)
 *         )
 *       )
 *     )
 *   )
 * )
 */

// Only handle GET requests for listing packages
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['a']) && $_GET['a'] === 'Packages')) {
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
    
    switch ($endpoint) {
        case 'list':
            // Determine language preference
            $language = isset($_GET['language']) && $_GET['language'] === 'ar' ? 'ar' : 'en';
            
            // Get only visible packages (hidden = 1 means visible to clients, based on bladePackages.php logic)
            $packages = selectDB("packages", "`status` = '0' AND `hidden` = '1' ORDER BY `rank` ASC");
            
            $formattedPackages = [];
            if ($packages) {
                foreach ($packages as $package) {
                    // Format package with appropriate language
                    $title = $language === 'ar' ? $package['arTitle'] : $package['enTitle'];
                    $details = $language === 'ar' ? $package['arDetails'] : $package['enDetails'];
                    
                    // Calculate final price after discount
                    $finalPrice = $package['price'] - $package['discount'];
                    if ($finalPrice < 0) $finalPrice = 0;
                    
                    $formattedPackages[] = [
                        'id' => $package['id'],
                        'title' => $title,
                        'details' => $details,
                        'attendees' => $package['attendees'],
                        'price' => (float) $package['price'],
                        'discount' => (float) $package['discount'],
                        'finalPrice' => (float) $finalPrice,
                        'rank' => $package['rank']
                    ];
                }
            }
            
            echo outputData(['packages' => $formattedPackages]);
            break;
            
        case 'details':
            // Validate packageId
            if (empty($_GET['id'])) {
                echo outputError(["msg" => "Package ID required."]);
                exit;
            }
            
            // Determine language preference
            $language = isset($_GET['language']) && $_GET['language'] === 'ar' ? 'ar' : 'en';
            
            // Get package details
            $package = selectDB("packages", "`id` = '{$_GET['id']}' AND `status` = '0'");
            
            if (!$package) {
                echo outputError(["msg" => "Package not found."]);
                exit;
            }
            
            // Format package with appropriate language
            $title = $language === 'ar' ? $package[0]['arTitle'] : $package[0]['enTitle'];
            $details = $language === 'ar' ? $package[0]['arDetails'] : $package[0]['enDetails'];
            
            // Calculate final price after discount
            $finalPrice = $package[0]['price'] - $package[0]['discount'];
            if ($finalPrice < 0) $finalPrice = 0;
            
            $formattedPackage = [
                'id' => $package[0]['id'],
                'title' => $title,
                'details' => $details,
                'attendees' => $package[0]['attendees'],
                'price' => (float) $package[0]['price'],
                'discount' => (float) $package[0]['discount'],
                'finalPrice' => (float) $finalPrice,
                'rank' => $package[0]['rank']
            ];
            
            echo outputData(['package' => $formattedPackage]);
            break;
            
        default:
            echo outputError(["msg" => "Invalid endpoint."]);
            break;
    }
    exit;
} else {
    echo outputError(["msg" => "Invalid request method or endpoint."]);
    exit;
}
