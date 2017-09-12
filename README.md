Installtion instructions
This application is  a laravel app, all the database migrations have been included. Change the "env" file for database details.

This is a secure API. Setup a dummy client with a client id and client key in the clients table.

End point for Signup API
 curl -X POST http://localhost/signup
Header Data:{clientId:abc,hash:709808nmkksa}
// hash is becrypt value of clientId,clientKey . forexample: for clientId: abc and clientKey:abc, calculate hash for abc,abc

Post Data: {name:shubh
email:a@gmail.com
password:shubham.1
}

response{api_token:hash}

End point for Login API
 curl -X POST http://localhost/signin 
Header Data:{clientId:abc,hash:709808nmkksa}
// hash is becrypt value of clientId,clientKey . forexample: for clientId: abc and clientKey:abc, calculate hash for abc,abc

Post Data: {
email:a@gmail.com
password:shubham.1
}

response{api_token:hash}

End point for Getting user details
 curl -X GET http://localhost/userDetails?api_token=
Header Data:{clientId:abc,hash:709808nmkksa}
// hash is becrypt value of clientId,clientKey . forexample: for clientId: abc and clientKey:abc, calculate hash for abc,abc



response{user details}

End point for Dashboard
 curl -X GET http://localhost/dashboard
Header Data:{clientId:abc,hash:709808nmkksa}
// hash is becrypt value of clientId,clientKey . forexample: for clientId: abc and clientKey:abc, calculate hash for abc,abc



A table with all the data about how many calls have been made by different apps.
