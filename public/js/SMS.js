function sendSMS() {
    const recipient = '+21629228940'; // Replace with the recipient's phone number
    const message = 'This is a test message!'; // Replace with the message you want to send
    const accountSid = 'AC9f17d20dffc59a1cd6e1ded224da3b20'; // Replace with your Twilio account SID
    const authToken = '6a1a52325a07843b9205f5def2283b41'; // Replace with your Twilio auth token
    const url = `https://api.twilio.com/2010-04-01/Accounts/${accountSid}/Messages.json`;

    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'Authorization': 'Basic ' + btoa(accountSid + ':' + authToken),
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        data: {
            'To': recipient,
            'Body': message,
            'From': '+1234567890' // Replace with your Twilio phone number
        },
        success: function (response) {
            console.log(response);
            alert('SMS sent successfully!');
        },
        error: function (error) {
            console.log(error);
            alert('Failed to send SMS!');
        }
    });
}