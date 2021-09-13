//Ladda.bind('input[type=submit]', { timeout: 10000 });

// Bind normal buttons
// Ladda.bind('.ladda-button', { timeout: 10000 });

// Bind progress buttons and simulate loading progress
// Ladda.bind('.ladda-button', {
//     callback: function(instance) {
//         var progress = 0;
//         var interval = setInterval(function() {
//             progress = Math.min(progress + Math.random() * 0.1, 1);
//             instance.setProgress(progress);

//             if (progress === 1) {
//                 instance.stop();
//                 clearInterval(interval);
//             }
//         }, 200);
//     }
// });

var userName = "";
var accessToken = "";
var uId = "";

const scopes = ['username', 'payments'];




// const config = {headers: {'Content-Type':'application/json', 'Access-Control-Allow-Origin': '*'}};
// Read more about this callback in the SDK reference:
// function onIncompletePaymentFound(payment) { /* ... */ };
const onIncompletePaymentFound = (payment) => {
    console.log("onIncompletePaymentFound", payment)
    if (payment) {
        $.ajax({
                cache: false,
                url: "api/proposal/incomplete",
                type: "POST",
                data: {
                    "paymentid": payment.identifier,
                    "user_uid": payment.user_uid,
                    "amount": payment.amount,
                    "to_address": payment.to_address,
                    "developer_approved": payment.status.developer_approved,
                    "developer_completed": payment.status.developer_completed,
                    "transaction_verified": payment.status.transaction_verified,
                    "cancelled": payment.status.cancelled,
                    "user_cancelled": payment.status.user_cancelled,
                    "txid": ((payment.transaction) ? payment.transaction.txid : null),
                },
                dataType: "json",
            })
            .done(function(response) { //success
                if (response.success == 'OK')
                    console.log('Incomplete: done');
                else
                    console.log('Incomplete: not accepted');

                swal({
                    title: "Incomplete Proposal",
                    text: response.message,
                    //timer: 10000,
                    showConfirmButton: true,
                    type: "info"
                });
                return response;
            })
            .fail(function(response) { //error
                console.log('Incomplete: fail');
                return response
            })
            .complete(function(response) { return response });
    } else {
        console.log('Incomplete: no payment');
    }
};


const ReadyForServerApproval = (paymentId) => {
    $.ajax({
            cache: false,
            url: "api/proposal/serverapproval",
            type: "POST",
            data: {
                "propose": parseFloat($('#proposal-value').val()),
                "current": parseFloat($('#current-pivalue').text()),
                "donate": parseFloat($('#donate_value').text()),
                "paymentid": paymentId,
                "username": userName,
                "uid": uId,
            },
            dataType: "json",
        })
        .done(function(response) { //success
            if (response.success == 'OK')
                console.log('ReadyForServerApproval: done');
            else
                console.log('ReadyForServerApproval: not accepted');
        })
        .fail(function(response) { //error
            console.log('ReadyForServerApproval: fail');
        })
        .complete(function(response) {});
}

const ReadyForServerCompletion = (paymentId, txid) => {
    console.log('Call ReadyForServerCompletion');
    $.ajax({
            cache: false,
            url: "api/proposal/servercompletion",
            type: "POST",
            data: {
                "paymentid": paymentId,
                "txid": txid,
            },
            dataType: "json",
        })
        .done(function(response) { //success
            if (response.success == 'OK') {
                console.log('ReadyForServerCompletion: done');
                swal({
                    // html: true,
                    title: "Accepted !",
                    text: response.message + '\n Your proposal Id is: ' + response.data.id,
                    showConfirmButton: true,
                    type: "success"
                });
            } else {
                console.log('ReadyForServerCompletion: not accepted');
                swal({
                    title: "Not cccepted !",
                    text: response.message,
                    //timer: 50000,
                    showConfirmButton: true,
                    type: "error"
                });
            }
        })
        .fail(function(response) { //error
            console.log('ReadyForServerCompletion: fail');
        })
        .complete(function(response) {});
}

const PaymentCancel = (paymentId) => {
    console.log('Call PaymentCancel');
    $.ajax({
            cache: false,
            url: "api/proposal/cancel",
            type: "POST",
            data: {
                "paymentid": paymentId,
            },
            dataType: "json",
        })
        .done(function(response) { //success
            if (response.success == 'OK')
                console.log('PaymentCancel: done');
            else
                console.log('PaymentCancel: not accepted');

            swal({
                title: "Proposal Cancel",
                text: response.message,
                //timer: 10000,
                showConfirmButton: true,
                type: "info"
            });
        })
        .fail(function(response) { //error
            console.log('PaymentCancel: fail');
            swal({
                title: "Proposal Cancel",
                text: response.message,
                //timer: 10000,
                showConfirmButton: true,
                type: "info"
            });
        })
        .complete(function(response) {});
}

