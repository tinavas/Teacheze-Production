<a href="<?php echo url('admin/ulearn');?>" style="float:right;">Back to Dashboard</a><br clear="right" />
<form action="" method="post" name="settings" id="settings">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td>PayPal Test mode</td>
        <td> 
        	<input type="radio" name="mode" value="1" <?php if ($result->mode == 1) { echo 'checked'; } ?> /> Yes 
        	<input type="radio" name="mode" value="0" <?php if ($result->mode == 0) { echo 'checked'; } ?> /> No 
        </td>
	<tr>
    	<td>PayPal E-mail Address</td>
        <td><input type="text" name="paypal" id="paypal" value="<?php echo isset ($result->paypal) ? $result->paypal : ''; ?>" size="30" /></td>
    </tr>
	<tr>
    	<td>Percentage on each package </td>
        <td><input type="text" name="percent" id="percent" value="<?php echo isset ($result->percent) ? $result->percent : ''; ?>" size="30" /> %</td>
    </tr>
	<tr>
    	<td colspan="2" align="center"><br />
        	<input type="hidden" id="id" name="id" value="<?php echo isset ($result->id) ? $result->id : ''; ?>" />
            <input type="submit" name="submit" class="form-submit art-button" value="Submit" /> &nbsp;
            <input type="reset" name="reset" class="form-submit art-button" value="Reset" /> &nbsp;
            <input type="button" name="back" value="Back" onClick="javascript:history.go(-1);" />
        </td>
    </tr>
</table>
</form>