<?php
require __DIR__ . '/../vendor/autoload.php';
require "../connection.php";
use GuzzleHttp\Client;

$flights = $connection->prepare("SELECT * FROM flights;");
$hotels = $connection->prepare("SELECT * FROM hotels;");

class OpenAIHandler
{
    private $apiKey;
    private $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client(['base_uri' => 'https://api.openai.com/v1/']);
    }

    public function generateText($prompt)
    {
        try {
            $response = $this->client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 150,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $text = $data['choices'][0]['message']['content'];

            return $text;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}

$apiKey = 'sk-proj-Aipe7uZ3TkOh1sVA72IqT3BlbkFJA5f1uFownfs7cO1SxM0G'; 

$openAIHandler = new OpenAIHandler($apiKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $destination=$_POST['destination'];
    $days=$_POST['days'];
    $budget=$_POST['budget'];

    if ($destination && $days && $budget) {
        $flights->execute();
        $flightResults = $flights->get_result()->fetch_all(MYSQLI_ASSOC);
        $hotels->execute();
        $hotelResults = $hotels->get_result()->fetch_all(MYSQLI_ASSOC);

        $prompt = "Please suggest a flight and hotel according to these inputs: {destination: $destination, days the client wants to stay: $days, budget of the client: $budget} using the following data from the database: {flights: " . json_encode($flightResults) . ", hotels: " . json_encode($hotelResults) . "}. Respond in JSON format that will be returned to the frontend.";

        $responseText = $openAIHandler->generateText($prompt);

        echo json_encode(['response' => $responseText]);
    } else {
        echo json_encode(['error' => 'Missing required parameters']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
