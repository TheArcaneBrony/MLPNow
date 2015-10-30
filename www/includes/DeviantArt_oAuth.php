<?php

	require_once "oAuthProvider.php";

	class DeviantArt_oAuth extends oAuthProvider {
		protected
			$_user_authorize_uri = 'https://www.deviantart.com/oauth2/authorize',
			$_token_request_uri = 'https://www.deviantart.com/oauth2/token',
			$_token_type = 'Bearer',
			$_auth_scope = 'user';

		public function getUserInfo($access_token){
			$result = parent::_sendRequest("https://www.deviantart.com/api/v1/oauth2/user/whoami", $access_token);
			if (empty($result))
				return null;

			$formatted = array(
				'remote_id' => $result['userid'],
				'remote_name' => $result['username'],
				'remote_avatar' => makeHttps($result['usericon']),
			);

			return $formatted;
		}
	}
