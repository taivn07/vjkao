<?php
	if(isset($this->cols)){ 
?>
<tr>
	<td class="form_title" style="padding-bottom: 0;"><?php echo $this->label;?></td>
</tr>
<tr>
	<td colspan="<?php echo $this->cols; ?>" <?php if(isset($this->style)){	echo 'style="' . $this->style . '"';}?>>
		<?php echo $this->input;?>
		<?php
			if(isset($this->desc)){
				echo '<div class="desc">(' . $this->desc . ')</div>';
			} 
		?>
		<div class="clr"></div>
	</td>
</tr>
<?php
	}else{ 
?>
<tr>
	<td class="form_title"><?php echo $this->label;?></td>
	<td <?php if(isset($this->style)){	echo 'style="' . $this->style . '"';}?>>
		<?php echo $this->input;?>
		<?php
			if(isset($this->desc)){
				echo '<span class="desc">(' . $this->desc . ')</span>';
			} 
		?>
		<div class="clr"></div>
	</td>
</tr>
<?php
	} 
?>