const PaymentError = (error, payment) => {
    if (payment) {
        swal("Oops!", errors.toString(), 'error');
        $.ajax({
                cache: false,
                url: "api/proposal/error",
                type: "POST",
                data: {
                    "paymentid": payment.identifier,
                },
                dataType: "json",
            })
            .done(function(response) { //success
                if (response.success == 'OK')
                    console.log('PaymentError: done');
                else
                    console.log('PaymentError: not accepted');

                swal({
                    title: "Error",
                    text: response.message,
                    //timer: 10000,
                    showConfirmButton: true,
                    type: "error"
                });
            })
            .fail(function(response) { //error
                console.log('PaymentError: fail');
                swal({
                    title: "Error",
                    text: "The proposal is unsuccessful.",
                    //timer: 10000,
                    showConfirmButton: true,
                    type: "error"
                });
            })
            .complete(function(response) {});
    } else {
        if(error.response.data.error_message){
            swal("Oops!", error.toString() + "\n" + error.response.data.error_message, 'error');
        }
        else{
            swal("Oops!", error.toString() + "\n" + "Please reload the app.", 'error');
        }
    }
}

const callbacks = {
    onReadyForServerApproval: ReadyForServerApproval,
    onReadyForServerCompletion: ReadyForServerCompletion,
    onCancel: PaymentCancel,
    onError: PaymentError,
}

// Authenticate the user, and get permission to request payments from them:
// const authResult = window.Pi.authenticate(scopes, onIncompletePaymentFound);
Pi.init({ version: "2.0"})

$(document).ready(function() {
    // Pi.init({ version: "2.0", sandbox: true })
        // const authResult = await window.Pi.authenticate(scopes, onIncompletePaymentFound);

    window.Pi.authenticate(scopes, onIncompletePaymentFound).then(function(auth) {
        $('#ready_state').removeClass('login_error');
        $('#ready_state').html("You're ready to make proposal: " + auth.user.username);
        accessToken = auth.accessToken;
        uId = auth.user.uid;
        userName = auth.user.username;
        console.log("Hi there! You're ready to make payments: " + auth.user.username);
    }).catch(function(error) {
        $('#ready_state').addClass('login_error')
        $('#ready_state').html("Authentication error !");
        console.log('Authentication error');
    });


    // if(uId != null && uId != ""){
    //     $('#ready_state').removeClass('login_error');
    //     $('#ready_state').html("You're ready to make proposal: " + auth.user.username);
    // }

    // propose pi value

    var btnPropose = $('#btn-propose');
    btnPropose.click(function(e) {
        e.preventDefault();
        var ld = Ladda.create(document.querySelector('#btn-propose'));
        ld.start();
        $.ajax({
                cache: false,
                url: "api/proposal/checkproposal",
                type: "POST",
                data: {
                    "propose": $('#proposal-value').val(),
                    "current": $('#currentpivalue').text(),
                    "donate": $('#donate_value').text(),
                    "username": userName,
                },
                dataType: "json",
            })
            .done(function(response) { //success
                if (response.success == "OK") {
                    //start payment process
                    var proposalValue = parseFloat($('#proposal-value').val());
                    var donateValue = parseFloat($('#donate_value').text());
                    paymentData = {
                        amount: donateValue, // Amount of π to be donate:
                        memo: "To propose " + proposalValue + "$/1π, you agree to donate " + donateValue + "π", // An explanation of the payment - will be shown to the user
                        metadata: { 'proposalvalue': proposalValue, 'donatevalue': donateValue }, //for your own usage
                    }
                    const paymentResult = window.Pi.createPayment(paymentData, callbacks);
                } else {
                    swal("Oops!", response.message, 'error');
                }
            })
            .fail(function(response) { //error
                if (response.message) {
                    swal("Fail!", response.message, 'error');
                } else
                if (response.responseJSON) {
                    swal("Fail!", response.responseJSON.message, 'error');
                } else {
                    swal("Fail!", "unknow error, please try again.", 'error');
                }
            })
            ////////////
        ld.stop();
        ld.remove();
    });

});


