<html>
<body>
	<div>FARGO ASSEMBLY OF PA</br>
	800 W.WASHINGTON ST. NORRISTOWN, PA</br>
	VENDOR CORRECTIVE ACTION REPORT</div>
	<table>
		<tr>
			<td>Supplier<br/>$supplier</td>
			<td>Plant<br/>$plant</td>
			<td>Fargo Part Number<br/>$f_part_no</td>
			<td>Rev<br/>$rev</td>
		</tr>
		<tr>
			<td>Vendor Part Number<br/>$v_part_no</td>
			<td>Order Date<br/>$order_date</td>
			<td>Description<br/>$desc</td>
			<td>Serial Number<br/>$serial</td>
		</tr>
		<tr>
			<td>Price Per Unit<br/>$price</td>
			<td>Buyer<br/>$buyer</td>
			<td>Inspection Level<br>$level</td>
			<td>P.O. Number<br/>$po_num</td>
		</tr>
		<tr>
			<td>Qty Received<br/>$qty_rec</td>
			<td>Qty Rejected<br/>$qty_rejected</td>
			<td>Account Number<br/>$account_no</td>
			<td>Date<br/>$date</td>
		</tr>
		<tr>
			<td colspan = 4>Description of Nonconformance<br/><p>$non_con</p></td>
		</tr>
		<tr>
			<td colspan = 4>Root Cause(Analysis Results)<br/><p>$root_cause</p></td>
		</tr>
		<tr>
			<td colspan = 4>
				<table>
					<tr>
						<td>Disposition</td>
						<td>Quantity</td>
						<td>Actionee</td>
					</tr>
					<tr>
						<td rowspan = 2>Engineering Change Order<br/>Supplier Corrective Action Request<br/>Other(Describe Below)<br/>None Required</td>
						<td rowspan = 2>$repair Repair<br/>$rework Rework To Dwg<br/>$use Use As Is<br/>$scrap Scrap<br/>$return Return To FVendor<br/>$replace Replacement Required</td>
						<td>$actionee</td>
					</tr>
					<tr>
						<td colspan = 2></td>
						<td>Due Date<br/>$due_date</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan = 4>Corrective/Preventive Action<br/><p>$corrective_action</p></td>
		</tr>
	</table>		
</			