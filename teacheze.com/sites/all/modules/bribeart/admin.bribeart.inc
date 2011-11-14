<?php
    function list_bribe_arts()   
    {
        if(isset($_POST['asubmit']) && count($_POST['sids']) > 0)
        {
            $sids = implode(',',$_POST['sids']);
            
            $action_d = $_POST['action_d'];
            
            if($action_d == 3)//delete
            {
                $sql_a = "DELETE from bd_bribe_art WHERE id in ($sids) ";
            }
            else if($action_d == 1 || $action_d == 0)//publish - unpublish
            {
                $sql_a = "UPDATE bd_bribe_art SET approved='$action_d' WHERE id in ($sids) ";
            }
            
            if(!empty($sql_a))
            {
                db_query($sql_a);
            }
            
            drupal_set_message('Successfully updated the records.');
        }
    
       $sql = "SELECT * from bd_bribe_art order by date_time desc";
    
       $res =  db_query($sql);  
            
       $num_recs = mysql_num_rows($res);
        
       $queryResult = pager_query(db_rewrite_sql($sql), 10);              
        
       return theme('list_bribe_arts',$queryResult,$num_recs); 
    }
    
    function publish_unpublish_art()
    {
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $approved = $_REQUEST['published'];
        
        if($approved==1)
        $msg_t = 'published';
        else
        $msg_t = 'unpublished';    
        
        if($type=='image')
        {
            $sql = "UPDATE bd_bribe_art SET approved='$approved' WHERE id = '$id'";
            
            $queryResult =  db_query($sql);
            
            drupal_set_message('Successfully <strong>'.$msg_t.'</strong> the record');
            
            drupal_goto('admin/bribeart/list_bribe_arts');        
        }
        
    }
    
    function delete_art()
    {
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        
        if($type=='image')
        {
            $sql = "DELETE FROM bd_bribe_art WHERE id = '$id'";
            
            $queryResult =  db_query($sql);
            
            drupal_set_message('Successfully <strong>deleted</strong> the record');
            
            drupal_goto('admin/bribeart/list_bribe_arts');        
        }
    }
?>