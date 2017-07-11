<?php
require_once "./util.php";

// Handle post case
// Send msg
if(isPost()){
  $user_id = $_POST['user_id'];
  $message_text = $_POST['message_text'];
  sendMessage($user_id, $message_text);

  echo "Send msg success";
}

// Read from log
$userIds = getUserMessengerIds();

// Get user info of these guy
$userInfos = [];

foreach($userIds as $userId){
  $userInfo = getUserInfo($userId);
  if(!is_null($userInfo)){
    $userInfo['user_id'] = $userId;
    $userInfos[] = $userInfo;
  }
}

$webhookLog = getWebhookLog();

?>
<h3>Send message to customer</h3>
<ul>User List
  <?php foreach($userInfos as $userInfo): ?>
    <?php $i = $userInfo['user_id']; $f = $userInfo['first_name']; $l = $userInfo['last_name']; $p = $userInfo['profile_pic']; $g = $userInfo['gender']?>
    <li>
      <form method="POST">
        <div>to</div>
        <input type="hidden" name="user_id" value="<?php echo $i; ?>" />
        <div style="display: flex;">
          <img src="<?php echo $p; ?>" width="40" height="40" />
          <div>
            <div><?php echo "$f $l"; ?></div>
            <div><?php echo $g; ?></div>
          </div>
        </div>
        <input type="text" name="message_text" placeholder="message" />
        <button>Send</button>
      </form>
    </li>
  <?php endforeach; ?>
</ul>

<h3>Conversation Log</h3>
<button onclick="window.location.href = '';">Refresh</button>
<pre style="border: 1px dashed black;">
  <?php echo $webhookLog; ?>
</pre>

