<?php
require_once('config.php');
//create users
require_once('functions.php');
try {
	
	switch ($_REQUEST['Service'])
	{
		case "get_history":
		
			if(isset($_REQUEST['user_id']))
			{
				$user_id = $_REQUEST['user_id'];

				$que_answer_data = array();
						
				//get question ans history for user
				$args = array(
					'user_id' => $user_id, // use user_id
					'parent'  => 0,
					'post_type' => 'services',
					'orderby' => 'comment_date',
					'order' => 'ASC',
				);
				
				$questions_data = array();
				
				
				
				$questions = get_comments($args);
				
				
				$data_questions = array();
				foreach($questions as $question){
					$temp_question_data = array();
					$temp_question_data['question_id'] = $question->comment_ID;
					$temp_question_data['service_id'] = $question->comment_post_ID;
					$temp_question_data['question'] = $question->comment_content;
					$temp_question_data['date'] = $question->comment_date;
					
					$args = array(
						'parent'  => $question->comment_ID,
						'orderby' => 'comment_date',
						'order' => 'ASC',
					);
					
					$answers = get_comments($args);
					
					foreach($answers as $answer){
						$temp_answer_data = array();
						$temp_answer_data['answer_id'] = $answer->comment_ID;
						$temp_answer_data['service_id'] = $answer->comment_post_ID;
						$temp_answer_data['answer'] = $answer->comment_content;
						$temp_answer_data['date'] = $answer->comment_date;
						
						$temp_question_data['answers'][] = $temp_answer_data;
					}

					$data_questions[] = $temp_question_data;
				}
				
				$status = 1;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Sign in sucessfully";
				$data['que_ans_history'] = $data_questions;
				echo json_encode($data);
				die;			
				
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Please provide valid details";
				echo json_encode($data);
				die;
			}
		
		break;
		
		case "get_ques_history_sp":
			if(isset($_REQUEST['user_id']))
			{
				$user_id = $_POST['user_id'];

				$args = array( 'post_type' => 'services', 'posts_per_page' => -1 ,'post_status' => array( 'pending', 'publish' ),'author' => $user_id );
				
				query_posts($args);

				global $post;

				$data_questions = array();
				while (have_posts()) : the_post();
					$postdata = $post;
					$post_id=$postdata->ID;
					
					//get question ans history for service provider
					$args = array(
						'post_id' => $post_id,
						'parent'  => 0,
						'orderby' => 'comment_date',
						'order' => 'ASC',
					);
					
					$questions_data = array();
					
					$questions = get_comments($args);

					foreach($questions as $question){
						$temp_question_data = array();
						$temp_question_data['question_id'] = $question->comment_ID;
						$temp_question_data['service_id'] = $question->comment_post_ID;
						$temp_question_data['question'] = $question->comment_content;
						$temp_question_data['date'] = $question->comment_date;
						
						$args = array(
							'parent'  => $question->comment_ID,
							'orderby' => 'comment_date',
							'order' => 'ASC',
						);
						
						$answers = get_comments($args);
						
						foreach($answers as $answer){
							$temp_answer_data = array();
							$temp_answer_data['answer_id'] = $answer->comment_ID;
							$temp_answer_data['service_id'] = $answer->comment_post_ID;
							$temp_answer_data['answer'] = $answer->comment_content;
							$temp_answer_data['date'] = $answer->comment_date;
							
							$temp_question_data['answers'][] = $temp_answer_data;
						}
						
						
						$data_questions[] = $temp_question_data;
						
					}
				
				endwhile;

				// Reset Query
				wp_reset_query();
						
				$status = 1;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Sign in sucessfully";
				$data['que_ans_history'] = $data_questions;
				echo json_encode($data);
				die;			
				
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Please provide valid details";
				echo json_encode($data);
				die;
			}
		
		break;
		
		case "get_service_ques":
		
			if(isset($_REQUEST['service_id']))
			{
				$post_id = $_REQUEST['service_id'];

				$que_answer_data = array();
						
				//get question ans history for user
				$args = array(
					'post_id' => $post_id, // use user_id
					'parent'  => 0,
					'orderby' => 'comment_date',
					'order' => 'ASC',
				);
				
				$questions_data = array();
				
				$questions = get_comments($args);
				
				$data_questions = array();
				foreach($questions as $question){
					$temp_question_data = array();
					$temp_question_data['question_id'] = $question->comment_ID;
					$temp_question_data['service_id'] = $question->comment_post_ID;
					$temp_question_data['question'] = $question->comment_content;
					$temp_question_data['date'] = $question->comment_date;
					
					$args = array(
						'parent'  => $question->comment_ID,
						'orderby' => 'comment_date',
						'order' => 'ASC',
					);
					
					$answers = get_comments($args);
					
					foreach($answers as $answer){
						$temp_answer_data = array();
						$temp_answer_data['answer_id'] = $answer->comment_ID;
						$temp_answer_data['service_id'] = $answer->comment_post_ID;
						$temp_answer_data['answer'] = $answer->comment_content;
						$temp_answer_data['date'] = $answer->comment_date;
						
						$temp_question_data['answers'][] = $temp_answer_data;
					}
					
					
					$data_questions[] = $temp_question_data;
					
				}
						
				$status = 1;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Sign in sucessfully";
				$data['que_ans_history'] = $data_questions;
				echo json_encode($data);
				die;			
				
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Please provide valid details";
				echo json_encode($data);
				die;
			}
		
		break;
		case "submit_answer":
		
			if(isset($_REQUEST['question_id']) && $_REQUEST['question_id'] != "" && isset($_REQUEST['service_id']) && $_REQUEST['service_id'] != "" && isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != "" && isset($_REQUEST['answer']) && $_REQUEST['answer'] != ""  )
			{
				$question_id = $_REQUEST['question_id'];
				$service_id = $_REQUEST['service_id'];
				$user_id = $_REQUEST['user_id'];
				$answer = $_REQUEST['answer'];
				
				$user = get_user_by( 'id', $user_id );
				$email = $user->user_email;
				//$email = "vishal.gandhi71.86@gmail.com";
				$nicename = $user->display_name;

				$commentdata = array(
					'comment_post_ID' => $service_id, // to which post the comment will show up
					'comment_author' => $nicename, //fixed value - can be dynamic 
					'comment_author_email' => $email, //fixed value - can be dynamic 
					'comment_author_url' => '', //fixed value - can be dynamic 
					'comment_content' => $answer, //fixed value - can be dynamic 
					'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
					'comment_parent' => $question_id, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
					'user_id' => $user_id, //passing current user ID or any predefined as per the demand
				);

				//Insert new comment and get the comment ID
				$comment_id = wp_new_comment( $commentdata );
				
				
				
				
				$status = 1;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Answer submitted sucessfully";
				echo json_encode($data);
				die;
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Please provide valid details";
				echo json_encode($data);
				die;
			}
			
			
		break;
		case "create_question":
		
			if(isset($_REQUEST['service_id']) && $_REQUEST['service_id'] != "" && isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != "" && isset($_REQUEST['question']) && $_REQUEST['question'] != ""  )
			{
				$service_id = $_REQUEST['service_id'];
				$user_id = $_REQUEST['user_id'];
				$question = $_REQUEST['question'];
				
				$user = get_user_by( 'id', $user_id );
				$email = $user->user_email;
				//$email = "vishal.gandhi71.86@gmail.com";
				$nicename = $user->display_name;

				$commentdata = array(
					'comment_post_ID' => $service_id, // to which post the comment will show up
					'comment_author' => $nicename, //fixed value - can be dynamic 
					'comment_author_email' => $email, //fixed value - can be dynamic 
					'comment_author_url' => '', //fixed value - can be dynamic 
					'comment_content' => $question, //fixed value - can be dynamic 
					'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
					'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
					'user_id' => $user_id, //passing current user ID or any predefined as per the demand
				);

				//Insert new comment and get the comment ID
				$comment_id = wp_new_comment( $commentdata );
				
				
				
				
				$status = 1;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Question submitted sucessfully";
				echo json_encode($data);
				die;
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Please provide valid details";
				echo json_encode($data);
				die;
			}
			
			
		break;
		default:
		{
			$status = 2;
			$data['status'] = ($status>1)? "fail" : "success";
			$data['message'] = "No service found";
			echo json_encode($data);
			die;
		}
		break;
	}
} catch (Exception $e) {
	$status = 2;
	$data['status'] = ($status>1)? "fail" : "success";
	$data['message'] = "Check network connection and try again...";
	echo json_encode($data);
	die;
}
