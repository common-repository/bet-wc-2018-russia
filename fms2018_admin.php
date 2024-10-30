<?php
class fms2018m
{
// private $tablemecze;
    private $wpdb;
    
    public function __construct()
    {
        global $wpdb;
        $prefix             = $wpdb->prefix;
        $this->tablemecze   = $prefix . "fms2018_mecze";
		$this->tabletypy   = $prefix . "fms2018_typy";
        $this->tablepanstwa = $prefix . "fms2018_panstwa";
		$this->tablestadiony = $prefix . "fms2018_stadiony";
		$this->tablefazy = $prefix . "fms2018_fazy";
		$this->tableusers = $prefix . "users";
        $this->wpdb         = $wpdb;
    }
    
    public function fms_meczeall()
    {
        $query = "SELECT fm.id, fm.id_p1, fm.id_p2, fm.w_p1, fm.w_p2, fm.czas, fm.faza, ff.name as name_fazy, ff.name_eng as name_fazy_eng, fp.name as p1_name , fp.name_eng as p1_name_eng, fp2.name as p2_name, fp2.name_eng as p2_name_eng,  fs.miasto, fs.miasto_eng, fs.stadion, fs.stadion_eng from " . $this->tablemecze . " fm
        LEFT JOIN " . $this->tablepanstwa . " fp on (fm.id_p1 = fp.id) 
        LEFT JOIN " . $this->tablepanstwa . " fp2 on (fm.id_p2 = fp2.id)
		LEFT JOIN " . $this->tablefazy . " ff on (fm.faza = ff.id)
		LEFT JOIN " . $this->tablestadiony ." fs on (fm.stadion = fs.id)";
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    
    public function fms_panstwaall()
    {
        $query = "SELECT id, name,name_eng from " . $this->tablepanstwa;
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	
	public function fms_stadionyall()
    {
        $query = "SELECT id, miasto, miasto_eng, stadion, stadion_eng from " . $this->tablestadiony;
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	public function fms_fazyall()
    {
        $query = "SELECT id, name, name_eng from " . $this->tablefazy;
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	
    public function fms_dodaj_mecz($data)
    {
        $this->wpdb->insert($this->tablemecze, $data, array(
            '%s',
            '%s',
            '%s'
        ));
    }
    
    public function fms_select_mecz($id)
    {
        $query = "SELECT fm.id, fm.id_p1, fm.id_p2, fm.w_p1, fm.w_p2, fm.czas, fp.name as p1_name, fp2.name as p2_name from " . $this->tablemecze . " fm
        LEFT JOIN " . $this->tablepanstwa . " fp on (fm.id_p1 = fp.id) 
        LEFT JOIN " . $this->tablepanstwa . " fp2 on (fm.id_p2 = fp2.id)
        WHERE fm.id =" . $id;
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	
	public function fms_typyuser($id)
    {
        $query = "SELECT ft.id_meczu, ft.id_user, ft.w_p1, ft.w_p2 from " . $this->tabletypy . " ft
		WHERE ft.id_user =". $id;
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	
	public function fms_typyall()
    {
        $query = "SELECT ft.id_meczu, ft.id_user, ft.w_p1, ft.w_p2, u.user_nicename from " . $this->tabletypy . " ft
		Left join ". $this->tableusers ." u ON ( ft.id_user = u.ID )";
        return $this->wpdb->get_results($query, ARRAY_A);
    }
	
    public function deleteAll()
    {
        $sql = "TRUNCATE TABLE " . $this->tableName;
        $this->wpdb->query($sql);
    }
    
}

function fms2018_settings()
{	
	$lang = get_option('fms2018_lang');
	$s_users = get_option('fms2018_susers');
	$display_types = get_option('fms2018_display_types');
	if($lang == 1) { 
		$l_header = 'Ustawienia MŚ 2018';
		$l_pzw = 'Ilość pkt za dokładny wynik';
		$l_pzr = 'Ilośc pkt za rezultat';
		$l_bm = 'Na ile minut przed meczem można typować';
		$l_users = 'Użytkownicy';
		$l_allusers = 'Wszyscy użytkownicy wyświetlani w rankingu.';
		$l_users_after_bet = 'Użytkownicy po wprowadzeniu pierwszego typu są wyświetlani w rankingu.';
		$l_users_role = 'Użytkownicy o wybranej roli mogą uczestniczyć w grze: ';
		$l_types = 'Typy: ';
		$l_hidebets = 'Typy innych graczy zostaną ujawnione na '. get_option('fms2018_block') . ' minut przed meczem';
		$l_showbets = 'Typy innych graczy są jawne (niepolecana opcja)';
		$l_end = '<div style="background: lightcoral; padding: 15px"><h3 style="color: #fff">Plugin nie jest już rozwijany!</h3><b>
Powstał nowy: Easy Bet. <a href="https://wordpress.org/plugins/easy-bet/">https://wordpress.org/plugins/easy-bet/</a><br><br>

Plugin Easy Bet jest ulepszoną wersją Bet WC 2018 Russia. Zawiera nowe funkcje takie jak zarządzanie:<br>
- Drużynami<br>
- Ligami<br>
- Miejscami<br>
- Fazami<br></b></div>';
	}
	else {
		$l_header = 'Settings WC 2018';
		$l_pzw = 'Points for the exact result:';
		$l_pzr = 'Points for the result:';
		$l_bm = 'Block bet before match (in minutes):';	
		$l_users = 'Users';
		$l_allusers = 'Everyone displayed in the ranking.';
		$l_users_after_bet = 'Users after entering the first type are displayed in the ranking.';
		$l_users_role = 'Users with the selected role can participate in the game: ';
		$l_types = 'Bets: ';
		$l_hidebets = 'Players types will be displayed '. get_option('fms2018_block') . ' minutes before match starts';
		$l_showbets = 'Players types are public all time (not recommended option)';
		$l_end = '<div style="background: lightcoral; padding: 15px"><h3 style="color: #fff">The plugin is no longer being developed!</h3><b>
A new one has been created: Easy Bet. <a href="https://wordpress.org/plugins/easy-bet/">https://wordpress.org/plugins/easy-bet/</a><br><br>

The Easy Bet plugin is an upgrade to the Bet WC 2018 Russia plugin. New features such as management have been added:<br>
- teams<br>
- leagues<br>
- places<br>
- stages<br></b></div>';
	}	
	echo '<h2>'.$l_header.'</h2>';
	echo $l_end;
    echo '<div class="wrap"> ';
    echo '<form action="options.php" method="post" >' . wp_nonce_field('update-options') . ' ';
	echo '<label>Lang/Język: </label><br>
	<input type="radio" name="fms2018_lang" value="1"'; if($lang == 1) echo 'checked';
	echo '> Polski<br>
	<input type="radio" name="fms2018_lang" value="2"'; if($lang == 2) echo 'checked';
	echo '> English<br>';	
	
    echo '<label>'.$l_pzw.'</label><input type="number" name="fms2018_pktwynik" value="' . get_option('fms2018_pktwynik') . '" /><br>';
    echo '<label>'.$l_pzr.'</label><input type="number" name="fms2018_pktrezultat" value="' . get_option('fms2018_pktrezultat') . '" /><br>';

	
	echo '<br><label>'.$l_types.': </label><br>';
	echo '<label>'.$l_bm.'</label><input type="number" name="fms2018_block" value="' . get_option('fms2018_block') . '" /><br>';	
	echo '<input type="radio" name="fms2018_display_types" value="0"'; if($display_types == 0) echo 'checked';
	echo '> '.$l_hidebets.'<br>
	<input type="radio" name="fms2018_display_types" value="1"'; if($display_types == 1) echo 'checked';
	echo '> '.$l_showbets.'<br><br>';
	
	echo '<br><label>'.$l_users.': </label><br>
	<input type="radio" name="fms2018_susers" value="1"'; if($s_users == 1) echo 'checked';
	echo '> '.$l_allusers.'<br>
	<input type="radio" name="fms2018_susers" value="2"'; if($s_users == 2) echo 'checked';
	echo '> '.$l_users_after_bet.'<br><br>';
	
	echo $l_users_role;
	global $wp_roles; 
	echo '<select name="fms2018_susers_role">';
	echo '<option>all users</option>';
	foreach ( $wp_roles->roles as $key=>$value ):
	if ($key == get_option('fms2018_susers_role')) $selected = 'selected="selected"'; else $selected = null;
	echo '<option '.$selected.'>'.$key.'</option>';
	endforeach;
	echo '</select>';
	
    echo '<input type="hidden" name="action" value="update" />';
    echo '<input type="hidden" name="page_options" value="fms2018_lang,fms2018_pktwynik,fms2018_pktrezultat,fms2018_block,fms2018_susers,fms2018_susers_role,fms2018_display_types" />';
	
	wp_nonce_field('fms_update_setting-nonce','fms_nonce_field'); 
	submit_button('Update', 'primary');

   // echo '<input type="submit" name="Submit" value="Update" />';
	
	echo '</form></div>'; 
	
	echo '<div style="color: red"><br> Shortcodes:<br> [fms2018_mecze]<br>[fms2018_ranking]<br>[fms2018_stats]</div>';
}
function fms_withnull_query( $query )
{
    return str_ireplace( "'NULL'", "NULL", $query ); 
}

function fms_komunikat_mecze($success,$k_ok,$k_error)
		{
			$html = '<div>';
            if ($success) $html .= '<span class="k_ok komunikat">'.$k_ok.'</span>';    
			else $html .= '<span class="k_error komunikat">'.$k_error.'</span>';
			$html .= '</div>';
		return $html;
		}
		
function fms2018_mecze()
{
    $lang = get_option('fms2018_lang');
    $model = new fms2018m();
    global $wpdb;
    $prefix = $wpdb->prefix;
	
	if($lang == 1) {
		$l_header = 'Mecze';
		$l_team = 'Drużyna';
		$l_wynik = 'Wynik';
		$l_date = 'Data';
		$l_faza = 'Faza';
		$l_miejsce = 'Miasto - Stadion';
		$l_meczadodany = 'Mecz został dodany!';
		$l_blad = 'Błąd!';
		$l_edycjameczuok = 'Edycja powiodła się!';
		$l_usunmecz = 'Mecz został usuniety!';
		$l_edycjameczu = 'Edytujesz mecz mecz:';
		$l_dodajmecz = 'Dodaj mecz';	
		$l_select = 'wybierz';
		$l_wynik = 'Wynik';		
	}
	else {
		$l_header = 'Matches';
		$l_team = 'Team';
		$l_wynik = 'Results';
		$l_date = 'Date';
		$l_faza = 'Stage';
		$l_miejsce = 'City - Stadium';	
		$l_meczadodany = 'Match was added!';
		$l_blad = 'Error!';
		$l_edycjameczuok = 'Edit success!';
		$l_usunmecz = 'Match was deleted!';
		$l_edycjameczu = 'Edition of the match:';
		$l_dodajmecz = 'Add match';
		$l_select = 'select';
		$l_wynik = 'Score';			
	}
	
	if (isset($_POST['godzina_minus'])) {
		$mecze_all = $model->fms_meczeall();
		$czasy ='';
		$idki = '';
		foreach($mecze_all as $mecz){
			$czasy .= "'".date("Y-m-d H:i:s", strtotime($mecz['czas'])-3600)."',";
			$idki .= $mecz['id'].',';
		}	
		$query ="UPDATE `".$prefix."fms2018_mecze` SET czas = ELT(id, ".substr($czasy, 0, -1).") WHERE id IN (".substr($idki, 0, -1).")";
		$wpdb->query($query);
	}
		if (isset($_POST['godzina_plus'])) {
		$mecze_all = $model->fms_meczeall();
		$czasy ='';
		$idki = '';
		foreach($mecze_all as $mecz){
			$czasy .= "'".date("Y-m-d H:i:s", strtotime($mecz['czas'])+3600)."',";
			$idki .= $mecz['id'].',';
		}	
		$query ="UPDATE `".$prefix."fms2018_mecze` SET czas = ELT(id, ".substr($czasy, 0, -1).") WHERE id IN (".substr($idki, 0, -1).")";
		$wpdb->query($query);
	}
	
    if (isset($_POST['dodaj_mecz']) && check_admin_referer('fms_dodaj_mecz-nonce','fms_nonce_field'))
        if ($_POST['dodaj_mecz']) {   
            $table_name = $prefix . "fms2018_mecze";
            $id_p1      = sanitize_text_field($_POST['id_p1']);
            $id_p2      = sanitize_text_field($_POST['id_p2']);
            $czas       = sanitize_text_field($_POST['czas']);
			$stadion       = sanitize_text_field($_POST['stadion']);
            $faza       = sanitize_text_field($_POST['faza']);
			
            $success = $wpdb->insert($table_name, array(
                "id_p1" => $id_p1,
                "id_p2" => $id_p2,
                "czas" => $czas,
				"stadion" => $stadion,
				 "faza" => $faza,
            ));
		echo fms_komunikat_mecze($success,$l_meczadodany,$l_blad);
        }
    
    if (isset($_POST['edytuj_mecz']) && check_admin_referer('fms_edytuj_mecz-nonce','fms_nonce_field'))
        if ($_POST['edytuj_mecz']) {  
			
				$error = false;
				if (!is_numeric($_POST['id'])) $error = true;
				if (!is_numeric($_POST['id_p1'])) $error = true;
				if (!is_numeric($_POST['id_p2'])) $error = true;
				if (!is_numeric($_POST['w_p1']) && $_POST['w_p1'] != null) $error = true;
				if (!is_numeric($_POST['w_p2']) && $_POST['w_p2'] != null) $error = true;
			
			if($error == false) 
			{
            $table_name = $prefix . "fms2018_mecze";
            $id         = sanitize_text_field($_POST['id']);
            $id_p1      = sanitize_text_field($_POST['id_p1']);
            $id_p2      = sanitize_text_field($_POST['id_p2']);
			$w_p1      = sanitize_text_field($_POST['w_p1']);
            $w_p2      = sanitize_text_field($_POST['w_p2']);
            $czas       = sanitize_text_field($_POST['czas']);
            if($w_p1 == null) $w_p1 = 'NULL';
			if($w_p2 == null) $w_p2 = 'NULL';
			add_filter( 'query', 'fms_withnull_query' );
			
            $success = $wpdb->update($table_name, array(
                "id_p1" => $id_p1,
                "id_p2" => $id_p2,
				"w_p1" => $w_p1,
				"w_p2" => $w_p2,
                "czas" => $czas
            ), array(
                'id' => $id
            ));
			 remove_filter( 'query', 'fms_withnull_query' );
	
	
			echo fms_komunikat_mecze($success,$l_edycjameczuok,$l_blad);
		}
		else 'blad';
        }
    
    if (isset($_GET['zadanie']) && $_GET['zadanie'] == 'usun') {
        $id         = sanitize_text_field($_GET['mecz_id']);
        $table_name = $prefix . "fms2018_mecze";
        $success    = $wpdb->delete($table_name, array(
            'id' => $id
        ));
		echo fms_komunikat_mecze($success,$l_usunmecz,$l_blad);
    }
         
    if (isset($_GET['zadanie']) && $_GET['zadanie'] == 'edytuj') {
        echo '<h2>'.$l_edycjameczu.'</h2>';
        $mecz = $model->fms_select_mecz(sanitize_text_field($_GET['mecz_id']));
  
        echo '<form action ="?page=fms2018_mecze" method ="post">
          <input type="hidden" name="id" value="' . sanitize_text_field($_GET['mecz_id']) . '">
          <table><tr><th>'.$l_team.' 1</th><th>'.$l_team.' 2</th><th>'.$l_date.'</th></tr>
          <tr>
          <td><select name="id_p1">';
        
        $results = $model->fms_panstwaall();
        foreach ($results as $panstwo) {
            if ($mecz[0]['id_p1'] == $panstwo['id'])
                $selected = ' selected';
            else
                $selected = '';
			
			echo '<option value="' . $panstwo['id'] . '" ' . $selected . '>';
			if($lang == 1) echo $panstwo['name']; else echo $panstwo['name_eng'];
			echo '</option>';
        }
        echo '</select></td>';
        echo '<td><select name="id_p2">';
    
        foreach ($results as $panstwo) {
            if ($mecz[0]['id_p2'] == $panstwo['id'])
                $selected = ' selected';
            else
                $selected = '';
			echo '<option value="' . $panstwo['id'] . '" ' . $selected . '>';
			if($lang == 1) echo $panstwo['name']; else echo $panstwo['name_eng'];
			echo '</option>';	
        }
		
        echo '</select></td>';
        echo '<td><input placeholder="2018-06-14 14:00:00" data-format="dd/MM/yyyy hh:mm:ss" type="text" name = "czas" id = "czas" value="'.$mecz[0]['czas'].'"></input></td>';	
		echo '</tr><tr><td>'.$l_wynik.'</td></tr><tr>
		<td><input type = "number" name = "w_p1" id = "wp_1" value = "'.$mecz[0]['w_p1'].'">:</td>
		<td><input type = "number" name = "w_p2" id = "wp_2" value = "'.$mecz[0]['w_p2'].'"></td>
		</tr>
		</table>';
		wp_nonce_field('fms_edytuj_mecz-nonce','fms_nonce_field'); 
		submit_button('Update', 'primary', 'edytuj_mecz' );
		//<input type = "submit" name = "edytuj_mecz" value = "Update">
		echo '</form>';      
    }
	
    else {
        echo '<h2>'.$l_dodajmecz.'</h2>';
        echo '<form action ="?page=fms2018_mecze" method ="post">
    <table><tr><th>'.$l_team.' 1</th><th>'.$l_team.' 2</th><th>'.$l_faza.'</th><th>'.$l_miejsce.'</th><th>'.$l_date.'</th></tr>
    <tr>
    <td><select name="id_p1" required><option disabled selected value>'.$l_select.'</option>';
        
        $results = $model->fms_panstwaall();
        foreach ($results as $panstwo) {
           	echo '<option value="' . $panstwo['id'] . '" ' . $selected . '>';
			if($lang == 1) echo $panstwo['name']; else echo $panstwo['name_eng'];
			echo '</option>';
        }
        echo '</select></td>';   
        echo '<td><select name="id_p2" required><option disabled selected value>'.$l_select.'</option>';
        
        foreach ($results as $panstwo) {
            echo '<option value="' . $panstwo['id'] . '" ' . $selected . '>';
			if($lang == 1) echo $panstwo['name']; else echo $panstwo['name_eng'];
			echo '</option>';
        }
        echo '</select></td>';
		echo '<td><select name="faza" required><option disabled selected value>'.$l_select.'</option>';
        $results = $model->fms_fazyall();
		foreach ($results as $faza) {
            echo '<option value="' . $faza['id'] . '">';
			if($lang ==1) echo $faza['name']; else echo $faza['name_eng'];
			echo '</option>';
        }
        echo '</select></td>';	
		echo '<td><select name="stadion" required><option disabled selected value>'.$l_select.'</option>';
        $results = $model->fms_stadionyall();
        foreach ($results as $stadion) {
			if($lang == 1)
            echo '<option value="' . $stadion['id'] . '">' . $stadion['miasto'] . ' - '. $stadion['stadion'].'</option>';
			else
			echo '<option value="' . $stadion['id'] . '">' . $stadion['miasto_eng'] . ' - '. $stadion['stadion_eng'].'</option>';
        }
        echo '</select></td>';
 	
		echo '<td><input placeholder="2018-06-14 14:00:00" data-format="dd/MM/yyyy hh:mm:ss" type="text" name = "czas" id = "czas"></input></td>';
		echo '</tr>
			</table>';
			wp_nonce_field('fms_dodaj_mecz-nonce','fms_nonce_field'); 
			submit_button($l_dodajmecz, 'primary', 'dodaj_mecz' );
			//echo '<input type = "submit" name = "dodaj_mecz" value = "'.$l_dodajmecz.'">';
			echo '</form>';
    }  
    $mecze_all = $model->fms_meczeall();
	usort($mecze_all, function($a1, $a2) {
   $v1 = strtotime($a1['czas']);
   $v2 = strtotime($a2['czas']);
   return $v1 - $v2; 
});
	
	
	echo '<h2>'.$l_header.'</h2>';
	if($lang != 1) {
	echo '<span style="color: red">Warning! Set correct time of start matches for Your timezone</span>';
	echo '<form action ="?page=fms2018_mecze" method ="post">';
	echo 'You can update the start times of matches by this buttons: <input type = "submit" class="button button-primary" name = "godzina_minus" value = "-1"> ';
	echo '<input type = "submit" class="button button-primary" name = "godzina_plus" value = "+1">';
	echo '</form>';
	echo 'Remember to set correct timezone in wordpress settings(Settings->General->Timezone)<br><br>';
	}
	
    echo '<table class="fms_tablemecze"><tr><th>id</th><th>'.$l_team.' 1</th><th>'.$l_team.' 2</th><th>'.$l_wynik.'</th><th>'.$l_date.'</th><th>'.$l_faza.'</th><th>'.$l_miejsce.'</th><th></th></tr>';
    foreach ($mecze_all as $row) {     

		if($lang == 1){
			$l_p1_name = $row['p1_name'];
			$l_p2_name = $row['p2_name'];
			$l_name_fazy = $row['name_fazy'];
			$l_miasto = $row['miasto'];
			$l_stadion = $row['stadion'];
			$l_usun = 'Usuń';
			$l_edytuj = 'Edytuj';
			$l_pytanie = 'Czy napewno chcesz usunąć mecz:';
		}
		else {
			$l_p1_name= $row['p1_name_eng'];
			$l_p2_name= $row['p2_name_eng'];
			$l_name_fazy = $row['name_fazy_eng'];
			$l_miasto = $row['miasto_eng'];
			$l_stadion = $row['stadion_eng'];
			$l_usun = 'Delete';
			$l_edytuj = 'Edit';
			$l_pytanie = 'Do You want delete this match:';
		}	
		
		echo '<tr><td>'.$row['id'].'</td>';	
		echo '<td>'.$l_p1_name.'</td><td>'.$l_p2_name.'</td>';
		echo '<td style="text-align: center;">'.$row['w_p1'].' : '.$row['w_p2'].'</td><td>'.$row['czas'].'</td>';
		echo '<td>'.$l_name_fazy.'</td><td>'.$l_miasto.' - '.$l_stadion.' </td>';
		echo '<td>
        <a href="?page=fms2018_mecze&mecz_id=' . $row['id'] . '&zadanie=edytuj">'.$l_edytuj.'</a>
        <a href="?page=fms2018_mecze&mecz_id=' . $row['id'] . '&zadanie=usun" onclick="return confirm('."'".$l_pytanie." ".$l_p1_name." : ".$l_p2_name." '".')">'.$l_usun.'</a>
		</td></tr>';
    }
    echo '</table>';
	
	
   
}
?>