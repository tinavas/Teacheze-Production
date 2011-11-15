<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');

drupal_add_css('sites/all/modules/sforms/css/ui.core.css', 'core', 'all');
drupal_add_css('sites/all/modules/sforms/css/ui.theme.css', 'core', 'all');
drupal_add_css('sites/all/modules/sforms/css/ui.datepicker.css', 'core', 'all');

drupal_add_js('sites/all/modules/sforms/js/jquery.ui.core.js', 'core', 'header');
drupal_add_js('sites/all/modules/sforms/js/jquery.ui.datepicker.js', 'core', 'header');

?>

<script type="text/javascript">

$(function() {
		$("#start_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: '2000:<?php echo date('Y');?>'
		});
		
		$("#end_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: '2000:<?php echo date('Y');?>'
		});
	});

</script>

<script type="text/javascript">
function getTransactions(selected)
{
	var c_dept = $('#c_dept').val();
	var url = '<?php echo url('sforms/get_transactions');?>?others=false&all=true&selected='+selected+'&did='+c_dept;			
	$('#sub_transaction_con').load(url);
}
</script>

<form action="" method="post" name="myform">
<fieldset>
<legend>Filter Result</legend>
<ul class="clear-block">
<li>
	<dl class="multiselect">
    	<dd class="a">
        	<div class="form-item">
            Department
            </div>
        	<div class="form-item">
            Transaction
            </div>            
            <div class="form-item">
            Start Date
            </div>
            <div class="form-item">
            End Date
            </div>
            <div class="form-item">
            Published
            </div>            
            <div class="form-item">
            <input type="submit" name="go" style="padding:4px; width:100px; margin:10px 0px 0px 0px;" value="Go" />
            </div>            
        </dd>
        
        <dd class="b">
        	<div class="form-item">
        	<select class="form-select" name="c_dept" id="c_dept" onchange="getTransactions(0);">
            	<option value="all">All</option>
            	<?php echo $dept_options ;?>
            </select>             
			</div>
        	<div class="form-item">
            <div id="sub_transaction_con" class="inline_div">
        	<select class="form-select" name="c_transaction" disabled="disabled" id="c_transaction">
            	<option value="">Please Select Department</option>
            </select>
            </div>
            <?php
			if(!empty($_POST['c_transaction']))
			{
			 ?>
             <script type="text/javascript">
			 getTransactions('<?php echo $_POST['c_transaction'];?>');
			 </script>
             <?php
			}
			?>
			</div>		
            <div class="form-item">
        	<input type="text" name="start_date" readonly="readonly" id="start_date" value="<?php echo (empty($_POST['start_date']))?'2010-08-10':$_POST['start_date'];?>"  />
			</div>
            <div class="form-item">
        	<input type="text" name="end_date" readonly="readonly" id="end_date" value="<?php echo (empty($_POST['end_date']))?date('Y-m-d'):$_POST['end_date'];?>"  />
			</div>
            <div class="form-item">
            <select class="form-select" name="approved" id="approved">
            	<option value="all">All</option>
            	<option value="1">Yes</option>
                <option value="0">No</option>
            </select>          
            </div>            	        	
        </dd>
    </dl>
</li>
</ul>
</fieldset>
</form>

<h1 class="c_head"><?php echo mysql_num_rows($result);?> reports have been added.</h1>

<table>
<tr>
	<th>Title</th>
    <th>City</th>
    <th>Department</th>
    <th>Transaction</th>
    <th>Date</th>
    <th>Published</th>
    <th>Delete</th>
</tr>
<?php
$cnt_r = 0;
while ($row = db_fetch_object($result)) 
{
?>
	<tr class="<?php echo ($cnt_r%2==0)?'odd':'even';?> <?php echo ($row->approved==1)?'published_row':'unpublished_row'; ?>">
    	<td><a href="<?php echo url('admin/sforms/view_detail_dint_have_to_pay');?>?id=<?php echo $row->id;?>"><?php echo $row->c_name;?></a></td>
        <td><?php echo $row->c_city;?></td>
        <td>
        	<?php
				echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);
			?>
        </td>
        <td>
        	<?php
				echo ($row->c_transaction!=0)?$row->trans_name:getOthers($row->others_transaction);
			?>
        </td>
        <td><?php echo getDateFormat($row->created,'F j, Y - H:i');?></td>
        <td>
        	<a href="<?php echo url('admin/sforms/publish_unpublish');?>?type=dinthvtopay&id=<?php echo $row->id;?>&published=<?php echo ($row->approved==0)?'1':'0'; ?>"><strong><?php echo ($row->approved==0)?'No':'Yes'; ?></strong></a>
        </td> 
        <td>
        	<a href="<?php echo url('admin/sforms/delete_bribe');?>?type=dinthvtopay&id=<?php echo $row->id;?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
        </td>       
    </tr>
<?php
$cnt_r++;
}//eof while
?>
</table>