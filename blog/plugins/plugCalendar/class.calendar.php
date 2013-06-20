<?php 
/**
 * Class calendar
 *
 * @version	0.1
 * @date	03/05/2012
 * @author	Cyril MAGUIRE
 **/
 
class Calendar {
	
	public $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	public $months = array('1'=>'Janvier', '2'=>'Février', '3'=>'Mars', '4'=>'Avril', '5'=>'Mai', '6'=>'Juin', '7'=>'Juillet', '8'=>'Août', '9'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');
		
	/**
	 * Fill an array with all months and days for one year
	 * @param integer $year The year to display
	 * @return array $r
	 */
	function getAll($year) {
		$r = array();
		
		if (!class_exists('DateTime') || (class_exists('DateTime') && !method_exists('DateTime','add'))) {
			$date = strtotime($year.'-01-01');
			while(date('Y',$date) <= $year) {
				$y = date('Y',$date);
				$m = date('n',$date);
				$d = date('j',$date);
				$w = str_replace('0','7',date('w',$date));
				$r[$y][$m][$d] = $w;
				$date = strtotime(date('Y-m-d',$date).' +1 DAY');
			}
		} else {
			$date = new DateTime($year.'-01-01');
			while($date->format('Y') <= $year) {
				$y = $date->format('Y');
				$m = $date->format('n');
				$d = $date->format('j');
				$w = str_replace('0','7',$date->format('w'));
				$r[$y][$m][$d] = $w;
				$date->add(new DateInterval('P1D'));
			}
		}
		return $r;
	}
	
	/**
	 * Select a specific month for a specific year
	 * @param integer $year The year to display
	 * @param integer $month The month to display
	 * @return array $y
	 */
	function getMonth($year,$month) {
		$y = current($this->getAll($year));
		return $y[$month];
	}
	
	
	/**
	 * Display calendar
	 * @param integer $year The year to display
	 * @param integer $month The month to display
	 * @param optionnal integer $nbMonth Number of months to display
	 * @param optionnal array $links All the days which contain published articles. $links must be as follow :
	 * $links = array(
	        'year' => array(
	            'date' => array(
	                 'month' => integer,
	                 'day' => integer,
	                 'url' => string,
	             );
	        );
	  );
	 * @return string
	 */
	function display($year,$month,$nbMonths = 1,$links = array()) {
		$mRef = $month;
		
		ob_start();
		?>
		
		<div class="calendar">
		<?php
		for ($i=0;$i<$nbMonths;$i++):
		$month = $mRef+$i;
		$m = $this->getMonth($year,$month);
		?>
		<table id="wp-calendar">
			<caption>
			<span class="month"><?php echo $this->months[$month];?></span>
			<span class="year" id="y_<?php echo $year?>"><?php echo $year;?></span>
			</caption>
				<thead>
					<tr>
						<?php foreach($this->days as $d) {
						echo '
							<th scope="col" >'.substr($d,0,3).'</th>
							';
						}?>
						
					</tr>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<tr>
						<?php foreach($m as $day=>$weekDay):
						$end = end($m);
						$Month = (strlen($month) == 1 ? '0'.$month : $month);
						$Day = (strlen($day) == 1 ? '0'.$day : $day);
						
						if ($day == 1) {
						echo '
							<td colspan="'.($weekDay-1).'" class="merge"></td>
							';
						}
						if (isset($links[$year][$year.$Month.$Day])) {
							$art = $links[$year][$year.$Month.$Day];
								if ($art['month'] == $Month && $art['day'] == $Day){
						echo ' 
							<td '.(($month == date('n') && $day == date('d')) ? ' id="today"' : '').'><a href="'.$art['url'].'" title="'.$art['title'].'">'.$day.'</a></td>
							';
								}else {
						echo ' 
							<td '.(($month == date('n') && $day == date('d')) ? ' id="today"' : '').'>'.$day.'</td>
							';
								}
						}else {
						echo ' 
							<td '.(($month == date('n') && $day == date('d')) ? ' id="today"' : '').'>'.$day.'</td>
							';
						}
						
						if ($weekDay == 7) {
						echo '
					</tr>
					<tr>
						';
						}
						endforeach;
						
						if ($end != 7){
						echo '
							<td colspan="'.(7-$end).'" class="merge"></td>
						';
						}
						
						?>
						
					</tr>
				</tbody>
		</table>
		<?php endfor;?>
	
		</div>
		<?php
		$cal = ob_get_contents();
		ob_end_clean();
		
		return $cal;
	}
	
}
?>