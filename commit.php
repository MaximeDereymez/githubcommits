<?php
if (empty($_GET["user"]) || empty($_GET["repo"])) {
    $user = "torvalds";
    $repo = "linux";
} else {
    $user = $_GET["user"];
    $repo = $_GET["repo"];
}
if (empty($_GET["sha"])) {
    header('Location: main.php?' . $_SERVER["QUERY_STRING"]);
    die;
} else
    $sha = $_GET["sha"];
$user_agent = "PHP";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/" . $user . "/" . $repo . "/commits/" . $sha);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
$json = curl_exec($ch);
curl_close($ch);
$commit = json_decode($json, true);
$not_found = !empty($commit["message"]) && $commit["message"] === "Not Found";
$exceeded_rate = !empty($decoded_json["message"]) && substr($decoded_json["message"], 0, 23) === "API rate limit exceeded";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Details</title>
    <link rel='stylesheet' type='text/css' href='main.css'>
</head>
<body>
<header>
    <nav>
        <a id="backButton" href="main.php?user=<?= $user ?>&repo=<?= $repo ?>">< Back</a>
        <form id="searchForm" action="main.php" method="get">
            <p class="inlineForm">User: <input type="text" name="user" value="<?= $user ?>"/></p>
            <p class="inlineForm">Repo: <input type="text" name="repo" value="<?= $repo ?>"/></p>
            <p class="inlineForm"><input type="submit" value="OK"></p>
        </form>
    </nav>
</header>
<?php if ($not_found) { ?>
    <p>Requested commit not found</p>
<?php } else if ($exceeded_rate) { ?>
    <p>API rate limit exceeded!</p>
<?php } else { ?>
    <div class="commit">
        <div>
            <?php if (!empty($commit["committer"])) { ?>
                <a href="https://github.com/<?= $commit["committer"]["login"] ?>">
                    <img class="avatar" src="<?= $commit["committer"]["avatar_url"] ?>">
                </a>
                <span><?= $commit["committer"]["login"] ?></span>
            <?php } else { ?>
                <span><?= $commit["commit"]["committer"]["name"] ?></span>
            <?php } ?>
            <span style="float: right"><?= $commit["sha"] ?></span>
        </div>
        <p class="message"><?= $commit["commit"]["message"] ?></p>
        <?php if (!empty($commit["stats"])) { ?>
            <ul>
                Modifications
                <li>Added lines: <?= $commit["stats"]["additions"]; ?></li>
                <li>Deleted lines: <?= $commit["stats"]["deletions"]; ?></li>
                <li>Total: <?= $commit["stats"]["total"]; ?></li>
            </ul>
        <?php } ?>
        <?php if (!empty($commit["files"])) { ?>
            <ul>
                Modified files
                <?php foreach ($commit["files"] as $file) { ?>
                    <li><?= $file["filename"]; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
<?php } ?>
</body>
</html>