<!-- Display a payment form -->
<form id="payment-form">
      <div id="payment-element">
        <!--Stripe.js injects the Payment Element-->
      </div>
      <div class="py-3">
        <small>
          <b>El importe es de $<?php echo $cita['Costo'];?></b>
        </small>
      </div>
      <button id="submit" class="btn btn-primary w-100">
        <div class="spinner hidden" id="spinner"></div>
        <span id="button-text">Pagar ahora</span>
      </button>
      
      <div id="payment-message" class="hidden"></div>
</form>