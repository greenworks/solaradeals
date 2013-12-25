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
<form action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post" name="paymentForm">
<input type="text" name="email_cobranca" value="<?php echo $this->attributeConfig->merchant_email; ?>" />
<input type="hidden" name="tipo" value="CBR" />
<input type="hidden" name="moeda" value="BRL" />
<input type="text" name="item_id" value="<?php echo $this->orderDisplayId; ?>" />
<input type="text" name="item_descr" value="Make Purchase from <?php echo $this->systemName ;?>" />
<input type="hidden" name="item_quant" value="1" />
<input type="text" name="item_valor" value="<?php echo $this->cart->getTotalPrice()*100;?>" />
<input type="hidden" name="frete" value="0" />
<input type="hidden" name="peso" value="0" />
<input type="submit" value="ok" />
</form> 
You will be redirected to Paypal in a moment. Please wait...
</div>
<script>
   // document.paymentForm.submit();
</script>
