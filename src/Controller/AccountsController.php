<?php

App::src('Controller', 'AppController');

class AccountsController extends AppController
{

	public function authorize()
	{
		$access_token = 'CAACEdEose0cBAOFi9FHBmEAYZBdfd8C0DTZAQedngqZBYgYZATgpEwHVIdB6MvifJg63ZBGnKxNOMR7bEqxzUSmTz3JGZBkqjXBnizfPH61ZA0nUiWaOoact2FPZAc5bVK76wddHZCIJ44RYCsZC1Bnl1BJCtu7vr7oFKkMisbpXcTUsgZBoZCf08gwwKavFh3ZBfU8sGZCrMEN2zS4tbfxFr00U9A';
		$graph_url= "https://graph.facebook.com/me/picture?redirect=false&access_token={$access_token}";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $graph_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$output = json_decode(curl_exec($ch));

		curl_close($ch);

		return $output->data->url;
	}

}

?>	