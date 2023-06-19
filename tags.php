<div>
    <div style="margin-left:15px;">
        <div>
            <div>
                <div>
                    <span style="font-size:11px;color:gray;">Trending</span>
                </div>
            </div>
            <div>
                <span style="font-weight:bold;font-size:12px;">
                    <a href="<?= ROOT ?><?php echo $FRIEND_ROW['type']; ?>/<?php echo $FRIEND_ROW['tag_name']; ?>" class="username" style="font-weight:bold;color:black;">
                        @<?php echo $FRIEND_ROW['tag_name'] ?>
                    </a>
                </span>
            </div>
            <div>
                <span style="font-size:11px;color:gray;"><?php echo (rand(10, 100)); ?> Mentions</span>
            </div>
        </div>
    </div>
</div>
<br>