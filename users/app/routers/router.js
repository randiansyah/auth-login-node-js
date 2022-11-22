const e = require('express');
let express = require('express');
let router = express.Router();

const db = require('../config/db.config.js');
const users = require('../controllers/users.controller.js');
const ApiKey = db.ApiKey;

async function verifyToken (req, res, next) {
    let authorization = req.header('Authorization');
    if(authorization != null){
        const key = await ApiKey.findOne({ where: { key: authorization, level: 4, type_key : "web" } });
        if(!key){
            res.status(401).json({
                message: "Permission denied",
            });      
        }else{
            next();
        }
    }
    else
    {
        res.status(401).json({
            message: "Bad request. Can not find any query param",
        });
    }
}

router.post('/api/users/create', verifyToken, users.create);
router.get('/api/users/all', verifyToken, users.retrieveAll);
router.get('/api/users/getby/:id', verifyToken, users.getById);
router.put('/api/users/update/:id', verifyToken, users.updateById);
router.delete('/api/users/delete/:id', verifyToken, users.deleteById);
// router.get('/api/users/filtering', verifyToken, users.filteringBy);
// router.get('/api/users/pagination', verifyToken, users.pagination);
// router.get('/api/users/pagefiltersort', users.pagingfilteringsorting);

module.exports = router;