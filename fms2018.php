<?php

/*	Plugin Name: Bet WC 2018 Russia
	Plugin URI: fms2018.foxskav.pl
	Description: Bet with Your friends matches on World Cup 2018 Russia. Typuj ze znajomymi wyniki meczy na MŚ 2018 Rosja.
	Version: 2.1
	Author: Foxskav
	Author URI: foxskav.pl
	License: GPL-2.0+
	License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	*/
	if (!function_exists('write_log')) {
		function write_log ( $log )  {
			if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
					} else {
					error_log( $log );
				}
			}
		}
	}
	
define( 'FMS2018_SRC', plugin_dir_path( __FILE__ ) );
require FMS2018_SRC.'fms2018_admin.php';

function fms2018_install() {
		 global $wpdb;
$prefix = $wpdb->prefix;
$query = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."fms2018_mecze (
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `id_p1` int(11) DEFAULT NULL,
  `id_p2` int(11) DEFAULT NULL,
  `w_p1` int(11) DEFAULT NULL,
  `w_p2` int(11) DEFAULT NULL,
  `czas` datetime DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `stadion` int(11) DEFAULT NULL,
  `faza` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
$wpdb->query($query);

$query = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."fms2018_fazy (
  `id` int(9) NOT NULL,
  `name` varchar(250) NOT NULL,
  `name_eng` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;";
$wpdb->query($query);

$query = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'fms2018_panstwa ( 
        id int(9) NOT NULL, 
        name varchar(250) NOT NULL, 
		name_eng varchar(250) NOT NULL,
        PRIMARY KEY  (id)
        );';
$wpdb->query($query);

