<?php 
require 'vendor/autoload.php';

function generateChatResponse($message, $openaiApiKey) {
    $url = 'https://api.openai.com/v1/chat/completions';

    $client = new GuzzleHttp\Client();

    $headers = [
        'Authorization' => 'Bearer ' . $openaiApiKey,
        'Content-Type' => 'application/json',
    ];

    $data = [
        'messages' => [['role' => 'system', 'content' => 'You are a PHP developer.'], ['role' => 'user', 'content' => $message]],
        'max_tokens' => 200, // Adjust the response length as per your requirements.
        'model' => 'gpt-3.5-turbo',
    ];

    $response = $client->post($url, [
        'headers' => $headers,
        'json' => $data,
    ]);

    $responseBody = json_decode($response->getBody(), true);
    $reply = $responseBody['choices'][0]['message']['content'];

    return $reply;
}   

$openaiApiKey = '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'; // Replace with your actual OpenAI API key
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'])) {
    $prompt = $_POST['question'];
    $chatReply = generateChatResponse($prompt, $openaiApiKey);
    // echo $chatReply; // Output the generated response
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlgoHub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Josefin+Sans:wght@500&family=Kdam+Thmor+Pro&family=Roboto+Serif:opsz@8..144&display=swap" rel="stylesheet">
    
<style>
       .output-div {
        margin-top: 1rem;
        max-width: 100%;
        overflow: auto;
        display: flex;
    }

    .copy-button {
        flex: 0 0 auto;
        margin-right: 1rem;
    }

    #copy-output {
        padding: 0.25rem 0.5rem;
    }

    .mockup-code {
        flex: 1;
        white-space: pre-wrap;
        font-family: monospace;
        padding: 15px; /* Use a monospace font for code-like appearance */
    }
</style>

</head>
<body>
<div id="loader" class="hidden fixed top-0 left-0 w-full h-full bg-white opacity-75 z-50 flex justify-center items-center">
<span class="loading loading-dots loading-md"></span>
</div>
<div class="navbar bg-neutral text-white">
        <div class="navbar-start">
          <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li class="active"><a>HOME</a></li>
                <li><a>HOW IT WORKS</a></li>
                <li><a>ABOUT US</a></li>
                <li><a>CONTACT</a></li>
              </ul>
            </ul>
          </div>
          <a class="btn btn-ghost text-xl" href="index.html"><>AlgoHUB</></a>
        </div>
        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1">
                <li class="active"><a>HOME</a></li>
                <li><a>HOW IT WORKS</a></li>
                <li><a>ABOUT US</a></li>
                <li><a>CONTACT</a></li>
          </ul>
        </div>
        <div class="navbar-end">
            <button class="btn btn-sm sm:btn-sm md:btn-md btn-primary lg:btn btn-primary">GENERATE</button>
        </div>
      </div>

<section class="p-10 bg-white text-black">
    <h1 class="text-3xl font-bold text-center">Enter Prompt to Generate Code</h1>
    <p class="py-6 text-center">You can use it only 3 times without signing in. <span class="text-primary">Login</span> Here.</p>

    <div class="card lg:card-side bg-base-100 shadow-xl">
        <figure><img src="images/c3.jpg" class="w-80" alt="Album" style="height: 70vh; width: 30rem;" /></figure>
        <div class="card-body mt-20">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Write Your Prompt</span>
                </div>
            </label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" id="prompt-input" name="question" placeholder="Write code for Bubble Sort in C++..." class="input input-bordered w-full" />
                <button id="generate-btn" class="btn btn-md sm:btn-md btn-outline btn-primary md:btn-md btn-active btn-primary lg:btn-md btn-outline btn-primary mt-5">Generate Now</button>
            </form>
        </div>
    </div>

    <!-- Output-div with CSS to control text appearance -->
  <div class="output-div">
        <div class="copy-button">
            <button id="copy-output" class="btn btn-outline btn-primary">Copy</button>
        </div>
        <div class="mockup-code p-15">
            <?php if (isset($chatReply)) : ?>
                <pre id="output-text"><?php echo $chatReply; ?></pre>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Add an event listener for when the page finishes loading
    document.addEventListener("DOMContentLoaded", function() {
        // Show the loader when the page starts loading
        document.getElementById("loader").classList.remove("hidden");
        
        // Add a delay to simulate loading (e.g., 3 seconds)
        setTimeout(function() {
            // Hide the loader after the delay
            document.getElementById("loader").classList.add("hidden");
        }, 3000);
    });
</script>
<script>
    document.getElementById("copy-output").addEventListener("click", function () {
        var outputText = document.getElementById("output-text");
        var textArea = document.createElement("textarea");
        textArea.value = outputText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("Copied to clipboard!");
    });
</script>
</body>
</html>