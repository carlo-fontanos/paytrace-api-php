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
	
	public function __construct( $client_username, $client_password ) {
		
		/** 
		 * Normally you would include these information as variables when instantiating
		 * the class, but we'll just hard code the credentials for now.
		 */
		$this->credentials = (object) array(
			'url'				=>	'https://paytrace.com/api/default.pay',
			'client_username' 	=> 	$client_username,
			'client_password' 	=> 	$client_password
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
				'EMAIL', 'PHONE', 'FAX', 'CUSTPSWD'
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
	 * @example:		$paytrace_api->transaction_authorization( $customer_id, $amount, array(...) );
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
	 * @description: 	Process a Sale by customer ID (no re-enter credit card required)
	 * @documentation:	http://help.paytrace.com/api-sale
	 * @example:		$paytrace_api->transaction_sale( 50000, '2.28', array(...) );
	 *
	 */
	public function transaction_sale( $customer_id, $amount, $others = array() ){
		
		if( $others ){
			$valid_parameters = array(
				'BNAME',' BADDRESS', 'BADDRESS2', 'BCITY', 'BSTATE', 
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
			'TRANXTYPE' 	=> 'Sale',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'CUSTID' 		=> $customer_id,
			'TERMS' 		=> 'Y',
			'AMOUNT' 		=> $amount,
		) );
		
		return $this->send( $parameters );
		
	}
	
	/**
	 * @description: 	Get customer transactions from PayTrace
	 * @documentation:	http://help.paytrace.com/api-export-transaction-information
	 * @example:		
			$paytrace_api->get_transactions( '05/01/2016', '05/31/2017', array(
				'CUSTID'	=>	'100000', 
				'RETURNBIN'	=>	'Y', 
			) );
	 *
	 */
	public function get_transactions( $start_date, $end_date, $others = array() ){
		
		if( $others ){
			$valid_parameters = array(
				'TRANXTYPE', 'CUSTID', 'USER', 'RETURNBIN', 'SEARCHTEXT'
			);
			
			foreach( $others as $key => $value ){
				if( ! in_array( $key, $valid_parameters ) ){
					return 'Invalid parameter "' . $key . '"'; 
					break;
				}
			}
		}
		
		$parameters = array_merge( $others, array(
			'METHOD' 		=> 'ExportTranx',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'TERMS' 		=> 'Y',
			'SDATE' 		=> $start_date,
			'EDATE' 		=> $end_date,
			
		) );
		
		return $this->send( $parameters, true );
		
	}
	
	/**
	 * @description: 	Get single customer transaction from PayTrace
	 * @documentation:	hhttp://help.paytrace.com/api-export-transaction-information
	 * @example:		
			$paytrace_api->get_transaction( $transaction_id );
	 *
	 */
	public function get_transaction( $transaction_id ){
		
		$parameters = array(
			'METHOD' 		=> 'ExportTranx',
			'UN' 			=> $this->credentials->client_username,
			'PSWD' 			=> $this->credentials->client_password,
			'TERMS' 		=> 'Y',
			'TRANXID' 		=> $transaction_id,
		);
		
		if( isset( $this->send( $parameters, true )->TRANSACTIONRECORD[0] ) ){
			return $this->send( $parameters, true )->TRANSACTIONRECORD[0];
		}
		
		return $this->send( $parameters, true );
		
	}
	
	/**
	 * @description: Handles sending of information to PayTrace API using cURL method
	 *
	 */
	public function send( $parmlist_array, $multiple = false ) {
		
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
		
		// return $response;
		
		if( $multiple ){
			return $this->format_response( $response );
		}
		
		return $this->format_single_response( $response );
		
	}
	
	/**
	 * @description: Converts the response into a readable object
	 *
	 */
	public function format_single_response( $response ){
		
		$response_array = array();
		/* Loop through the items then store them in an array */
		foreach( explode( '|', $response ) as $response_string ){
			
			if( $response_string ){
				$pieces = explode('~', $response_string );
				
				/* Check if value contains + or =, which basically means that the value is an array */
				if ( strpos( $pieces[1], '+' ) !== false && strpos( $pieces[1], '=' ) !== false ){
					$sub_response_array = array();
					
					/* Loop through the sub items then store them in an array */
					foreach( explode( '+', $pieces[1] ) as $sub_response_string ){
						$sub_pieces = explode( '=', $sub_response_string );
						
						if( isset( $sub_pieces[0] ) && isset( $sub_pieces[1] ) ){
							$sub_response_array[$sub_pieces[0]] = $sub_pieces[1];
						}
					}
					
					$pieces[1] = $sub_response_array;
				}
				
				$response_array[$pieces[0]] = $pieces[1];
			}
		}
		
		/* Convert into an object then return */
		return json_decode( json_encode( $response_array ) );
		
	}
	
	/**
	 * @description: Converts multiple response into one readable object
	 *
	 */
	public function format_response( $response ){
		
		$response_array = array();
		/* Loop through the items then store them in an array */
		foreach( explode( '|', $response ) as $response_string ){
			
			if( $response_string ){
				$pieces = explode('~', $response_string );
				
				/* Check if value contains + or =, which basically means that the value is an array */
				if ( strpos( $pieces[1], '+' ) !== false && strpos( $pieces[1], '=' ) !== false ){
					$sub_response_array = array();
					
					/* Loop through the sub items then store them in an array */
					foreach( explode( '+', $pieces[1] ) as $sub_response_string ){
						$sub_pieces = explode( '=', $sub_response_string );
						
						if( isset( $sub_pieces[0] ) && isset( $sub_pieces[1] ) ){
							$sub_response_array[$sub_pieces[0]] = $sub_pieces[1];
						}
					}
					
					$pieces[1] = $sub_response_array;
					
					/* Store formed arrays into one array */
					$response_array[$pieces[0]][] = $pieces[1];
				}
			}
		}
		
		/* Convert into an object then return */
		return json_decode( json_encode( $response_array ) );
		
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
// $paytrace_api = new PayTrace_API('YOUR_USERNAME', 'YOU_PASSWORD');

// echo '<pre>';

// print_r( $paytrace_api->get_user( 200005 ) );

// print_r( $paytrace_api->transaction_sale( 2, '4.49', array(
	// 'CSC'		=>	'998',
	// 'INVOICE'	=>	'10112',
// ) ) );

// print_r( $paytrace_api->transaction_authorization( 200015, '0.00' ) );

// print_r( $paytrace_api->get_transactions( '05/01/2015', '05/31/2017', array(
	// 'CUSTID'	=>	'49',
	// 'RETURNBIN'	=>	'Y',
// ) ) );

// exit();

// $transactions = $paytrace_api->get_transactions( '05/01/2015', '05/31/2017', array( 'CUSTID' => '49', 'RETURNBIN' => 'Y', ) )->TRANSACTIONRECORD;

// echo '<pre>';
// print_r( get_paytrace_invoice_details( $transactions, 40497 ) );

// print_r( $paytrace_api->get_transaction( 172171535 ) );

// exit();