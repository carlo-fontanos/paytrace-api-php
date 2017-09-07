<?php 
/**
 * PayTrace_API Class
 * 
 * @description: 	PayTrace API Class using cURL
 * @dependency: 	None
 * @author: 		Carl Victor Fontanos.
 * @author_url: 	www.carlofontanos.com
 *
 */

class PayTrace_API {
	
	protected $credentials;
	
	public function __construct() {
		
		/** 
		 * Normally you would include these information as variables when instantiating
		 * the class, but we'll just hard code the credentials for now.
		 */
		$this->credentials = (object) array(
			'url'				=>	'https://paytrace.com/api/default.pay',
			'client_username' 	=> 	'YOUR_USERNAME',
			'client_password' 	=> 	'YOUR_PASSWORD'
		);
	}
	
	/**
	 * @description:	Retrieve user information including 4 last digits of user credit card number.
	 * @example:		$paytrace_api->get_user( 100000 );
	 *
	 */
	public function get_user( $customer_id ){
		
		$parameters = array(
			'METHOD' 		=> 'ExportCustomers',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'TERMS' 		=> 'Y',
			'RETURNBIN' 	=> 'Y',
		);
		
		return $this->send( $parameters );
		
	}
	
	/**
	 * @description: Registers the user in PayTrace API. All parameters are required except $others
	 * @example:
			$response = $paytrace_api->create_user( 100000, '4242424242424242', 12, 18, 'Business Name 100000', array(
				'EMAIL'	=> 'example@gmail.com', 
				'PHONE'	=> '987654321'
			) );
	 *
	 */
	public function create_user( $customer_id, $credit_card_number, $expiry_month, $expiry_year, $name_or_business_name, $others = array() ){
		
		if( $others ){
			$valid_parameters = array(
				'BADDRESS', 'BADDRESS2', 'BCITY', 'BSTATE', 'BZIP', 
				'BCOUNTRY', 'SNAME', 'SADDRESS', 'SADDRESS2', 
				'SCITY', 'SCOUNTY', 'SSTATE', 'SZIP', 'SCOUNTRY', 
				'EMAIL', 'PHONE', 'FAX', 'CSC'
			);
			
			foreach( $others as $key => $value ){
				if( ! in_array( $key, $valid_parameters ) ){
					return 'Invalid parameter "' . $key . '"'; 
					break;
				}
			}
		}
		
		$parameters = array_merge( $others, array(
			'METHOD' 		=> 'CreateCustomer',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'CC' 			=> $credit_card_number,
			'EXPMNTH' 		=> $expiry_month,
			'EXPYR' 		=> $expiry_year,
			'BNAME' 		=> $name_or_business_name,
			'TERMS' 		=> 'Y',
		) );
		
		return $this->send( $parameters );
	}
	
	/**
	 * @description: Update user account details in PayTrace. Parameter: $customer_id is required
	 * @example:
			$response = $paytrace_api->update_user( 100000, array(
				'EMAIL'	=> 'example2@gmail.com', 
				'PHONE'	=> '987654321'
			) );
	 *
 	 */
	public function update_user( $customer_id, $others = array() ){
		
		if( $others ){
			$valid_parameters = array(
				'BNAME', 'BADDRESS', 'BADDRESS2', 'BCITY', 'BSTATE', 'BZIP', 
				'BCOUNTRY', 'SNAME', 'SADDRESS', 'SADDRESS2', 
				'SCITY', 'SCOUNTY', 'SSTATE', 'SZIP', 'SCOUNTRY', 
				'EMAIL', 'PHONE', 'FAX', 'NEWCUSTID', 'CC',
				'EXPMNTH', 'EXPYR', 'CUSTPSWD'
			);
			
			foreach( $others as $key => $value ){
				if( ! in_array( $key, $valid_parameters ) ){
					return 'Invalid parameter "' . $key . '"'; 
					break;
				}
			}
		}
		
		$parameters = array_merge( $others, array(
			'METHOD' 		=> 'UpdateCustomer',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'TERMS' 		=> 'Y',
		) );
		
		return $this->send( $parameters );
		
	}
	
