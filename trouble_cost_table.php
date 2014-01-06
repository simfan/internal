<?php

	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database');
	}
	
	$sort_by = $_POST['primarySearch'];
	$part_num = $_POST['partNum'];
	$material_part = $_POST['materialPart'];
	
	$field_count = 0;
	$query_start = "SELECT * FROM itr ";
	$query_end = '';
		
	if ($part_num != '')
	{
		$query_mid = "WHERE part_no = '" . $part_num . "' ";
		$field_count++;
	}
	if ($material_part != '')
	{
		if($field_count == 0)
			$query_mid .= "WHERE ";
		else
		{
			if ($sort_by != 'materialPart')
			{
				$query_mid .= "AND (";
				$query_end = ")";
			}
		}
		$query_mid .= "	cost_pn1 = '" . $material_part . "' OR cost_pn2 = '" . $material_part . "' OR cost_pn3 = '" . $material_part . "' OR cost_pn4 = '" . $material_part . "'" . $query_end;
	}
	
	if ($sort_by == 'partNumber')
		$query_mid .= " ORDER BY part_no, itr_no";
	
		
	if ($sort_by == 'generalSort')
		$query_mid .= " ORDER BY itr_no";
	$query = $query_start . $query_mid . $query_end;
	$result = pg_query($conn, $query) or die("Error in query: $query " . pg_last_error($conn));
	$rows = pg_num_rows($result);
?>
<html>
<body>
	<table>
		<tr>
			<th>ITR</th>
			<th>Part Number</th>
			<th>Material Part 1</th>
			<th>Cost Per Piece</th>
			<th>Qty</th>
			<th>Material Total Cost</th>
			<th>Material Part 2</th>
			<th>Cost Per Piece</th>
			<th>Qty</th>
			<th>Material Total Cost</th>
			<th>Material Part 3</th>
			<th>Cost Per Piece</th>
			<th>Qty</th>
			<th>Material Total Cost</th>
			<th>Material Part 4</th>
			<th>Cost Per Piece</th>
			<th>Qty</th>
			<th>Material Total Cost</th>
			<th>Trouble Report Total Cost</th>
		</tr>
	<?php		
	$j = 0;
	for($i = 0; $i < $rows; $i++)
	{
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$cpn_class = 'cpn';
		$cost_class = 'cost';
		$qty_class = 'qty';
		$total_class = 'total';
		if ($i == ($rows-1))
		{
			$cpn_class .= ' bottom';
			$cost_class .= ' bottom';
			$qty_class .= ' bottom';
			$total_class .= ' bottom';
		}
		$itr = pg_fetch_array($result);
		$itr_no = $itr['itr_no'];
		$part = $itr['part_no'];
		$cost_pn1 = $itr['cost_pn1'];
		$cost1 = $itr['cost1'];
		$cost_qty1 = $itr['cost_qty1'];
		if($cost1 != '' && $cost_qty1 != '')
		{
			$total1 = round($cost1 * $cost_qty1, 2);
			$total1 = number_format($total1, 2);
			
		}
		$cost_pn2 = $itr['cost_pn2'];
		$cost2 = $itr['cost2'];
		$cost_qty2 = $itr['cost_qty2'];
		if($cost2 != '' && $cost_qty2 != '')
		{
			$total2 = round($cost2 * $cost_qty2, 2);	
			$total2 = number_format($total2, 2);
		}
		else 
			$total2 = "&nbsp";
		$cost_pn3 = $itr['cost_pn3'];
		$cost3 = $itr['cost3'];
		$cost_qty3 = $itr['cost_qty3'];	
		if($cost3 != '' && $cost_qty3 != '')
		{
			$total3 = round($cost3 * $cost_qty3, 2);
			$total3 = number_format($total3, 2);	
		}
		else 
			$total3 = "&nbsp";				
		$cost_pn4 = $itr['cost_pn4'];
		$cost4 = $itr['cost4'];
		$cost_qty4 = $itr['cost_qty4'];	
		if($cost4 != '' && $cost_qty4 != '')
		{
			$total4 = round($cost4 * $cost_qty4, 2);
			$total4 = number_format($total4, 2);	
		}
		else 
			$total4 = "&nbsp";			
		$itr_total = $total1 + $total2 + $total3 + $total4;
		$itr_total = number_format($itr_total, 2);
		$grand_total += $itr_total;
		$grand_total = number_format($grand_total, 2);
		if($cost1 != '')
			print "<tr><td>$itr_no</td><td>$part</td><td class = '" . $cpn_class . "'>$cost_pn1</td><td class = '" . $cost_class . "' align = 'right'>$cost1</td><td class = '" . $qty_class . "' align = 'right'>$cost_qty1</td><td class = '" . $total_class . "' align = 'right'>$total1</td><td>$cost_pn2</td><td align = 'right'>$cost2</td><td align = 'right'>$cost_qty2</td><td align = 'right'>$total2</td><td>$cost_pn3</td><td align = 'right'>$cost3</td><td align = 'right'>$cost_qty3</td><td align = 'right'>$total3</td><td>$cost_pn4</td><td align = 'right'>$cost4</td><td align = 'right'>$cost_qty4</td><td align = 'right'>$total4</td><td align = 'right'>$itr_total</td></tr>";
	}
	print "<tr><td colspan = 18 align = 'right'>Grand Total</td><td align = 'right'>$grand_total</td></tr>";
	
	/*if ($sort_by = "materialPart")
	{
		$query1 = "SELECT (itr_no, cost_pn1, cost1, cost_qty1) FROM itr WHERE cost_pn1 = "' . $material_part . "'"
		*/
	?>
	</table>
</body>
</html>