const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const path = require('path');
const pug = require('pug');
const app = express();
// const pdftron = require('@pdftron/pdfnet-node');
const PORT = 8001;
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'docreview'
});

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(session({
    secret: 'somesecretkey',
    resave: true,
    saveUninitialized: true,
    cookie: {
        maxAge: 1000 * 60 * 60 * 24,
    },
}));

app.set('views', path.join(__dirname));
app.set('view engine', 'pug');
app.use(express.static(path.join('../')));

app.listen(PORT, 'localhost', () => {
    console.log(`NodeJS Server is running at port ${PORT}`);
});

app.get('/', (request, response) => {
    if (request.session.user) {
        response.redirect('/reviewer-home');
    } else {
        response.render('index');
    }
});

app.post('/login', (request, response) => {
    // Receive login details
    const email = request.body.email;
    const password = request.body.password;
    // Verify login details
    connection.query('SELECT * FROM users WHERE email = ? AND password = ?', [email, password], (error, result) => {
        if (result.length === 0) {
            console.error('Invalid email or password.');
            response.redirect('/');
        }

        if (result[0].status != "Online") {
            //Login Successful
            console.log('Login Successful');
            // Set session variables
            request.session.user = {
                email: result[0].email,
                firstName: result[0].firstName,
                lastName: result[0].lastName,
                role: result[0].role
            };
            response.redirect('/reviewer-home');
            // Make user Online
            connection.query('UPDATE users SET status="Online" WHERE email= ?', [email], (error) => {
                if (error) {
                    console.error(error);
                    response.redirect('/');
                }
            });
        } else {
            console.error("User is already logged in.");
            response.redirect('/');
        }
    });
});

app.get('/reviewer-home', (request, response) => {
    if (request.session.user) {
        if (request.session.user.role == "Reviewer") {
            console.log("Logged in as a rev");
            response.render('reviewer-home');
        } else {
            console.log("Not allowed");
            response.redirect('/');
        }
    } else {
        console.log("Log in first");
        response.redirect('/');
    }
});

app.get('/logout', (request, response) => {
    const email = request.session.user.email;
    // Make user Offline
    connection.query('UPDATE users SET status="Offline" WHERE email= ?', [email], () => {
        console.log("Logged out");
    });
    request.session.destroy(() => {
        response.redirect('/');
    });
});