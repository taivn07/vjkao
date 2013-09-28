<?php
class Block_BlkThongKe extends Zend_View_Helper_Abstract{

	public function blkThongKe($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			
			//==================== Thong ke onloine ========================//
			$session=session_id();
			$time=time();
			$time_check=$time-600; // set thời gian kiểm tra là 10 phút
			
			$tbl_online="thongke_online"; // tên của table đã tạo bên trên
			
			$select = $db->select()
			->from($tbl_online,array('session', 'time'))
			->where("session = '" . $session . "'");
			
			$row = $db->fetchAll($select);
			$count = count($row);
			
			if($count == 0){
				$bind = array(
						'session' => $session,
						'time' => $time
						);
				$db->insert($tbl_online, $bind);
			}
			else {
				$bind = array(
						'time' => $time
				);
				$where = " session = '" . $session . "'";
				$db->update($tbl_online, $bind, $where);
			}
			
			$select = $db->select()
			->from($tbl_online,array('session', 'time'));
			$row = $db->fetchAll($select);
			$count_user_online = count($row);
			
			// nếu quá 10 phút mà ko thấy session này làm việc thì tiến hành xóa
			$where = " time < " . $time_check;
			$db->delete($tbl_online, $where);
			
			//==================== Thong ke truy cap ========================//
			$tbl_counter="thongke_counter"; // tên của table chứa dữ liệu
			$img = PUBLIC_URL .'/images/visiter/';
			
			$online = $view->language['online'];
			$today = $view->language['homNay'];
			$yesterday = $view->language['homQua'];
			$x_week = $view->language['tuanNay'];
			$x_month = $view->language['thangNay'];
			$all = $view->language['tatCa'];
			
			$locktime = 15;
			$initialvalue = 1;
			$records = 100000;
			
			$s_online = 1;
			$s_today = 1;
			$s_yesterday = 1;
			$s_all = 1;
			$s_week = 1;
			$s_month = 1;
			
			$s_digit = 1;
			$disp_type = 'Mechanical';
			
			$widthtable = '98';
			$pretext = '';
			$posttext = '';
			$locktime = $locktime * 60;
			// Now we are checking if the ip was logged in the database. Depending of the value in minutes in the locktime variable.
			$day = @date('d');
			$month = @date('n');
			$year = @date('Y');
			$daystart = @mktime(0,0,0,$month,$day,$year);
			$monthstart = @mktime(0,0,0,$month,1,$year);
			// weekstart
			$weekday = @date('w');
			$weekday--;
			if ($weekday < 0) $weekday = 7;
			$weekday = $weekday * 24*60*60;
			$weekstart = $daystart - $weekday;
			
			$yesterdaystart = $daystart - (24*60*60);
			$now = time();
			$ip = $_SERVER['REMOTE_ADDR'];
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from($tbl_counter,array('MAX(id) AS total'));
			$row = $db->fetchRow($select);

			$all_visitors = $row['total'];
			
			
			if ($all_visitors !== NULL)
			{
				$all_visitors += $initialvalue;
			}else
			{
				$all_visitors = $initialvalue;
			}
			
			// Delete old records
			$temp = $all_visitors - $records;
			
			if ($temp>0)
			{
				$where = " id < " . $temp;
				$db->delete($tbl_counter, $where);
			}
			
			$select = $db->select()
			->from($tbl_counter,array('COUNT(*) AS visitip'))
			->where(" ip = '" . $ip . "'")
			->where("(tm+" . $locktime . ")>" . $now);
			$row = $db->fetchRow($select);
			$items = $row['visitip'];
						
			if (empty($items))
			{
				$bind = array(
						'tm' => $now,
						'ip' => $ip
				);
				$db->insert($tbl_counter, $bind);
			}
			
			$n = $all_visitors;
			$div = 100000;
			while ($n > $div)
			{
				$div *= 10;
			}
			
			$select = $db->select()
			->from($tbl_counter,array('COUNT(*) AS todayrecord'))
			->where(" tm > '" . $daystart . "'");
			$row = $db->fetchRow($select);
			$today_visitors = $row['todayrecord'];
			
			$select = $db->select()
			->from($tbl_counter,array('COUNT(*) AS yesterdayrec'))
			->where(" tm > '" . $yesterdaystart . "'")
			->where(" tm < '" . $daystart . "'");
			$row = $db->fetchRow($select);
			$yesterday_visitors = $row['yesterdayrec'];
			
			$select = $db->select()
			->from($tbl_counter,array('COUNT(*) AS weekrec'))
			->where(" tm >= '" . $weekstart . "'");
			$row = $db->fetchRow($select);
			$week_visitors = $row['weekrec'];
			
			$select = $db->select()
			->from($tbl_counter,array('COUNT(*) AS monthrec'))
			->where(" tm >= '" . $monthstart . "'");
			$row = $db->fetchRow($select);
			$month_visitors = $row['monthrec'];
			
			// Show digit counter
			$counter = '<table>';
			// Show today, yestoday, week, month, all statistic
			if($s_online) $counter .= $this->spaceer($img."vtoday.png", $online, $count_user_online);
			if($s_today) $counter .= $this->spaceer($img."vtoday.png", $today, $today_visitors);
			if($s_yesterday) $counter .= $this->spaceer($img."vyesterday.png", $yesterday, $yesterday_visitors);
			if($s_week) $counter .= $this->spaceer($img."vweek.png", $x_week, $week_visitors);
			if($s_month) $counter .= $this->spaceer($img."vmonth.png", $x_month, $month_visitors);
			if($s_all) $counter .= $this->spaceer($img."vall.png", $all, $all_visitors);
			
			$totalImg = array(0,0,0,0,0,0,0,0,0,0);
			for($i=0;$i<10;$i++){
				$lengCon = count(str_split($all_visitors,1));
				if($i < $lengCon){
					$mangCon = str_split(strrev($all_visitors),1);
					$totalImg[$i] = $mangCon[$i];
				}
			}
			
			$counter .= "</table>";
			$counter .= '<div class="total">';
			for($i=9;$i>=0;$i--){
				$counter .= '<img src="'.$img.$totalImg[$i].'.png">';
			}
			$counter .= '</div>';
			
			require_once (BLOCK_PATH . '/BlkThongKe/'.$template.'.php');
		}
	}

	public function spaceer($a1,$a2,$a3)
	{
		global $config;
		$ret  = "<tr>";
		$ret .= "<td width='10%'><img src='".$a1."'/></td>";
		$ret .= "<td>".$a2."</td>";
		$ret .=	"<td width='25%' style='text-align:right;'>".$a3."</td>";
		$ret .= "</tr>";
		return $ret;
	}
}