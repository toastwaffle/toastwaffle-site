<?php   defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<style>
table#addThisBlockSetup th {font-weight: bold; text-style: normal; padding-right: 8px; white-space: nowrap; vertical-align:top ; padding-bottom:8px}
table#addThisBlockSetup td{ font-size:12px; vertical-align:top; padding-bottom:8px;}
</style> 

<table id="addThisBlockSetup" style="width:100%"> 
	<tr>
		<th><?php  echo t('AddThis User Name (optional)')?></th>
		<td>
			<input type="text" style="width:90%" name="username" value="<?php  echo $bObj->username?>"/>
			<div class="ccm-note" style="margin-bottom:16px; margin-top:4px;">
				<?php  echo t('Sign up for an account at')?>
				<a href="http://addthis.com">AddThis.com</a>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<input name="display" type="radio" value="shareLong" <?php  echo ($bObj->display=='shareLong')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/lg-share-en.gif" width="125" height="16" border="0" alt="" /><br /><br />
			<input name="display" type="radio" value="bookmarkLong" <?php  echo ($bObj->display=='bookmarkLong')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/lg-bookmark-en.gif" width="125" height="16" border="0" alt="" /><br /><br />
			<input name="display" type="radio" value="addThisLong" <?php  echo ($bObj->display=='addThisLong')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/lg-addthis-en.gif" width="125" height="16" border="0" alt="" />
		</th>
		<td>			
			<input name="display" type="radio" value="shareShort" <?php  echo ($bObj->display=='shareShort')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" border="0" alt="" /><br /><br />
			<input name="display" type="radio" value="bookmarkShort" <?php  echo ($bObj->display=='bookmarkShort')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/sm-bookmark-en.gif" width="83" height="16" border="0" alt="" /><br /><br />
			<input name="display" type="radio" value="addThisShort" <?php  echo ($bObj->display=='addThisShort')?'checked':''?> /> <img src="http://s7.addthis.com/static/btn/sm-plus.gif" width="16" height="16" border="0" alt="" />		
		</td>
	</tr>
</table>