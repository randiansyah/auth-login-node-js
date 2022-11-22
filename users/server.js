const express = require('express');
const app = express();

var bodyParser = require('body-parser');
 
const db = require('./app/config/db.config.js');
  
// migrate 
// db.ConnBank.sync({force: false}).then(() => {
//   console.log('Drop and Resync with { force: true }');
// }); 

let router = require('./app/routers/router.js');

const cors = require('cors')
const corsOptions = {
  origin: 'http://localhost:4200',
  optionsSuccessStatus: 200
}
app.use(cors(corsOptions));

app.use(bodyParser.json());
app.use('/', router);

const port = process.env.PORT || 3000;
app.listen(port);
console.log('Listening on localhost:'+ port);