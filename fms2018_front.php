<?php

function fms_typuj(){
if (isset($_POST['typ_mecz_id']) && check_admin_referer('fms_typuj-nonce','fms_nonce_field')) 
{	
	$error = false;
	if (!is_numeric($_POST['typ_mecz_id'])) $error = true;
	else $fms_typ_mecz_id = sanitize_text_field( $_POST['typ_mecz_id'] );
	if (!is_numeric($_POST['w_p1'])) $error = true;
	else $w_p1 = sanitize_text_field( $_POST['w_p1'] );
	if (!is_numeric($_POST['w_p2'])) $error = true;
	else $w_p2 = sanitize_text_field( $_POST['w_p2'] );
	
	if($error == false) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	
	// blokada przed zmiana typu po czasie
	$query = "Select czas From ".$prefix."fms2018_mecze where id = '".$fms_typ_mecz_id."'";
	$result = $wpdb->get_results($query, ARRAY_A);
    $aktualny_czas = strtotime(date("Y-m-d H:i:s"))+(int) get_option('gmt_offset') * 60 * 60;  
	$block = get_option('fms2018_block')*60;
	$blokowany_czas = strtotime($result[0]['czas'])-$block;
	if($blokowany_czas-$aktualny_czas>0) {
		
	$id_user = get_current_user_id();
	
	if($w_p1 != null && $w_p2 != null)
		{
		
	$query ="INSERT INTO ".$prefix."fms2018_typy (id_meczu, id_user, w_p1, w_p2) VALUES (%d,%d,%d,%d) ON DUPLICATE KEY UPDATE w_p1= VALUES(w_p1), w_p2 = VALUES(w_p2)";
	$wpdb->query( $wpdb->prepare($query, $fms_typ_mecz_id , $id_user, $w_p1, $w_p2));
	
		}
	}		
	}
}
}
function fms_wyswietl_mecze() 
{
	fms_typuj();
$model = new fms2018m();
$mecze_all = $model->fms_meczeall();
$id_user = get_current_user_id();
$typy_user = $model->fms_typyuser($id_user);
$typy_all = $model->fms_typyall();
$lang = get_option('fms2018_lang');
$s_users_role = get_option('fms2018_susers_role');
$display_types = get_option('fms2018_display_types');

usort($mecze_all, function($a1, $a2) {
   $v1 = strtotime($a1['czas']);
   $v2 = strtotime($a2['czas']);
   return $v1 - $v2; 
});

$html ='';
	if($lang == 1)
	{
		$l_typowanie = 'TYPOWANIE';
	}
	else {
		$l_typowanie = 'BET';	
	}
    $html .= '<h3 class="nag_typ">'.$l_typowanie.'</h3>';
	$html .= '<div id="bet_all">';	

    foreach ($mecze_all as $row) {
	
	if($lang == 1)
	{
		$l_faza = $row['name_fazy'];
		$l_miasto = $row['miasto'];
		$l_stadion = $row['stadion'];
		$l_typowali = 'TYPY:';
		$team1 = $row['p1_name']; 
		$team2 = $row['p2_name'];
		$l_twojtyp = 'TWÓJ TYP:';
		$l_fortyp = '<br>Aby typować musisz być zalogowany.';
		$l_brakdostepu = '<br>Nie posiadasz uprawnień aby typować.';
		$l_typzmieniac = 'Twój typ możesz zmieniać do ';
		$l_typinnych = 'Typy innych będą widoczne o ';
		$l_ilemin = ' minut przed rozpoczęciem meczu';
		$l_typuj = 'TYPUJ';
	}
	else {
		$l_faza = $row['name_fazy_eng'];
		$l_miasto = $row['miasto_eng'];
		$l_stadion = $row['stadion_eng'];
		$l_typowali = 'TYPES:';
		$team1 = $row['p1_name_eng'];
		$team2 = $row['p2_name_eng'];
		$l_twojtyp = "YOUR'S BET:";  
		$l_fortyp = '<br>For bet You have to login.';
		$l_brakdostepu = '<br>You dont have permission to bet.';
		$l_typzmieniac = 'You can change yours bet to ';
		$l_typinnych = 'Players types will be displayed at ';
		$l_ilemin = ' minutes before match starts';
		$l_typuj = 'BET';
	}

		if($row['czas'] != '0000-00-00 00:00:00') {
			$czas_dzien = date("d-m-Y", strtotime($row['czas']));
			$czas_hm = date("H:i", strtotime($row['czas']));
		}
		else $czas = '';
		
		$w1 = '';
		$w2 = '';
			foreach ($typy_user as $search) {
				if($search['id_meczu'] == $row['id']){
				$w1 = $search['w_p1'];
				$w2 = $search['w_p2'];
				}
			}	
			
		$aktualny_czas = strtotime(date("Y-m-d H:i:s"))+(int) get_option('gmt_offset') * 60 * 60;  
		$block = get_option('fms2018_block')*60;
		$blokowany_czas = strtotime($row['czas'])-$block;
		
		if($aktualny_czas > 86400+strtotime($row['czas'])) $class_old ='old oldclosed'; else $class_old ='';
		
		
		$html .= '<div id="m_'.$row['id'].'" class="mecz '.$class_old.'">';
		$html .= '<div class="mecz_l"><div class="mecz_l1">';
		$html .= '<div class="czas_spotkania">'.$czas_dzien.'<br><span class="godzina">'.$czas_hm.'</span></div>';			
		$html .= '<div class="faza">'.$l_faza.'</div>';
		$html .= '<div class="miejsce"><span>'.$l_miasto.'</span><br><span>'.$l_stadion.'</span></div>';
		$html .= '</div><div class="mecz_l2">';
		
	
		
		$html .= '<div class="typowali">'.$l_typowali.'<br>';
	
		foreach ($typy_all as $search) {
				if($search['id_meczu'] == $row['id'] && $search['user_nicename'] != null){
				$html .= '<span class="typ_user">'.$search['user_nicename'].'</span>';
			
				if($blokowany_czas-$aktualny_czas<0 || $search['id_user'] == $id_user || $display_types == 1) 
					$html .= '<span class="typ_wynik">'.$search['w_p1'].':'.$search['w_p2'].'</span>';
				else {
					$html .= '<div class="typ_wynik"><div class="info">?';
					$html .= '<span>'.$l_typinnych.date("Y-m-d H:i", $blokowany_czas);
					$html .='<br>'.get_option('fms2018_block');
					$html .= $l_ilemin.'</span>';
				 
				$html .='</div></div>';
				}
				}
				
					
			}

		$html .= '</div></div></div>';
		$html .= '<div class="mecz_p">';		
		$html .= '<div class="teams">
		<div><img src="'. esc_url( plugins_url( "img/f/f".$row['id_p1'].'.png', __FILE__ ) ) .'" alt="f'.$row['id_p1'].'"><span>'.$team1.'</span></div>
		<div><span class="wynik">'.$row['w_p1'].':'.$row['w_p2'].'</span>
		<span class="vs">VS.</span>
		</div>
		<div><img src="'. esc_url( plugins_url( "img/f/f".$row['id_p2'].'.png', __FILE__ ) ) .'" alt="f'.$row['id_p2'].'"><span>'.$team2.'</span></div></div>';
			
		$html .= '<div class="obstaw"><span>'.$l_twojtyp.'</span>';
		
		if($id_user != 0 && ($s_users_role == wp_get_current_user()->roles[0] || $s_users_role == 'all users')) {	
			if($blokowany_czas-$aktualny_czas<0) { 
				$html .= '<br><span>'.$w1.':'.$w2.'</span><br>';
			}
			else {
			$html .= '<form method="post">
			<input type="hidden" name="typ_mecz_id" value="' . $row['id'] . '">
			<input type="number" name="w_p1" value="'.$w1.'"> : 
			<input type="number" name="w_p2" value="'.$w2.'"><br>';
			
			$html .= wp_nonce_field('fms_typuj-nonce','fms_nonce_field'); 
			
			$html .= '<input type="submit" value="'.$l_typuj.'"></form>';
			}
		}
		else 
		{
			if($id_user != 0) $html .= '<span>'.$l_brakdostepu.'</span>';
			else $html .= '<span>'.$l_fortyp.'</span>';
		}
			
		
		if (isset($_POST['typ_mecz_id']) && $_POST['typ_mecz_id'] == $row['id'])
		{
		$html .= $l_typzmieniac;
		$html .= date("Y-m-d H:i", $blokowany_czas);
		$html .='<br>('.get_option('fms2018_block');
		$html .= $l_ilemin.')';
		
		}
		$html .='</div></div></div>';
    }

		if (isset($_POST['typ_mecz_id'])){
    	$html .= '<script type="text/javascript">';
		$html .="document.getElementById('m_".sanitize_text_field($_POST['typ_mecz_id'])."').scrollIntoView(true);
		window.scrollBy(0, -60);
		</script>";
		}
$html .= '</div>';
return $html;
}
add_shortcode('fms2018_mecze', 'fms_wyswietl_mecze');


