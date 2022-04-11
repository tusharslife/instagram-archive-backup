<?php
	class Backup {
		private $code, $accessToken, $thumbs, $original, $username, $total;
		public function Backup($newCode) {
			$this->code = $newCode;
		}
		public function requestAccessToken() {
			$url = 'https://api.instagram.com/oauth/access_token';
			$fields = array(
				'client_id' 			=> 			'[CODE]',
				'client_secret' 		=> 			'[CODE]',
				'grant_type' 			=> 			'authorization_code',
				'redirect_uri' 			=> 			'[YOUR SITE URL]',
				'code' 					=> 			$this->code
			);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = json_decode(curl_exec($ch), true);
			if(isset($result['access_token'])) {
				$this->accessToken = $result['access_token'];
				$this->username = $result['user']['username'];
				$this->thumbs = array();
				$this->original = array();
				array_push($this->thumbs, $result['user']['profile_picture']);
				array_push($this->original, $this->thumbs[0]);
			}
			else {
				header('Location: [YOUR SITE URL]');
			}
		}
		public function getThumbnails() {
			$url = 'https://api.instagram.com/v1/users/self/media/recent?count=10000&access_token='.$this->accessToken;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = json_decode(curl_exec($ch), true);
			$_SESSION['username']=$this->username;
			$currentId=0;
			while(isset($result['data'][$currentId])) {
				array_push($this->thumbs, $result['data'][$currentId]['images']['thumbnail']['url']);
				array_push($this->original, $result['data'][$currentId]['images']['standard_resolution']['url']);
				$currentId++;
			}
			$this->total = count($this->thumbs);
		}
		public function loadThumbnails() {
			for($currentId = 0; $currentId < $this->total; $currentId++) { ?>
				<li>
					<input type = "checkbox" id = "cb<?php echo $currentId; ?>" class = "check-box"/>
					<label for="cb<?php echo $currentId; ?>"><img src="<?php echo $this->thumbs[$currentId]; ?>"/></label>
				</li>
			<?php 
			}
		}
		public static function downloadFile($url, $path) {
  			$newfname = $path;
  			$file = fopen($url, "rb");
  			if($file) {
    			$newf = fopen($newfname, "wb");
    			if($newf)
    			     while(!feof($file)) {
      			     fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
    				}
  			}
  			if($file) {
    			fclose($file);
  			}	
  			if($newf) {
    			fclose($newf);
  			}
 		}
 		public static function zipData($user) {
			$rootPath = realpath('../images');
			$zip = new ZipArchive();
			$zip->open('../downloads/'.$user.'/backup.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
			foreach ($files as $name => $file) {
    			if (!$file->isDir()) {
        		$filePath = $file->getRealPath();
        		$relativePath = substr($filePath, strlen($rootPath) + 1);
        		$zip->addFile($filePath, $relativePath);
    			}
			}
			$zip->close();
		}
		public static function rrmdir($dir) { 
  			if(is_dir($dir)) { 
    			$objects = scandir($dir); 
     		foreach ($objects as $object) { 
       			if($object != "." && $object != "..") { 
         			if(filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       			} 
     		} 
     		reset($objects); 
     		rmdir($dir); 
   		}
		}
		public function downloadData($toDownload) {
			mkdir('../images/'.$this->username);
			if(is_dir('../downloads/'.$this->username) == false) {
				mkdir('../downloads/'.$this->username);
			}
			$downloadId = 0;
			for($currentId = 0; $currentId < $this->total; $currentId++) {
				if($toDownload[$downloadId] == $currentId) {
					Backup::downloadFile($this->original[$currentId], '../images/'.$this->username.'/'.$downloadId.'.jpg');
					$downloadId++;
				}
			}
			Backup::zipData($this->username);
			Backup::rrmdir('../images/'.$this->username);
		}
	}
?>