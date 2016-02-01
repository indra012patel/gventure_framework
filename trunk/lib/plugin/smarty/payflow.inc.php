<?php
ini_set('display_errors', 0);

class payflow_pro
{
	private $USERNAME;
	private $PASSWORD;
	private $PARTNER;
	private $MERCHANT;
	private $PAYPAL_URL;
	private $VERSION;
	private $POSTVAR;
	private $RESPONSE;

	function __construct($USERNAME, $PASSWORD, $PARTNER, $MERCHANT, $IS_ONLINE = FALSE, $VERSION = '1.0')
	{
		$this->USERNAME = $USERNAME;
		$this->PASSWORD = $PASSWORD;
		$this->PARTNER = $PARTNER;
		$this->MERCHANT = $MERCHANT;
		
		if($IS_ONLINE == FALSE)
		{
			$this->PAYPAL_URL = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$this->PAYPAL_URL = 'https://payflowpro.paypal.com/loginPage.do';
		}

		$this->POSTVAR="TRXTYPE=S&TENDER=C&USER=".$this->USERNAME."&PWD=".$this->PASSWORD."&PARTNER=".$this->PARTNER."&";
		$this->VERSION = $VERSION;
	}

	function MakePayment()
	{
		$headers = array();
        $strResponse = "";
		$strTemp = date("YmdHIsB").rand(11,10000);
		$strRequestId = md5($strTemp.$strRequest);
		$headers[] = "Content-Type: text/namevalue"; //or maybe text/xml
		$headers[] = "GSOL-Timeout: 45";
		$headers[] = "GSOL-VIT-Name: Linux";  // Name of your OS
		$headers[] = "GSOL-OS-Version: Centos 5";  // OS Version
		$headers[] = "GSOL-Client-Type: PHP/cURL";  // What you are using
		$headers[] = "GSOL-Client-Version: 0.01";  // For your info
		$headers[] = "GSOL-Client-Architecture: x86";  // For your info
		$headers[] = "GSOL-Integration-Product: voiceoftheangels.com"; 
		$headers[] = "GSOL-Integration-Version: ".$this->VERSION; // Application version
		$headers[] = "GSOL-Request-ID: " . $strRequestId;
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->PAYPAL_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_HEADER, 0); // tells curl to include headers in response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 45); // times out after 45 secs
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // this line makes it work under https
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->POSTVAR); //adding POST data
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2); //verifies ssl certificate
		curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
		//forces closure of connection when done
		curl_setopt($ch, CURLOPT_POST, 1); //data sent as POST
		$this->RESPONSE = curl_exec($ch);
		curl_close($ch);
		unset($ch);
	}

	function status()
	{
		$arrayresult=array();
		$arrayreturn=array();
		if(strlen($this->RESPONSE)>0)
		{
			$arrayresult = explode("&", $this->RESPONSE);
			if(is_array($arrayresult) and count($arrayresult)>0)
			{
				foreach($arrayresult as $row)
				{
					$row1=explode("=",$row);
					if(isset($row1[0]) and isset($row1[1]))
					$arrayreturn[$row1[0]]=$row1[1];
				}
			}
		}
		return $arrayreturn;
	}

	function MakeURL($amt, $card, $expiry, $cvv2, $firstname, $lastname, $street, $city, $state, $zip, $country, $ip, $comment, $comment2)
	{
		$this->POSTVAR.="AMT=".$amt."&ACCT=".$card."&EXPDATE=".$expiry."&CVV2=".$cvv2."&FIRSTNAME=".$firstname."&LASTNAME=".$lastname."&STREET=".$street."&CITY=".$city."&STATE=".$state."&ZIP=".$zip."&BILLTOCOUNTRY=".$country."&CUSTIP=".$ip."&COMMENT1=".$comment."&COMMENT2=".$comment2;
	}

	function __destruct() 
	{

	}
}

?>
