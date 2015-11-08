<?php

	require_once "oAuthProvider.php";

	class google_oAuth extends oAuthProvider {
		protected
			$_user_authorize_uri = 'https://accounts.google.com/o/oauth2/auth',
			$_token_request_uri = 'https://www.googleapis.com/oauth2/v4/token',
			$_token_type = 'Bearer',
			$_auth_scope = 'email',
			$_force_POST = true;

		public function getUserInfo($access_token){
			$result = parent::_sendRequest("https://www.googleapis.com/plus/v1/people/me", $access_token);
			if (empty($result))
				return null;


			if (!empty($result['nickname']))
				$username = $result['nickname'];
			else if (!empty($result['emails']))
				$username = strtok($result['emails'][0],'@');
			else $username = $result['displayName'];

			$formatted = array(
				'remote_id' => $result['id'],
				'remote_name' => $username,
				'remote_avatar' => makeHttps($result['image']['url']),
			);

			return $formatted;
		}
	}
