<?php

include('config.php');


if (isset($_POST['amount']) && isset($_POST['nonce'])) {
    
     $paymentMethodNonce = Braintree_PaymentMethodNonce::find($_POST['nonce']);
  echo "Nonce details: ";
   print_r(json_encode($paymentMethodNonce));
   
   echo "\n";
   echo "\n";

    $result = Braintree_Transaction::sale([
        'amount' => $_POST['amount'],
        'paymentMethodNonce' => $_POST['nonce'],
        'customer' => ['email' => 'customer@example.com',
            'company' => 'Company name'
        ],

        /**
         *  if needed:
         */
        'options' => ['skipAdvancedFraudChecking' => true],

    ]);

    if ($result->success) {
        echo "Success\n";
        echo "Transaction details";
       print_r(json_encode($result));
        /**
         * submit for settlement after success:
         */
        Braintree_Transaction::submitForSettlement($result->transaction->id);
    } else {
        echo "Fail: " . $result->message;
    }

}


function createNonce($card_id){
  $result = Braintree_PaymentMethodNonce::create($card_id);
  return $result->paymentMethodNonce->nonce;
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Braintree card payment implementation Javascript SDK v.3</title>
    <meta name="description" content="Braintree card payment implementation Javascript SDK v.3">
    <meta name="author" content="Jozef Vacval">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #frm {
            width: 200px;
            margin: auto;
        }

        #modal {
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            height: 100vh;
            width: 100vw;
            z-index: 100;
        }

        .bt-modal-frame {
            height: 480px;
            width: 440px;
            margin: auto;
            background-color: #eee;
            z-index: 2;
            border-radius: 6px;
        }

        .bt-modal-body {
            height: 400px;
            margin: 0 20px;
            background-color: white;
            border: 1px solid lightgray;
        }

        .bt-modal-header, .bt-modal-footer {
            height: 40px;
            text-align: center;
            line-height: 40px;
        }

        .bt-mask {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: black;
            opacity: 0.8;
        }
    </style>
</head>

<body>


<form id='frm' method="post">
    Card token:
    <input id="card_id"  name="card_id"class="form-control">
    Amount:
    <input id="amount" name="amount" class="form-control" value="1">
    <input name="nonce" id="nonce" type="hidden" class="form-control">
    <input id="pay-btn" type="submit" value="Loading...">
</form>


<div id="modal" class="hidden">
    <div class="bt-mask"></div>
    <div class="bt-modal-frame">
        <div class="bt-modal-header">
            <div class="header-text">Authentication</div>
        </div>
        <div class="bt-modal-body"></div>
        <div class="bt-modal-footer"><a id="text-close" href="#">Cancel</a></div>
    </div>
</div>

<script src="assets/jquery.js"></script>
<script src="https://js.braintreegateway.com/web/3.29.0/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.29.0/js/three-d-secure.js"></script>
<script src="https://js.braintreegateway.com/web/3.29.0/js/hosted-fields.js"></script>


<script>
    var payBtn = $('#pay-btn');
    var modal = $('#modal');
    var bankFrame = $('.bt-modal-body');
    var closeFrame = $('#text-close');
    var amountInput = $('#amount');
    var card_id = $('#card_id');
    

    var components = {
        client: null,
        threeDSecure: null
    };

    $.getJSON("generateToken.php", function (data) {
        onFetchClientToken(data.client_token);
    });


    function onFetchClientToken(clientToken) {
        braintree.client.create({
            authorization: clientToken
        }, onClientCreate);
    }

    function onClientCreate(err, client) {
        if (err) {
            alert(err.message);
            return;
        }

        components.client = client;

        braintree.threeDSecure.create({
            client: client
        }, onComponent('threeDSecure'));
    }

    function onComponent(name) {
        return function (err, component) {
            if (err) {
                alert(err.message);
                return;
            }

            components[name] = component;

                setupForm();
          
        }
    }

    function setupForm() {
        enablePayNow();
    }

    function addFrame(err, iframe) {
        bankFrame.append(iframe);
        modal.removeClass('hidden');
    }

    function removeFrame() {
        var iframe = bankFrame.find('iframe');
        modal.addClass('hidden');
        iframe.remove();
    }

    function enablePayNow() {
        payBtn.val('Pay Now');
        payBtn.removeAttr('disabled');
    }

    closeFrame.click(function () {
        components.threeDSecure.cancelVerifyCard(removeFrame());
        enablePayNow();
    });

    payBtn.click(function (event) {
        event.preventDefault();
        payBtn.attr('disabled', 'disabled');
        payBtn.val('Processing...');
           var newNonce = <?php createNonce('db48pz') ?> 
            components.threeDSecure.verifyCard({
                amount: amountInput.val(),
                nonce: newNonce,
                addFrame: addFrame,
                removeFrame: removeFrame
            }, function (err, verification) {
                if (err) {
                    alert(err.message);
                    enablePayNow();
                    return;
                }

                $('#nonce').val(verification.nonce);
                $('#frm').submit();

            });
        });

</script>
</body>
</html>
