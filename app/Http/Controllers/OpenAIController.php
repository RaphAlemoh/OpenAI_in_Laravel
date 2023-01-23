<?php

namespace App\Http\Controllers;

use OpenAI;
use Illuminate\Support\Facades\Http;

class OpenAIController extends Controller
{
    private $models = [
        "babbage" => "text-babbage-001",
        "curies" => "text-curie-001",
        "ada" => "text-ada-001",
        "davinci" => "text-davinci-003",
        "code_davinci" => "code-davinci-002"
    ];


    public function open_ai()
    {
        $client = OpenAI::client(env('OPEN_AI_TOKEN'));

        $prompt = "What is Laravel framework";

        $result = $client->completions()->create([
            'model' => $this->models['davinci'],
            'prompt' => $prompt,
        ]);

        echo $result['choices'][0]['text'];
    }



    public function open_ai_http()
    {

        try {
            $prompt = "OpenAI API is";

            //As a rough rule of thumb, 1 token is approximately 4 characters or 0.75 words for English text
            $maxTokens = 50;

            $open_ai_response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " . env('OPEN_AI_TOKEN')
            ])->post("https://api.openai.com/v1/engines/$this->models['davinci']/completions", [
                'prompt' => $prompt,
                "temperature" => 0.7,
                "max_tokens" => $maxTokens,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" => 0,
            ])->json();


            return (!$open_ai_response) ?  "No response available!" : $open_ai_response['choices'][0]['text'];
        } catch (\Throwable $th) {
            return  $th;
        }
    }

    public function open_ai_http_codex(){
        try {

            // python code to explain
            $prompt = "class Log:\n    def __init__(self, path):\n        dirname = os.path.dirname(path)\n        os.makedirs(dirname, exist_ok=True)\n        f = open(path, \"a+\")\n\n        # Check that the file is newline-terminated\n        size = os.path.getsize(path)\n        if size > 0:\n            f.seek(size - 1)\n            end = f.read(1)\n            if end != \"\\n\":\n                f.write(\"\\n\")\n        self.f = f\n        self.path = path\n\n    def log(self, event):\n        event[\"_event_id\"] = str(uuid.uuid4())\n        json.dump(event, self.f)\n        self.f.write(\"\\n\")\n\n    def state(self):\n        state = {\"complete\": set(), \"last\": None}\n        for line in open(self.path):\n            event = json.loads(line)\n            if event[\"type\"] == \"submit\" and event[\"success\"]:\n                state[\"complete\"].add(event[\"id\"])\n                state[\"last\"] = event\n        return state\n\n\"\"\"\nHere's what the above class is doing:\n1.";

            //As a rough rule of thumb, 1 token is approximately 4 characters or 0.75 words for English text
            $maxTokens = 64;

            $open_ai_response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " . env('OPEN_AI_TOKEN')
            ])->post("https://api.openai.com/v1/engines/$this->models['code-davinci']/completions", [
                'prompt' => $prompt,
                "temperature" => 0.7,
                "max_tokens" => $maxTokens,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" => 0,
                "stop" => ["\"\"\""]
            ])->json();


            return (!$open_ai_response) ?  "No response available!" : $open_ai_response['choices'][0]['text'];
        } catch (\Throwable $th) {
            return  $th;
        }
    }
}
