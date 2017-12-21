<?php
function commit_link($user, $repo, $commit)
{
    return "/commit.php?user=" . $user . "&repo=" . $repo . "&sha=" . $commit["sha"];
}

function short_message($commit)
{
    $message = $commit["commit"]["message"];
    return (strlen($message) > 120) ? substr($message, 0, 117) . "..." : $message;
}

if (empty($_GET["user"]) || empty($_GET["repo"])) {
    $user = "torvalds";
    $repo = "linux";
} else {
    $user = $_GET["user"];
    $repo = $_GET["repo"];
}
$user_agent = "PHP";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/" . $user . "/" . $repo . "/commits");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
$json = curl_exec($ch);
curl_close($ch);
$decoded_json = json_decode($json, true);
$not_found = !empty($decoded_json["message"]) && $decoded_json["message"] === "Not Found";
$exceeded_rate = !empty($decoded_json["message"]) && substr($decoded_json["message"], 0, 23) === "API rate limit exceeded";
?>
<html>
<head>
    <title>Main</title>
    <link rel='stylesheet' type='text/css' href='main.css'>
</head>
<body>
<header>
    <nav>
        <form id="searchForm" action="main.php" method="get">
            <p class="inlineForm">User: <input type="text" name="user" value="<?php echo $user?>"/></p>
            <p class="inlineForm">Repo: <input type="text" name="repo" value="<?php echo $repo?>"/></p>
            <p class="inlineForm"><input type="submit" value="OK"></p>
        </form>
    </nav>
</header>
<?php if ($not_found) { ?>
    <p>Requested repository not found</p>
<?php } else if ($exceeded_rate) { ?>
    <p>API rate limit exceeded!</p>
<?php } else foreach ($decoded_json as $commit) { ?>
    <div class="commit">
        <div class="column left">
            <?php if (!empty($commit["committer"])) { ?>
                <a href="https://github.com/<?php echo $commit["committer"]["login"] ?>">
                    <img class="avatar" src="<?php echo $commit["committer"]["avatar_url"] ?>">
                </a>
                <span><?php echo $commit["committer"]["login"] ?></span>
            <?php } else { ?>
                <span><?php echo $commit["commit"]["committer"]["name"] ?></span>
            <?php } ?>
        </div>
        <div class="column right">
        <a class="message" href="<?php echo commit_link($user, $repo, $commit) ?>" title="Details">
            <?php echo short_message($commit) ?>
        </a>
        </div>
    </div>
    <br/>
<?php } ?>
</body>
</html>
