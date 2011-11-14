<?php
	/**
	 * @author Constantin Boiangiu
	 * @link http://www.php-help.ro
	 * 
	 * This script is provided as-is, with no guarantees.
	 */
	
	/* 
		if you set the session in some configuration or initialization file with
		session id, delete session_start and make a require('init_file.php');
	*/
	//session_start();
	
	/*===============================================================
		General captcha settings
	  ===============================================================*/
	// captcha width
	$captcha_w = 150;
	// captcha height
	$captcha_h = 50;
	// minimum font size; each operation element changes size
	$min_font_size = 24;
	// maximum font size
	$max_font_size = 27;
	// rotation angle
	$angle = 15;
	// background grid size
	$bg_size = 10;
	// path to font - needed to display the operation elements
	$font_path = dirname(__FILE__).'/fonts/CASTELAR.TTF';
	// array of possible operators
	$operators=array('+','-','*');
	// first number random value; keep it lower than $second_num
	$first_num = rand(1,5);
	// second number random value
	$second_num = rand(6,11);
	
	$text_data = rand(1000,9999);
		
	/*===============================================================
		From here on you may leave the code intact unless you want
		or need to make it specific changes. 
	  ===============================================================*/
	
	shuffle($operators);
	$expression = $second_num.$operators[0].$first_num;
	
	//by dharam as we need to use text now
	
	$expression = $text_data;
	
	/*
		operation result is stored in $session_var
	*/
	//eval("\$session_var=".$second_num.$operators[0].$first_num.";");
	$session_var = $text_data;
	/* 
		save the operation result in session to make verifications
	*/
	
	//default declaration
	$_SESSION['security_number'] = $session_var;
	
	//this declaration is in use
	//$_SESSION['securimage_code_value'] = $session_var;
	
	//set using drupal's way
	//sess_write('sec_code_val',$session_var);
	
	sess_mc_write('sec_code_val',$session_var);
	/*
		start the captcha image
	*/
	$img = imagecreate( $captcha_w, $captcha_h );
	/*
		Some colors. Text is $black, background is $white, grid is $grey
	*/
	$black = imagecolorallocate($img,0,0,0);
	$white = imagecolorallocate($img,255,255,255);
	$grey = imagecolorallocate($img,215,215,215);
	/*
		make the background white
	*/
	imagefill( $img, 0, 0, $white );	
	/* the background grid lines - vertical lines */
	for ($t = $bg_size; $t<$captcha_w; $t+=$bg_size){
		imageline($img, $t, 0, $t, $captcha_h, $grey);
	}
	/* background grid - horizontal lines */
	for ($t = $bg_size; $t<$captcha_h; $t+=$bg_size){
		imageline($img, 0, $t, $captcha_w, $t, $grey);
	}
	
	/* 
		this determinates the available space for each operation element 
		it's used to position each element on the image so that they don't overlap
	*/
	$item_space = $captcha_w/3;

	imagettftext(
		$img,
		$min_font_size,
		15,
		40,
		45,
		$black,
		$font_path,
		$text_data);
	
	
	
	/*imagettftext(
		$img,
		rand(
			$min_font_size,
			$max_font_size
		),
		rand( -$angle , $angle ),
		rand( 10, $item_space-20 ),
		rand( 25, $captcha_h-25 ),
		$black,
		$font_path,
		$second_num);
	
	
	imagettftext(
		$img,
		rand(
			$min_font_size,
			$max_font_size
		),
		rand( -$angle, $angle ),
		rand( $item_space, 2*$item_space-20 ),
		rand( 25, $captcha_h-25 ),
		$black,
		$font_path,
		$operators[0]);
	
	
	imagettftext(
		$img,
		rand(
			$min_font_size,
			$max_font_size
		),
		rand( -$angle, $angle ),
		rand( 2*$item_space, 3*$item_space-20),
		rand( 25, $captcha_h-25 ),
		$black,
		$font_path,
		$first_num);*/
		
	/* image is .jpg */
	header("Content-type:image/jpeg");
	/* name is secure.jpg */
	header("Content-Disposition:inline ; filename=secure.jpg");
	/* output image */
	imagejpeg($img);