function fms_wyswietl_ranking(){
		$lang = get_option('fms2018_lang');	
		$s_users = get_option('fms2018_susers');
		$s_users_role = get_option('fms2018_susers_role');
		
	global $wpdb;
	$prefix = $wpdb->prefix;
	$id_user = get_current_user_id();
	if($s_users_role == 'all users')
	$query = "Select ID, user_nicename from ".$prefix."users order by user_nicename";
	else
	$query ="SELECT ".$prefix."users.ID, ".$prefix."users.user_nicename 
			FROM ".$prefix."users INNER JOIN ".$prefix."usermeta 
			ON ".$prefix."users.ID = ".$prefix."usermeta.user_id 
			WHERE ".$prefix."usermeta.meta_key = '".$prefix."capabilities' 
			AND ".$prefix."usermeta.meta_value LIKE '%".$s_users_role."%' 
			ORDER BY ".$prefix."users.user_nicename";
			
	$results = $wpdb->get_results($query, ARRAY_A);
		
		$row=0;
		global $fms_users;
		foreach($results as $result) {
			
			$fms_users[$row]['pkt'] = 0;
			$fms_users[$row]['id'] = $result['ID'];
			$fms_users[$row]['nazwa'] = $result['user_nicename'];
			$row++;
		}
			
		$query = "Select ID, w_P1, w_P2 From ".$prefix."fms2018_mecze";
		$result = $wpdb->get_results($query, ARRAY_A);
		
		$row=0;
		foreach($result as $mecz) {
			$mecze[$row]['id'] = $mecz['ID'];
			$mecze[$row]['w_p1'] = $mecz['w_P1'];
			$mecze[$row]['w_p2'] = $mecz['w_P2'];

			$row++;
		 }
	
		$query = "Select t.ID_MECZU, t.w_p1, t.w_p2, t.id_user
		From ".$prefix."fms2018_typy t";
		$result = $wpdb->get_results($query, ARRAY_A);
		
		$row=0;
		foreach($result as $mecz) {	
			$typy[$row]['id_meczu'] = $mecz['ID_MECZU'];
			$typy[$row]['w_p1'] = $mecz['w_p1'];
			$typy[$row]['w_p2'] = $mecz['w_p2'];	
			$typy[$row]['id_user'] = $mecz['id_user'];				
			$ilosc_typow = $row;
			$row++;
		}
		
		
		function fms_add_pkt($id, $pkt){
			$row=0;
			global $fms_users;
			
			if(isset($fms_users))
			foreach($fms_users as $user) {
				if($user['id'] == $id) $fms_users[$row]['pkt'] = $fms_users[$row]['pkt'] + $pkt; 
				$row++;
			}
			
			 
		}
		if(isset($typy)){
		foreach($typy as $typ){	
				
			foreach($mecze as $mecz){
				$winner = 3; // dla meczów bez wyników
				$winnertyp = 4; // dla meczów bez wyników
				if($typ['id_meczu'] == $mecz['id'] ) {
					if($mecz['w_p1'] > $mecz['w_p2']) $winner = 1;
					if($mecz['w_p1'] < $mecz['w_p2']) $winner = 2;
					if($mecz['w_p1'] == $mecz['w_p2'] && $mecz['w_p1'] != null) $winner = 0;
					if($typ['w_p1'] > $typ['w_p2']) $winnertyp = 1;
					if($typ['w_p1'] < $typ['w_p2']) $winnertyp = 2;
					if($typ['w_p1'] == $typ['w_p2'] && $mecz['w_p2'] != null) $winnertyp = 0;
					
				if($typ['w_p1'] == $mecz['w_p1'] && $typ['w_p2'] == $mecz['w_p2']) fms_add_pkt($typ['id_user'], get_option('fms2018_pktwynik'));
					
				else 
					if($winner == $winnertyp) fms_add_pkt($typ['id_user'], get_option('fms2018_pktrezultat'));
				}		 
			
			}
		
		}
		}
		
		if($lang == 1) {
			$l_user ='Użytkownik';
			$l_pkt ='PKT';
		}
		else {
			$l_user = 'User';
			$l_pkt ='Points';
		}
		$html = '<div id="fms_ranking" class="fms_ranking"><div class="fms_rank_user"><span>'.$l_user.'</span><span>'.$l_pkt.'</span></div>';
		$i=1;
		
		if(isset($fms_users)) 
		{	
		rsort($fms_users);
		if($s_users == 1) 		
		foreach($fms_users as $user)
		{
			if($user['id'] == $id_user) $current ='fms_current_user'; else $current = null;
			$html .=  "<div class='fms_rank_user ".$current."'><span>".$i.'. '.$user['nazwa']."</span><span>".$user['pkt']."</span></div>";
			$i++;
		}
		if($s_users == 2) 
		foreach($fms_users as $user)
		{
			if($user['id'] == $id_user) $current ='fms_current_user'; else $current = null;
			$query = "select count(id_meczu) as ile from ".$prefix."fms2018_typy where id_user = '".$user['id']."'";
			$result = $wpdb->get_results($query, ARRAY_A);
			if($result[0]['ile']>0){	
			$html .=  "<div class='fms_rank_user ".$current."'><span>".$i.'. '.$user['nazwa']."</span><span>".$user['pkt']."</span></div>";
			$i++;}
		}
		}
		
$html .= '</div>';		

	return $html;
}
add_shortcode('fms2018_ranking', 'fms_wyswietl_ranking');


