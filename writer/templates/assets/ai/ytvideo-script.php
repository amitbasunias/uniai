<?php
if (isset($_POST["submit"])) {
	
    function openAI() {
        //Set Open Ai API KEY
        require ("config.php");

        //Set Prompt for AI
		$usertitle = $_POST["usertitle"];
        $usertext = $_POST["userprompt"];
		$prompt = "Generate a sales copy to a customer for this product using these details: ".$usertitle." ".$usertext.".";

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
				$templatestyle = " including Greetings, Attention, Interest, Desire, and Action";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "2") {
				$templatestyle = " including Greetings, Problem, Agitate, Solution, and Action";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "3") {
				$templatestyle = " including Greetings, Before, After, Bridge, and Action";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "4") {
				$templatestyle = " including Greetings, Attention, Interest, Desire, Conviction, and Action";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "5") {
				$templatestyle = " including Greetings, Problem, Advantages, Proof, and Action";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "6") {
				$templatestyle = " including Greetings, Attention, Interest, Description, Persuasion, Proof, and Close";
				$templatetext = "Greetings:\r\n";
			}elseif ($template == "7") {
				$templatestyle = " including Greetings, Attention, Interest, Credibility, Proof, Benefits, Scarcity, Action, Warn, and Now";
				$templatetext = "Greetings:\r\n";
			}else{
				$templatestyle = "";
				$templatetext = "YouTube Video Script:\r\n";
			}
			
			$prompt = "Write a detailed, very long YouTube Video Script".$templatestyle." using these details:"."\r\n"."Product or Brand: ".$usertitle."\r\n"."About:".$usertext."\r\n".$tone.$templatetext;
			
			$data = [
				"model" => $aimodel,
				"prompt" => $prompt,
				"temperature" => $creativity,
				"max_tokens" => 600,
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
		echo "<p><strong>YouTube Video Script #1</strong></p>".aiContent()."<br/><br/>";
		echo "<p><strong>YouTube Video Script #2</strong></p>".aiContent()."<br/><br/>";
		echo "<p><strong>YouTube Video Script #3</strong></p>".aiContent();
	}
	
} else {
    echo '<p style="color: red;">Oops! There was a problem generating copy. Please try again.</p>';
}
?>