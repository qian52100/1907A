<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101500691333",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAu2ghMQ6QkNxPrXPsUWt2dhtkFOp/b8GdoumRC3UDup1LLbICuEqL1e6NRAyOtWIRWG7I2ceW03rtwuYt1f8ZLB9KRhqUWJVlkgpCPppWT21ZhOQ8PBDUyTuEMoYBEAxtwM/eItxXHQCQC2RdLszMB1AQmJJ/xJ/8xcNIFstMNbUbJhAhozQrrLzvAqn1K6r7fJfDmEaogc9MFy0NrOwkw8NmjJIf9gkOSAChNgkKP6BxacacIxYD6CRiaOFVHlKAxQJnEGe639Pm9fjxS/C8hx17eEyOaChLvSAeKZizsaQcml8IWd96j71kubqK7aFy9unqY863Ke6J/v72JEm+LwIDAQABAoIBAExibOTp4tKoeXFhRnkJLAeHsosz6S1L5Ux5lrzsvNBbPEErxSAIgmZ4DOwnkiMsDZXV0deyGi+oczB1UUlf6IqrVkKsorpYakXUGJwbnAJTt6EKxeJVmeVLdN6C7/vylOl50Di1RmxzxkJebfydTKvOXnVRgPJLo2OX7NIdmrponCGUMEqvKPXgm81h2r9lbf61VzxrO6g3QUxfQkUMHMT6X6DBP7+5AfU1O/1Vv3JlYpjWnSN8+AO16lQ+fbvjEn/SvjIFXGziEbQ5kLxKED1ILMPKOz/22vRPT/9SuxayWrgt/WejpAfr75pwJFO6KjlyMlOpFFCmwiIE69bONsECgYEA5WVvhiojVN5R76yXNPgI4tjDSid8RQHGQO//tsbLXBTRn+HBm76efu4ne0JnZO9LvFQUbrGEBNIIfOrotihhV7N6JrkYPtyZ6Utv9P7JL57jvrkarVz6O1ui+2ew+1o8FAFAAfnXmF68FG/Fzc2keUX5CeG1lT4bDUJziNtvWyMCgYEA0SQQmpBNS+jG/CKmffJ2V8auxcllpN3GP/GUZYBnJjW86BCzOU0Ho9bAOBkHkhF9/AVuMN+l0f+C4fHPqQ7tucsFx0+IY2FQi5kZiXiFz4oSO5jeoAG6uY94CMeNdO9j4T4H5NEIJ7JY3/vBYcY4816hy14vIw39yD+oqJdX14UCgYAV13Km85wpmIF0sJo1EOjHsJvnk5rhdynJRRCz9nmmatUKxBQLIDs3GrXnporxsVckt1y6rWxEiTsFqdg+f9nE3/Hhff4w/hAphmVCjn/ZaOeGYVmI6DFrNW5vQZA4Xn8Z6ISPq5o7h8mgqIPM9KeUZHUx26vOaftpZ8pYXYxcwQKBgQDIR2k/GwFquDgWLHiUFCUQWcv0zLDx+Q9pHMenjBc7mCqXcaawMPwoAfeBwTZmwymKtwiW9RWuzKliBld+5jTxv2KBQ8CwqvifNrYl160M2oQrXnGTeRR/rsycW70zRiy3/tdAYI0Wtsw1crn2EAVpyi3WuxMY1sU5hvzUtloAoQKBgDRLxzt2pv+DzzChj+BT1a5x1ImAmS/NYwhUDDMVGNRyN8bsS6GDpIzb676k93Kl9kIfAY8gc45ZuDJpCv1o7KtaUBwZuNkAGSn5POk4VT4BQBjEXkrjd3xPD+GpyclPNnwCwFm3vFEzByuNKTH7xRH1oAC5L4hyXnhY7Z69/iGa",
		
		//异步通知地址
		'notify_url' => "http://localhost/alipay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://localhost/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgfvN/die9a2SS1vZEm6WsKLs7SAH//mg2eP7ZHpEe/6tiz52lDfl2f5WOt3M5jmTWyGCypylfZPLLn/tlWkK/pOVUTAaJI8dMclv8jMwq7szhLoxHCFINMLGJtfIDtIWPhXcqn0fjkgKuikC4mFJPZMa5Ii+8R+9DWqUr7sEu/btq3ce0WRMTcsi77jkqi8hv5SmX3Ec+qJqcCH75lOZ30sShRFb9uVsNkAx2rSoqs1u55259rfvhEIqPNKbT5Ics1hARr7Y9gE6C9aA1GPGUjPEm8jQkg36JeMpyanvqaDH+DQ7+LJyUe51SRhcid5pyc36BbdvLnfzgmumvVXsgQIDAQAB",
		
	
);