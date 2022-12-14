// This is your test publishable API key.
const stripe = Stripe("pk_test_51M6YdrDAwqSpvGj6WfjgstQzI6wHmEjTdhSLHMTfXCEjE00Irdutlv9jXopA9DjKWJSnkcLcSveijV0mUemHs1c200292dGRs3");
const token_stripe = $('#Token-Stripe').val()
const citaFacturar = $('#citaFacturar').val()
let elements;

initialize();
checkStatus();

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {
  const valor = parseFloat($('#Cita-Pago-Stripe').val())
  console.log(token_stripe)
  const { clientSecret } = await fetch("utils/stripe.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ 
      dinero: valor,
      token: token_stripe,
    }),
  }).then((res) => res.json());

  elements = stripe.elements({ clientSecret });

  const paymentElementOptions = {
    layout: "tabs",
  };

  const paymentElement = elements.create("payment", paymentElementOptions);
  paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);
  await Swal.fire({
    title: '¿Estas seguro de realizar el pago con estos datos?',
    icon: 'info',
    showCloseButton: false,
    showCancelButton: true,
    showConfirmButton: true,
    // confirmButtonColor: '#28a745',
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
  }).then((res) => {
    if(res.isConfirmed){
      stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: 'http://localhost/jireh-php', 
        },
        redirect: 'if_required',
      }).then((res) => {
        if(!res.error){
          setLoading(false);
          Swal.fire({
            title: 'Se ha hecho el pago correctamente',
            text: '¿Desea solicitar factura?',
            icon: 'success',
            showCloseButton: false,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
          }).then((res) => {
            if(res.isConfirmed){
                window.location = "facturacion.php?idCita=" +  citaFacturar;
            }
            else{
                window.location = "index.php";
            }
          })
        }
        else {
          setLoading(false);
          Swal.fire({
            title: 'Ocurrió un error',
            text: res.error.message,
            icon: 'error',
            showConfirmButton: true,
            confirmButtonText: 'Aceptar',
          });
        }
      })
    }
    else{
      setLoading(false);
    }
  });

  // This point will only be reached if there is an immediate error when
  // confirming the payment. Otherwise, your customer will be redirected to
  // your `return_url`. For some payment methods like iDEAL, your customer will
  // be redirected to an intermediate site first to authorize the payment, then
  // redirected to the `return_url`.
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      showMessage("Payment succeeded!");
      break;
    case "processing":
      showMessage("Your payment is processing.");
      break;
    case "requires_payment_method":
      showMessage("Your payment was not successful, please try again.");
      break;
    default:
      showMessage("Something went wrong.");
      break;
  }
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
}