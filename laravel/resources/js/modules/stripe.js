const axios = require('axios');
let isLoading = false;
let stripe;

module.exports = function(id, withMethodId = false) {
    // disable button until we have the page ready
    changeLoadingState(true);
    axios({
        method: 'POST',
        url: '/api/payment-intent/' + id,
        headers: {
            'Content-Type': 'application/json'
        },
        params: {
            'api_token': document.getElementById('api_token').value
        }
    })
        .then(res => res.data)
        .then(data => {
            if (withMethodId) {
               return {
                   stripe: Stripe(data.publishable_key),
                   clientSecret: data.client_secret
               }

            } else {
                return setupElements(data);
            }

        })
        .then(({stripe, card, clientSecret}) => {
            document.getElementById('pay').disabled = false;
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', e => {
                e.preventDefault();
                if (card) {
                    pay(stripe, card, clientSecret);
                } else {
                    const methodId = getSelectedPayMethod();
                    if (!methodId) {
                        alert('Please select a card to pay with');
                    } else {
                        payWithMethodId(stripe, methodId, clientSecret );
                    }
                }
            });
            changeLoadingState(false);
        });
}

function getSelectedPayMethod() {
    const methods = document.getElementsByName('methods');
    for(let i = 0; i < methods.length; i++) {
        if (methods[i].checked) {
            return methods[i].value;
        }
    }
    return null;
}
function setupElements(data) {
    stripe = Stripe(data.publishable_key);
    const elements = stripe.elements();
    const style = {
        base: {
            fontFamily: '"Lato", sans-serif',
            fontSize: '24px'
        }
    }

    const card= elements.create('card', {style: style});
    card.mount('#card');

    return {
        stripe: stripe,
        card: card,
        clientSecret: data.client_secret
    };
}

function payWithMethodId(stripe, methodId, clientSecret) {
    changeLoadingState(true);

    stripe.confirmCardPayment(clientSecret, {
        payment_method: methodId,
    })
        .then(res => {
            changeLoadingState(false);
            if (res.error) {
                console.log(res.error.message);
            } else {
                showTheForm(res.paymentIntent);
            }
        });

}
function pay(stripe, card, clientSecret) {
    changeLoadingState(true);
    const futureUse = document.getElementById('future-use').checked ? 'on_session' : false;

    const options = {
        payment_method: {
            card: card
        }
    };

    if (futureUse) options.setup_future_usage = futureUse;
    console.log(options);

    stripe.confirmCardPayment(clientSecret, options)
        .then(res => {
            changeLoadingState(false);
            if (res.error) {
                console.log(res.error);
            } else {
                showTheForm(res.paymentIntent);
            }
        });
}

function showTheForm(data) {
    const date = new Date(data.created * 1000);
    document.getElementById('amount').innerText = '$' + (data.amount / 100).toFixed(2);
    document.getElementById('date').innerText = date.toDateString() + date.toTimeString();
    document.getElementById('page-payment').style.display = 'none';
    document.getElementById('page-payment-success').style.display = 'block';
}
function changeLoadingState(isLoadingLocal) {
    isLoading = isLoadingLocal;
    if (isLoadingLocal) {
        document.getElementById('cover-loading').classList.add('is-loading');
    } else {
        document.getElementById('cover-loading').classList.remove('is-loading');
    }

}
