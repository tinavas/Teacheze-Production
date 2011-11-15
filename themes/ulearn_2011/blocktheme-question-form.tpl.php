<?php
include_once('SimpleValidator.php');

$rules = array(
    'scf_name'=>array(
        'rule'=>'not-empty',
        'error'=>'Please enter your name.',
    ),
    'scf_mail'=>array(
        'rule'=>'mail',
        'error'=>'Please enter a valid email address.',
    ),
    'scf_phone'=>array(
        'rule'=>'not-empty',
        'error'=>'Please enter your phone.',
    ),
    'scf_question'=>array(
        'rule'=>'not-empty',
        'error'=>'Please enter your question.',
    ),
);
$validator = new SimpleValidator($rules);

$scf_errors = array();
if (isset($_POST['scf_name'])) {
    $name = trim(get_magic_quotes_gpc() ? stripslashes($_POST['scf_name']) : $_POST['scf_name']);
    $mail = trim(get_magic_quotes_gpc() ? stripslashes($_POST['scf_mail']) : $_POST['scf_mail']);
    $phone = trim(get_magic_quotes_gpc() ? stripslashes($_POST['scf_phone']) : $_POST['scf_phone']);
    $question = trim(get_magic_quotes_gpc() ? stripslashes($_POST['scf_question']) : $_POST['scf_question']);

    if ($validator->validateRules($_POST)) {
        $mailto = variable_get('site_mail', 'nobody@example.com');
        $headers = "From: no-reply@ulearn.ie\nContent-type: text/html\n";
        $subject = 'A new question asked from your website - ULearn.ie';
        $mailcontent = '
            <strong>Name:</strong> ' . $name . '<br />
            <strong>Email:</strong> ' . $mail . '<br />
            <strong>Phone:</strong> ' . $phone . '<br />
            <strong>Question:</strong><br />' . nl2br($question) . '<br />
        ';
        mail($mailto, $subject, $mailcontent, $headers);
        $scf_success = true;
    } else {
        $scf_errors = $validator->getErrors();
    }
}
?>
<ul class="widgets">
    <li class="widget">
        <h3><?php echo $block->subject; ?></h3>
        <?php // echo $block->content; ?>
        <div class="question-form">
            <?php if (isset($scf_success)) : ?>
                <strong style="color: #FFFFFF;">Your message has been sent successfully!</strong>
            <?php else : ?>
                <?php foreach ($scf_errors as $e) : ?>
                    <strong style="color: red;"><?php echo $e; ?></strong><br />
                <?php endforeach; ?>
                <form id="scf-form" action="" method="post">
                    <span class="label-new">New</span>
                    <div class="row">
                        <label>Name</label>
                        <input type="text" class="field medium" value="" title="" name="scf_name" />
                    </div>
                    <div class="row">
                        <label>Email</label>
                        <input type="text" class="field" value="" title="" name="scf_mail" />
                    </div>
                    <div class="row">
                        <label>Phone</label>
                        <input type="text" class="field" value="" title="" name="scf_phone" />
                    </div>
                    <div class="row">
                        <label>Question</label>
                        <textarea class="field" rows="8" cols="40" name="scf_question"></textarea>
                    </div>
                    <input type="submit" class="btn-ask" value="Ask ULearn Now!" title="Ask ULearn Now!" />
                    <div class="cl">&nbsp;</div>
                </form>
                <?php $validator->buildJS('#scf-form', true, true); ?>
            <?php endif; ?>
        </div>
    </li>
</ul>