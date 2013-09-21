<?php
require_once (SCRIPTS_PATH . '/phpmailer/class.phpmailer.php');
class Zendvn_Phpmailer{
		
	public function send($options = null, $content = null){
		//Khởi tạo đối tượng
		$mail = new PHPMailer();
		
		$mail->IsSMTP(); // Gọi đến class xử lý SMTP
		$mail->SMTPDebug  = 0;                    // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // Sử dụng đăng nhập vào account
		if($options['smtpsecure'] != 'none'){
		$mail->SMTPSecure = $options['smtpsecure'];
		}
		$mail->Host       = $options['smtphost']; // Thiết lập thông tin của SMPT VD:smtp.gmail.com
		$mail->Port       = $options['smtpport']; // Thiết lập cổng gửi email của máy VD:465
		$mail->Username   = $options['smtpuser']; // SMTP account username
		$mail->Password   = $options['smtppass']; // SMTP account password
		
		//Thiet lap thong tin nguoi gui va email nguoi gui
		$mail->SetFrom($options['mailfrom'],$options['fromname']);
		
		$to_email = explode(',', $options['tomail']);
		//Thiết lập thông tin người nhận
		foreach ($to_email AS $key => $val){
			$mail->AddAddress($val, $options['title']);
		}
		//$mail->AddAddress("zendvn@yahoo.com", "ZendVN Group");
		
		//Thiết lập email nhận email hồi đáp
		//nếu người nhận nhấn nút Reply
		$mail->AddReplyTo($options['mailfrom'],$options['title']);
		
		/*=====================================
		 * THIET LAP NOI DUNG EMAIL
		 *=====================================*/
		
		//Thiết lập tiêu đề
		$mail->Subject    = $options['subject'];
		
		//Thiết lập định dạng font chữ
		$mail->CharSet = "utf-8";
		
		//$mail->Body = $body;
		$mail->MsgHTML($content);
		$mail->IsHTML(true);
		
		if(!$mail->Send()) {
		  //echo "Mailer Error: " . $mail->ErrorInfo;
			echo "";
		} else {
			echo "";
		}
	}
}