$query = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'fms2018_stadiony ( 
  `id` int(9) NOT NULL,
  `miasto` varchar(250) NOT NULL,
  `stadion` varchar(255) DEFAULT NULL,
  `miasto_eng` varchar(255) DEFAULT NULL,
  `stadion_eng` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`));';
$wpdb->query($query);

$query ="CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."fms2018_typy (

  `id_meczu` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `w_p1` int(11) DEFAULT NULL,
  `w_p2` int(11) DEFAULT NULL,
  UNIQUE KEY `compos_typy` (`id_meczu`,`id_user`));";
$wpdb->query($query);
// import panstw i meczy
if (!get_option('fms2018_installed')) { 
$query= "INSERT INTO ".$wpdb->prefix."fms2018_panstwa (id, name, name_eng) 
		VALUES 
	(1, 'Anglia', 'England'),
	(2, 'Arabia Saudyjska', 'Saudi Arabia'),
	(3, 'Argentyna', 'Argentina'),
	(4, 'Australia', 'Australia'),
	(5, 'Belgia', 'Belgium'),
	(6, 'Brazylia', 'Brazil'),
	(7, 'Chorwacja', 'Croatia'),
	(8, 'Dania', 'Denmark'),
	(9, 'Egipt', 'Egypt'),
	(10, 'Francja', 'France'),
	(11, 'Hiszpania', 'Spain'),
	(12, 'Iran', 'Iran'),
	(13, 'Islandia', 'Iceland'),
	(14, 'Japonia', 'Japan'),
	(15, 'Kolumbia', 'Columbia'),
	(16, 'Korea Południowa', 'South Korea'),
	(17, 'Kostaryka', 'Costa Rica'),
	(18, 'Maroko', 'Morocco'),
	(19, 'Meksyk', 'Mexico'),
	(20, 'Niemcy', 'Germany'),
	(21, 'Nigeria', 'Nigeria'),
	(22, 'Panama', 'Panama'),
	(23, 'Peru', 'Peru'),
	(24, 'Polska', 'Poland'),
	(25, 'Portugalia', 'Portugal'),
	(26, 'Rosja', 'Russia'),
	(27, 'Senegal', 'Senegal'),
	(28, 'Serbia', 'Serbia'),
	(29, 'Szwajcaria', 'Switzerland'),
	(30, 'Szwecja', 'Sweden'),
	(31, 'Tunezja', 'Tunisia'),
	(32, 'Urugwaj', 'Uruguay'),
	(33, 'nieznane', 'unknown');";
$wpdb->query($query);
$query = "INSERT INTO ".$wpdb->prefix."fms2018_stadiony (`id`, `miasto`, `stadion`, `miasto_eng`, `stadion_eng`) VALUES
	(1, 'Jekaterynburg', 'Stadion Centralny', 'Yekaterinburg', 'Central Stadium'),
	(2, 'Kaliningrad', 'Stadion Kaliningrad', 'Kaliningrad', 'Kaliningrad Stadium'),
	(3, 'Kazań', 'Kazań Arena', 'Kazan', 'Kazan Arena'),
	(4, 'Moskwa ', 'Stadion Łużniki', 'Moscow', 'Luzhniki Stadium'),
	(5, 'Moskwa', 'Otkrytije Arena', 'Moscow', 'Otkritie Arena'),
	(6, 'Niżny Nowogród', 'Stadion Striełka', 'Nizhny Novgorod', 'Nizhny Novgorod Stadium'),
	(7, 'Petersburg', 'Stadion Kriestowskij', 'Saint Petersburg', 'Krestovsky Stadium'),
	(8, 'Rostów nad Donem', 'Rostow Arena', 'Rostov-on-Don', 'Rostov Arena'),
	(9, 'Samara', 'Samara Arena', 'Samara', 'Cosmos Arena'),
	(10, 'Sarańsk', 'Mordowija Ariena', 'Saransk', 'Mordovia Arena'),
	(11, 'Soczi', 'Stadion Olimpijski', 'Sochi', 'Fisht Olympic Stadium'),
	(12, 'Wołgograd', 'Wołgograd Arena', 'Volgograd', 'Volgograd Arena');";
$wpdb->query($query);
$query = "INSERT INTO ".$wpdb->prefix."fms2018_fazy (`id`, `name`, `name_eng`) VALUES
	(1, 'Grupa A', 'Group A'),
	(2, 'Grupa B', 'Group B'),
	(3, 'Grupa C', 'Group C'),
	(4, 'Grupa D', 'Group D'),
	(5, 'Grupa E', 'Group E'),
	(6, 'Grupa F', 'Group F'),
	(7, 'Grupa G', 'Group G'),
	(8, 'Grupa H', 'Group H'),
	(9, '1/8 finału', '1/8 finals'),
	(10, '1/4 finału', '1/4 finals'),
	(11, 'Półfinał', 'Semifinal'),
	(12, 'o 3. miejsce', 'for 3rd place'),
	(13, 'Finał', 'Final');";
$wpdb->query($query);
$query = "INSERT INTO ".$wpdb->prefix."fms2018_mecze (`id`, `id_p1`, `id_p2`, `w_p1`, `w_p2`, `czas`, `winner`, `stadion`, `faza`) VALUES
	(1, 26, 2, NULL, NULL, '2018-06-14 17:00:00', NULL, 4, '1'),
	(2, 9, 32, NULL, NULL, '2018-06-15 14:00:00', NULL, 1, '1'),
	(3, 18, 12, NULL, NULL, '2018-06-15 17:00:00', NULL, 7, '2'),
	(4, 25, 11, NULL, NULL, '2018-06-15 20:00:00', NULL, 11, '2'),
	(5, 10, 4, NULL, NULL, '2018-06-16 12:00:00', NULL, 3, '3'),
	(6, 3, 13, NULL, NULL, '2018-06-16 15:00:00', NULL, 5, '4'),
	(7, 23, 8, NULL, NULL, '2018-06-16 18:00:00', NULL, 10, '3'),
	(8, 7, 21, NULL, NULL, '2018-06-16 21:00:00', NULL, 2, '4'),
	(9, 17, 28, NULL, NULL, '2018-06-17 14:00:00', NULL, 9, '5'),
	(10, 20, 19, NULL, NULL, '2018-06-17 17:00:00', NULL, 4, '6'),
	(11, 6, 29, NULL, NULL, '2018-06-17 20:00:00', NULL, 8, '5'),
	(12, 30, 16, NULL, NULL, '2018-06-18 14:00:00', NULL, 6, '6'),
	(13, 5, 22, NULL, NULL, '2018-06-18 17:00:00', NULL, 11, '7'),
	(14, 31, 1, NULL, NULL, '2018-06-18 20:00:00', NULL, 12, '7'),
	(15, 15, 14, NULL, NULL, '2018-06-19 14:00:00', NULL, 10, '8'),
	(16, 24, 27, NULL, NULL, '2018-06-19 17:00:00', NULL, 5, '8'),
	(17, 26, 9, NULL, NULL, '2018-06-19 20:00:00', NULL, 7, '1'),
	(18, 25, 18, NULL, NULL, '2018-06-20 14:00:00', NULL, 4, '2'),
	(19, 32, 2, NULL, NULL, '2018-06-20 17:00:00', NULL, 8, '1'),
	(20, 12, 11, NULL, NULL, '2018-06-20 20:00:00', NULL, 3, '2'),
	(21, 10, 23, NULL, NULL, '2018-06-21 14:00:00', NULL, 1, '3'),
	(22, 8, 4, NULL, NULL, '2018-06-21 17:00:00', NULL, 9, '3'),
	(23, 3, 7, NULL, NULL, '2018-06-21 20:00:00', NULL, 6, '4'),
	(24, 6, 17, NULL, NULL, '2018-06-22 14:00:00', NULL, 7, '5'),
	(25, 21, 13, NULL, NULL, '2018-06-22 17:00:00', NULL, 12, '4'),
	(26, 28, 29, NULL, NULL, '2018-06-22 20:00:00', NULL, 2, '5'),
	(27, 5, 31, NULL, NULL, '2018-06-23 14:00:00', NULL, 5, '7'),
	(28, 16, 19, NULL, NULL, '2018-06-23 17:00:00', NULL, 8, '6'),
	(29, 20, 30, NULL, NULL, '2018-06-23 20:00:00', NULL, 11, '6'),
	(30, 1, 22, NULL, NULL, '2018-06-24 14:00:00', NULL, 6, '7'),
	(31, 14, 27, NULL, NULL, '2018-06-24 17:00:00', NULL, 1, '8'),
	(32, 24, 15, NULL, NULL, '2018-06-24 20:00:00', NULL, 3, '8'),
	(33, 32, 26, NULL, NULL, '2018-06-25 16:00:00', NULL, 9, '1'),
	(34, 2, 9, NULL, NULL, '2018-06-25 16:00:00', NULL, 12, '1'),
	(35, 12, 25, NULL, NULL, '2018-06-25 20:00:00', NULL, 10, '2'),
	(36, 11, 18, NULL, NULL, '2018-06-25 20:00:00', NULL, 2, '2'),
	(37, 8, 10, NULL, NULL, '2018-06-26 16:00:00', NULL, 4, '3'),
	(38, 4, 23, NULL, NULL, '2018-06-26 16:00:00', NULL, 11, '3'),
	(39, 21, 3, NULL, NULL, '2018-06-26 20:00:00', NULL, 7, '4'),
	(40, 13, 7, NULL, NULL, '2018-06-26 20:00:00', NULL, 8, '4'),
	(41, 16, 20, NULL, NULL, '2018-06-27 16:00:00', NULL, 3, '6'),
	(42, 19, 30, NULL, NULL, '2018-06-27 16:00:00', NULL, 1, '6'),
	(43, 28, 6, NULL, NULL, '2018-06-27 20:00:00', NULL, 5, '5'),
	(44, 29, 17, NULL, NULL, '2018-06-27 20:00:00', NULL, 6, '5'),
	(45, 14, 24, NULL, NULL, '2018-06-28 16:00:00', NULL, 12, '8'),
	(46, 27, 15, NULL, NULL, '2018-06-28 16:00:00', NULL, 9, '8'),
	(47, 1, 5, NULL, NULL, '2018-06-28 20:00:00', NULL, 2, '7'),
	(48, 22, 31, NULL, NULL, '2018-06-28 20:00:00', NULL, 10, '7'),
	(49, 33, 33, NULL, NULL, '2018-06-30 16:00:00', NULL, 3, '9'),
	(50, 33, 33, NULL, NULL, '2018-06-30 20:00:00', NULL, 11, '9'),
	(51, 33, 33, NULL, NULL, '2018-07-01 16:00:00', NULL, 4, '9'),
	(52, 33, 33, NULL, NULL, '2018-07-01 20:00:00', NULL, 6, '9'),
	(53, 33, 33, NULL, NULL, '2018-07-02 16:00:00', NULL, 9, '9'),
	(54, 33, 33, NULL, NULL, '2018-07-02 20:00:00', NULL, 8, '9'),
	(55, 33, 33, NULL, NULL, '2018-07-03 16:00:00', NULL, 7, '9'),
	(56, 33, 33, NULL, NULL, '2018-07-03 20:00:00', NULL, 5, '9'),
	(57, 33, 33, NULL, NULL, '2018-07-06 16:00:00', NULL, 6, '10'),
	(58, 33, 33, NULL, NULL, '2018-07-06 20:00:00', NULL, 3, '10'),
	(59, 33, 33, NULL, NULL, '2018-07-07 16:00:00', NULL, 9, '10'),
	(60, 33, 33, NULL, NULL, '2018-07-07 20:00:00', NULL, 11, '10'),
	(61, 33, 33, NULL, NULL, '2018-07-10 20:00:00', NULL, 7, '11'),
	(62, 33, 33, NULL, NULL, '2018-07-11 20:00:00', NULL, 4, '11'),
	(63, 33, 33, NULL, NULL, '2018-07-14 16:00:00', NULL, 7, '12'),
	(64, 33, 33, NULL, NULL, '2018-07-15 15:00:00', NULL, 4, '13');";
$wpdb->query($query);
}
	add_option( 'fms2018_installed', true );
	add_option( 'fms2018_pktwynik', '3' );
    add_option( 'fms2018_pktrezultat', '1');
	add_option( 'fms2018_lang', '2');	
	add_option( 'fms2018_block', '30');
	add_option( 'fms2018_susers', '1');
	add_option( 'fms2018_susers_role', 'all users');
	add_option( 'fms2018_display_types', '0');
	}	 
register_activation_hook(__FILE__, 'fms2018_install');
	function fms2018_uinstall() {
	delete_option( 'fms2018_installed' );	
	delete_option( 'fms2018_pktwynik' );
	delete_option( 'fms2018_pktrezultat' );
	delete_option( 'fms2018_lang' );
	delete_option( 'fms2018_block' );
	delete_option( 'fms2018_susers');
	delete_option( 'fms2018_susers_role');
	delete_option( 'fms2018_display_types');
	
   global $wpdb;
    $query ="DROP TABLE IF EXISTS ".$wpdb->prefix."fms2018_mecze,".$wpdb->prefix."fms2018_panstwa,".$wpdb->prefix."fms2018_typy,".$wpdb->prefix."fms2018_fazy,.".$wpdb->prefix."fms2018_stadiony";
       $wpdb->query($query);
} 
register_uninstall_hook(__FILE__, 'fms2018_uinstall');

 function fms2018_menu() {
	 $lang = get_option('fms2018_lang');
	if($lang == 1) {
    add_menu_page('MŚ 2018', 'MŚ 2018', 'administrator', 'fms2018', 'fms2018_settings');
    add_submenu_page('fms2018','Mecze', 'Mecze', 'administrator', 'fms2018_mecze', 'fms2018_mecze');
	}
	else {
	 add_menu_page('WC 2018', 'WC 2018', 'administrator', 'fms2018', 'fms2018_settings');
    add_submenu_page('fms2018','Matches', 'Matches', 'administrator', 'fms2018_mecze', 'fms2018_mecze');	
		}
}
require FMS2018_SRC.'fms2018_front.php';

add_action('admin_menu', 'fms2018_menu');
add_action('wp_enqueue_scripts','fms2018_addons');
add_action('admin_enqueue_scripts', 'fms2018_adminaddons');

function fms2018_addons(){
wp_enqueue_style( 'style', plugins_url( 'css/style.css' , __FILE__ ) );
wp_enqueue_script( 'js', plugins_url( 'js/typy.js' , __FILE__ ), array('jquery'));
}

function fms2018_adminaddons(){
wp_enqueue_style( 'style', plugins_url( 'css/admin_style.css' , __FILE__ ) );
}
?>