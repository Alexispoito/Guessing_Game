<?php
session_start();

if (isset($_POST['restart'])) {
    unset($_SESSION['target_number'], $_SESSION['attempts']);
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['target_number'])) {
    $_SESSION['target_number'] = rand(1, 100);
    $_SESSION['attempts'] = 0;
}

$message = "";
$game_won = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess']) && $_POST['guess'] !== '') {
    $guess = intval($_POST['guess']);
    $_SESSION['attempts']++;

    if ($guess < $_SESSION['target_number']) {
        $message = "❌ Too low! Try again.";
    } elseif ($guess > $_SESSION['target_number']) {
        $message = "❌ Too high! Try again.";
    } else {
        $message = "🎉 Congratulations! You guessed the number in {$_SESSION['attempts']} attempts.";
        $game_won = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Guessing Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="game-container">
        <h1>Guess the Number!</h1>
        <p>I'm thinking of a number between 1 and 100. Can you guess what it is?</p>

        <?php if (!$game_won): ?>
            <form method="POST">
                <input type="number" name="guess" min="1" max="100" placeholder="Enter your guess" required>
                <button type="submit">Guess</button>
            </form>
        <?php endif; ?>

        <div class="message"><?php echo htmlspecialchars($message); ?></div>

        <?php if ($game_won): ?>
            <form method="POST">
                <button type="submit" name="restart">Restart Game</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
