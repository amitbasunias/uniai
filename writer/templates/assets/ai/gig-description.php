<?php
if (isset($_POST["submit"])) {
	
    function openAI() {
        //Set Open Ai API KEY
        require ("config.php");

        //Set Prompt for AI
		$usertitle = $_POST["usertitle"];
		$usertext = $_POST["userprompt"];
		$usertone = $_POST["tone"];
		$prompt = $usertone." ".$usertitle." ".$usertext;

        $data = [
            "input" => $prompt,
        ];

        $post_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/moderations");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);

        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer $OPENAI_API_KEY";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //Decode JSON and get text only
        $gettext = json_decode($result, true);
        $textoutput = $gettext["results"][0]["flagged"];

		return $textoutput;
        curl_close($ch);		
    }
	
	//Show AI output
	$output = openAI();
	
	//If Not Safe
	if ($output == "1") {
		echo '<p style="color:red;">⚠️<strong>Unsafe content detected.</strong> <br><br>Our content filter has detected that your input may contain toxic language or sensitive topics.</p>';
	} else {//Else Safe
		function aiContent() {
			//Set Open Ai API KEY
			require ("config.php");
	
			//Set Prompt for AI
			$usertitle = $_POST["usertitle"];
			$usertext = $_POST["userprompt"];
			$usertone = $_POST["tone"];
			$template = $_POST["template"];
			$creativeness = $_POST["creativity"];
			
			//Select Tone
			require ("tone.php");
			require ("creativity.php");
			
			//Select template
			if ($template == "1") {
				$templatestyle = " based on AIDA(Attention, Interest, Desire, and Action) method";
				$templatetext = "<br><b>Attention:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "2") {
				$templatestyle = " based on PAS(Problem, Agitate, Solution, and Action) method";
				$templatetext = "<br><b>Problem:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "3") {
				$templatestyle = " based on BAB(Before, After, Bridge, and Action) method";
				$templatetext = "<br><b>Before:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "4") {
				$templatestyle = " based on AIDCA(Attention, Interest, Desire, Conviction, and Action) method";
				$templatetext = "<br><b>Attention:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "5") {
				$templatestyle = " based on PAPA(Problem, Advantages, Proof, and Action) method";
				$templatetext = "<br><b>Problem:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "6") {
				$templatestyle = " based on AIDPPC(Attention, Interest, Description, Persuasion, Proof, and Close) method";
				$templatetext = "<br><b>Attention:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}elseif ($template == "7") {
				$templatestyle = " based on AICPBSAWN(Attention, Interest, Credibility, Proof, Benefits, Scarcity, Action, Warn, and Now) method";
				$templatetext = "<br><b>Attention:</b>\r\n";
				$outtxt = "";
				$outlines = "";
			}else{
				$templatestyle = "";
				$templatetext = "";
				$outtxt = " and outlines";
				$outlines = "Outlines: Description, Why choose me?, What’s included:, Contact\r\n";
			}
			
			$prompt = "Write detailed description about My Fiverr Gig using these details".$outtxt.$templatestyle.":\r\n"."My Fiverr Gig: I will ".$usertitle."\r\n"."Keywords:".$usertext.".\r\n".$tone.$outlines.$templatetext;
		
			$data = [
				"model" => $aimodel,
				"prompt" => $prompt,
				"temperature" => $creativity,
				"max_tokens" => 300,
				"top_p" => 1,
				"frequency_penalty" => 0,
				"presence_penalty" => 0,
			];
			
			$post_json = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
			
			$headers = [];
			$headers[] = "Content-Type: application/json";
			$headers[] = "Authorization: Bearer $OPENAI_API_KEY";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$result = curl_exec($ch);
			
			//Decode JSON and get text only
			$gettext = json_decode($result, true);
			$aioutput = $gettext["choices"][0]["text"];
			return $aioutput;
			
			curl_close($ch);
		}
				
		//Select template
		$template = $_POST["template"];
		if ($template == "1") {
			$templatetext = "Attention:";
		}elseif ($template == "2") {
			$templatetext = "Problem:";
		}elseif ($template == "3") {
			$templatetext = "Before:";
		}elseif ($template == "4") {
			$templatetext = "Attention:";
		}elseif ($template == "5") {
			$templatetext = "Problem:";
		}elseif ($template == "6") {
			$templatetext = "Attention:";
		}elseif ($template == "7") {
			$templatetext = "Attention:";
		}else{
			$templatetext = "Description:";
		}
		echo '<p><strong>'.$templatetext.'</strong></p>'.aiContent();
	}
	
} else {
    echo '<p style="color: red;">Oops! There was a problem generating copy. Please try again.</p>';
}
?>