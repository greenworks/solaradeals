<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/
?>
<div style="margin-top:0px">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paymentForm">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $this->attributeConfig->merchant_email; ?>">
<input type="hidden" name="lc" value="<?php  echo $this->attributeConfig->country_code; ?>">
<input type="hidden" name="button_subtype" value="services">
<INPUT TYPE="hidden" name="charset" value="utf-8">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="rm" value="2">

<INPUT TYPE="hidden" NAME="display" value="0">
<INPUT TYPE="hidden" NAME="amount" value="<?php echo $this->cart->getTotalPrice(); ?>">
<INPUT TYPE="hidden" NAME="quantity" value="1">
<INPUT TYPE="hidden" NAME="currency_code" value="<?php echo $this->attributeConfig->currency_code; ?>">

<INPUT TYPE="hidden" NAME="cbt" value="Return back to <?php echo $this->systemName ;?>">
<input type="hidden" name="item_name" value="Make Purchase from <?php echo $this->systemName ;?>">
<input type="hidden" name="item_number" value="<?php echo $this->orderDisplayId; ?>">
<INPUT TYPE="hidden" NAME="return" value="<?php echo $this->returnUrl;?>">
<INPUT TYPE="hidden" NAME="cancel_return" value="<?php echo $this->cancelUrl; ?>">
<INPUT TYPE="hidden" NAME="notify_url" value="<?php echo $this->notifyUrl; ?>">

<input type="hidden" name="bn" value="Matamko_Channel_EC_SG">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
You will be redirected to Paypal in a moment. Please wait...
</div>
<script>
    document.paymentForm.submit();
</script>
