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
		function tagLine() {
			//Set Open Ai API KEY
			require ("config.php");
	
			//Set Prompt for AI
			$usertext = $_POST["userprompt"];
			$creativeness = $_POST["creativity"];
			require ("creativity.php");
			$prompt = "Write a Tagline about a Fiverr seller using these skills. Make sure to write it in 4 Words:"."\r\n"."Skills:Social media management, Social media marketing"."\r\n"."Tagline:Creative and Social Media Expert"."\r\n"."Skills:Social media management, Social media marketing"."\r\n"."Tagline:Social Media Manager"."\r\n"."Skills:Social media marketing, NFT"."\r\n"."Tagline:NFT Marketing Specialist"."\r\n"."Skills:".$usertext."\r\n"."Tagline:\r\n";		
			
			$data = [
				"model" => $aimodel,
				"prompt" => $prompt,
				"temperature" => $creativity,
				"max_tokens" => 10,
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
		
		function aiContent() {
			//Set Open Ai API KEY
			require ("config.php");
	
			//Set Prompt for AI
			$usertitle = $_POST["usertitle"];
			$usertext = $_POST["userprompt"];
			$usertone = $_POST["tone"];
			$creativeness = $_POST["creativity"];
			
			//Select Tone
			require ("tone.php");
			require ("creativity.php");
			
			$prompt = "Write a profile about this Fiverr seller as first person using these details:"."\r\n"."Name:".$usertitle.".\r\n"."Skills:".$usertext."\r\n".$tone."Profile:\r\n";		
			
			$data = [
				"model" => $aimodel,
				"prompt" => $prompt,
				"temperature" => $creativity,
				"max_tokens" => 150,
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
		echo '<p><strong>Seller story in one line:</strong></p>'.tagLine();
		echo '<br/><br/><p><strong>About The Seller:</strong></p>'.aiContent();
	}
	
} else {
    echo '<p style="color: red;">Oops! There was a problem generating copy. Please try again.</p>';
}
?>