	/**
	 * @description: 	Deletes the user from PayTrace
	 * @example:		$paytrace_api->delete_user( 100000 );
	 *
	 */
	public function delete_user( $customer_id ){
		
		$parameters = array(
			'METHOD' 		=> 'DeleteCustomer',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'TERMS' 		=> 'Y',
		);
		
		return $this->send( $parameters );
		
	}
	
	/**
	 * @description: 	Authorize a customer profile in PayTrace
	 * @documentation:	http://help.paytrace.com/api-authorizations
	 * @example:		$paytrace_api->transaction_authorization( '0.00', 100000 );
	 *
	 */
	public function transaction_authorization( $customer_id, $amount, $others = array() ){
		
		if( $others ){
			$valid_parameters = array(
				'BNAME', 'BADDRESS', 'BADDRESS2', 'BCITY', 'BSTATE', 
				'BZIP', 'BCOUNTRY', 'SNAME', 'SADDRESS', 'SADDRESS2', 
				'SCITY', 'SCOUNTY', 'SSTATE', 'SZIP', 'SCOUNTRY', 'EMAIL', 
				'CSC', 'INVOICE', 'DESCRIPTION', 'TAX', 'CUSTREF', 'RETURNCLR', 
				'CUSTOMDBA', 'ENABLEPARTIALAUTH', 'DISCRETIONARY DATA'
			);
			
			foreach( $others as $key => $value ){
				if( ! in_array( $key, $valid_parameters ) ){
					return 'Invalid parameter "' . $key . '"'; 
					break;
				}
			}
		}
		
		$parameters = array_merge( $others, array(
			'METHOD' 		=> 'ProcessTranx',
			'TRANXTYPE' 	=> 'Authorization',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'TERMS' 		=> 'Y',
			'AMOUNT' 		=> $amount,
		) );
		
		return $this->send( $parameters );
		
	}
	
	/**
	 * @description: Handles sending of information to PayTrace API using cURL method
	 *
	 */
	public function send( $parmlist_array ) {
		
		$parmlist_formatted = $this->format_request( $parmlist_array );
		$ch = curl_init();
		
		curl_setopt( $ch, CURLOPT_URL, $this->credentials->url );
		curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $parmlist_formatted );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		
		$response = curl_exec( $ch );
		
		curl_close( $ch );
		
		return $this->format_response( $response );
	}
	
	/**
	 * @description: Converts the response into a readable array
	 *
	 */
	public function format_response( $response ){
		
		/* If we got a response containing the string "RESPONSE~", then it means that our request succeeded */
		if( strpos( $response, 'RESPONSE~' ) !== false ){
			/* We return "1" to indicate success  */
			return 1;
		}
		
		/* If we got a response containing the string "ERROR~", then it means that our request failed */
		if ( strpos( $response, 'ERROR~' ) !== false ){
			/* We clean the error message then return it */
			$response = explode( '. ', str_replace( '|', '', $response ) )[1];
			return $response;
			
		}
		
		$formatted = array();
		
		foreach ( explode( '+', $response ) as $code ){
			$pieces = explode( '=', $code );
			if( isset( $pieces[0] ) && isset( $pieces[1] ) ){
				$formatted[$pieces[0]] = $pieces[1];
			}
		}
		
		return $formatted;
		
	}
	
	/**
	 * @description: Formats the data into urlencoded strings which is readable by PayTrace API
	 *
	 */
	public function format_request( $parmlist_array ){
		
		$formatted = '';

		foreach( $parmlist_array as $key => $value ){
			$formatted .= $key . '~' . $value . '|';
		}

		$formatted = 'parmlist=' . urlencode( $formatted );
		
		return $formatted;
		
	}
	
} 

/* Make global */
$paytrace_api = new PayTrace_API;

// echo '<pre>';
// print_r( $paytrace_api->get_user( 150003 ) );
