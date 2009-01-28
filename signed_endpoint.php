<?php

require_once("OAuth.php");

error_reporting(E_ALL);

class NingSignatureMethod extends OAuthSignatureMethod_RSA_SHA1 {
	protected function fetch_public_cert(&$request) {
		return "-----BEGIN CERTIFICATE-----
MIIDDTCCAnagAwIBAgIJAIIR50WLj+soMA0GCSqGSIb3DQEBBQUAMGMxCzAJBgNV
BAYTAlVTMQswCQYDVQQIEwJDQTESMBAGA1UEBxMJUGFsbyBBbHRvMRIwEAYDVQQK
EwlOaW5nIEluYy4xDTALBgNVBAsTBE5pbmcxEDAOBgNVBAMTB2hlbm5pbmcwHhcN
MDgwNzI1MTI0NzA3WhcNMDkwNzI1MTI0NzA3WjBjMQswCQYDVQQGEwJVUzELMAkG
A1UECBMCQ0ExEjAQBgNVBAcTCVBhbG8gQWx0bzESMBAGA1UEChMJTmluZyBJbmMu
MQ0wCwYDVQQLEwROaW5nMRAwDgYDVQQDEwdoZW5uaW5nMIGfMA0GCSqGSIb3DQEB
AQUAA4GNADCBiQKBgQC02P2t2GYvZuahj8CW8ZUjennDN+pc6dVOMkwZRxCLF/h1
KOgRNW5o50oHxOiqmyR5ALD+sYQAjzI3LK5X//nP94HRcypzoE1aD/dh7VoG9kj0
vYnp/95CwUtRVWmekNVJUCzARyfWmavUZLRa6rxosc+oYuzEOBzl87PYWfZCcwID
AQABo4HIMIHFMB0GA1UdDgQWBBSbTXSWyslrsd+/mnWM7OrjTdubADCBlQYDVR0j
BIGNMIGKgBSbTXSWyslrsd+/mnWM7OrjTdubAKFnpGUwYzELMAkGA1UEBhMCVVMx
CzAJBgNVBAgTAkNBMRIwEAYDVQQHEwlQYWxvIEFsdG8xEjAQBgNVBAoTCU5pbmcg
SW5jLjENMAsGA1UECxMETmluZzEQMA4GA1UEAxMHaGVubmluZ4IJAIIR50WLj+so
MAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAYOAHrP9VGlQqu2tvHEWN
gAPj3wpCj+PWQjtf83Gl7LrInz1QDXJMBGABPYvJtVdDyV6UQF4XS0fl4l9mPtsU
wc3cgErbPxG1gqxDwHALSc//LmqC6FJURqEbDdnmQmFbkTYxYBjIDgep02ki1CeA
gstG7eU+0ax6ARotyl2kd8o=
-----END CERTIFICATE-----";
	}
}

class MainHandler {

	function test_method() {
		return array("msg" => "Hello from the backend! (arg1 = ".$_POST['arg1'].", arg2 = ".$_POST['arg2'].")");
	}

	function main() { 
		if (!extension_loaded("curl")) return err("system", "curl extension not loaded");

		//Build a request object from the current request
		$this->request = OAuthRequest::from_request(null, null, array_merge($_GET, $_POST));
		
		//Initialize the new signature method
		$signature_method = new NingSignatureMethod();
		
		//Check the request signature
		@$signature_valid = $signature_method->check_signature($this->request, null, null, $_GET["oauth_signature"]);
		
		//Build the output object
		$payload = array();
		
		if ($signature_valid !== true) {
			return array("stat" => "fail", "error" => "invalid", "msg" => "Signature verification failed!");
		}

		if (!isset($_POST['f'])) return err("missing-param", "'f' parameter required");
		$f = $_POST['f'];
		if (!in_array($f, array("test_method"))) {
			return array("stat" => "fail", "error" => "bad_method", "msg" => "Unknown method!");
		}
		
		$this->sns_id = $_GET['opensocial_viewer_id'];
		
		$resp = $this->$f();
		if (!isset($resp['stat'])) $resp['stat'] = 'ok';
		
		return $resp;
	}
	
}

$m = new MainHandler;
echo json_encode($m->main());