function fms2018_wyswietl_stats() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$lang = get_option('fms2018_lang');	
	$html = '';
	
		if($lang == 1)
	{
		$l_stats = 'Statystyki';
		$l_points = 'Punkty';
	}
	else 
	{
		$l_stats = 'Statistics';
		$l_points = 'Points';
	}
	 $html .= '<h3 class="nag_typ">'.$l_stats.'</h3>';
	 
	 $query = "select DISTINCT u.id, u.user_nicename
				from ".$prefix."fms2018_mecze fm
				left join ".$prefix."fms2018_typy as ft ON (fm.id = ft.id_meczu)
				left join ".$prefix."users as u ON (ft.id_user = u.ID)";

	 $users = $wpdb->get_results($query, ARRAY_A);
		$row = 0;
		$zawodnicy = array();
		foreach($users as $user) {
			if($user['id'] != null){
			$zawodnicy[$row]['id'] = $user['id'];
			$zawodnicy[$row]['nick'] = $user['user_nicename'];
			$zawodnicy[$row]['pkt'] = 0;
			$row++;
			}
		 }
		fms_typuj();
$model = new fms2018m();
$typy_all = $model->fms_typyall();
$mecze_all = $model->fms_meczeall();
usort($mecze_all, function($a1, $a2) {
   $v1 = strtotime($a1['czas']);
   $v2 = strtotime($a2['czas']);
   return $v1 - $v2; 
});	
	
	$html .='<table class="table table-bordered" id="fms_statistic_table">
  <thead>
    <tr><th></th>';
	foreach ($mecze_all as $mecz) {
		if($lang == 1)
		$html .= '<th>'.$mecz['p1_name'].'<br>vs<br>'.$mecz['p2_name'].'<br>'.$mecz['w_p1'].' : '.$mecz['w_p2'].'<br></th>';
		else
		$html .= '<th>'.$mecz['p1_name_eng'].'<br>vs<br>'.$mecz['p2_name_eng'].'<br>'.$mecz['w_p1'].' : '.$mecz['w_p2'].'<br></th>';
		
	}
 
   $html .= ' </tr>
  </thead>
  <tbody id="fms_statistic_ttable">';

  foreach ($zawodnicy as $zawodnik) {
		$html .= '<tr><td>'.$zawodnik['nick'].'</td>';
		foreach ($mecze_all as $mecz) {	
			$typowal = false;
			foreach ($typy_all as $typ){
			if($typ['id_meczu'] == $mecz['id'] && $zawodnik['id'] == $typ['id_user'])
				{
				$winner = 3; // dla meczów bez wyników
				$winnertyp = 4; // dla meczów bez wyników	
				if($mecz['w_p1'] > $mecz['w_p2']) $winner = 1;
				if($mecz['w_p1'] < $mecz['w_p2']) $winner = 2;
				if($mecz['w_p1'] == $mecz['w_p2'] && $mecz['w_p1'] != null) $winner = 0;
				if($typ['w_p1'] > $typ['w_p2']) $winnertyp = 1;
				if($typ['w_p1'] < $typ['w_p2']) $winnertyp = 2;
				if($typ['w_p1'] == $typ['w_p2'] && $mecz['w_p2'] != null) $winnertyp = 0;
				$class ='bad_bet';
				if($typ['w_p1'] == $mecz['w_p1'] && $typ['w_p2'] == $mecz['w_p2']){ 
				$class ='good_bet';	
				$zawodnik['pkt'] += get_option('fms2018_pktwynik');	
				}
				else
				if($winner == $winnertyp){ 
				$class ='good_result';	
				$zawodnik['pkt'] += get_option('fms2018_pktrezultat');	
				}
				//fms_add_pkt($typ['id_user'], get_option('fms2018_pktrezultat'));
				if(isset($mecz['w_p1']) && isset($mecz['w_p2']))
					{
					$html .= '<td class="'.$class.'">'.$typ['w_p1'].' : '.$typ['w_p2'].'<br><span class="stats_points">'.$l_points.': '.$zawodnik['pkt'].'</span></td>';
					$typowal = true;
					}
				}
				
			}
			if($typowal == false)
				$html .= '<td class="bad_bet"> : <br><span class="stats_points">'.$l_points.': '.$zawodnik['pkt'].'</span></td>';
		}
		$html .= '</tr>';
  }
	
  
  
  
	 $html .= '
   </tbody>
</table>';

	
	 
	 return $html;
}
add_shortcode('fms2018_stats', 'fms2018_wyswietl_stats');
?>