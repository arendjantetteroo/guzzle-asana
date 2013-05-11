<?php 
/**
 * Oauth example for Asana
 *
 * based on an oauth example by flowbs
 * 
 * Copy oauthparams-dist.php to oauthparams.php, create an app within asana 
 * and fill in the details
 *
 * Afterwards put this file on a webserver, can be local.
 * To test, you can use the builtin php webserver in php 5.4
 * php -S localhost:8888
 */
require dirname(__FILE__). '/../oauthparams.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;
use AJT\Asana\AsanaOauthClient;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

/**
 * Set this to true to see the curl calls that are made, useful if things don't work
 * @var boolean
 */
$debug = false;

// Get the asana Oauth client
$asana_client = AsanaOauthClient::factory(array('debug' => $debug));
?>
<!--This link will direct the user to Asana's App Approval Page-->
<a href="https://app.asana.com/-/oauth_authorize?client_id=<?php echo $params['client_id'] ?>&redirect_uri=<?php echo $params['redirect_uri'] ?>&response_type=<?php echo $params['response_type'] ?>">
	<img src='asana-oauth-button-blue.png' alt='Connect with Asana'/>
</a>
<?php 
	//-------------------------------------------------------------------
	//1. Once returned from Asana, we make sure the 'code' param is present
	//-------------------------------------------------------------------
	if (isset($_GET['code']) && !empty($_GET['code']))  {  
	//-------------------------------------------------------------------
	//2. POST information to the token interface
	//-------------------------------------------------------------------
	$postFields = array(
		'client_id' => $params['client_id'],
		'client_secret' => $params['client_secret'],
		'code' => $_GET['code'],
		'redirect_uri' => $params['redirect_uri']
	);
	$authData = $asana_client->getToken($postFields);
	$access_key = $authData['access_token']; 
		
	echo 'Your current access_token is: ' .$access_key .'<br><hr/>';
	//-------------------------------------------------------------------
	//3. Query Asana for actual data with the recieved token
	//-------------------------------------------------------------------
	echo "<b>Now lets query asana for your personal information at the /users/me entry point:</b><br>";
	$asanaMe = $asana_client->getUserMe();
	echo "id: " .$asanaMe['id'] ."<br>";
	echo "name: " .$asanaMe['name'] ."<br>";	
	echo "email: " .$asanaMe['email'] ."<br>";
	echo "Workspaces:";
	echo "<ol>";
	foreach ($asanaMe['workspaces'] as $asanaWorkspace) {
		echo "<li>id: " .$asanaWorkspace['id'] ." name: " .$asanaWorkspace['name'] ."</li>";
	}
	echo "</ol><hr/>";
	//-------------------------------------------------------------------
	//4. Get a new token using the Token refresh
	//-------------------------------------------------------------------
	echo "<b>...And replace the existing token with a new one using token_refresh:</b><br>";
	array_splice($postFields, 3, -1); //remove code key from $postFields array	
	$postFields['refresh_token'] = $access_key;  //the current (expired) token to be replaced
	$authData = $asana_client->refreshToken($postFields);

	echo "Your new access token is: " .$authData['access_token'] ."<br>";
	echo "it will expire in: " .$authData['expires_in'] ." seconds<br>";
	echo "token type: " .$authData['token_type'] ."<br>"; 
}
?>

